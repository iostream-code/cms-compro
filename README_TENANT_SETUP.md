# Setup Tenant Middleware & Migration Service

## 1. Registrasi Provider

`bootstrap/providers.php`:
```php
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\TenantServiceProvider::class,
];
```

## 2. Registrasi Middleware (Laravel 11+, `bootstrap/app.php`)

Urutan **wajib**: `ResolveTenant` sebelum middleware `auth`, karena tabel
`users` ada di schema tenant.

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(prepend: [
        \App\Http\Middleware\ResolveTenant::class,
    ]);

    $middleware->alias([
        'role' => \App\Http\Middleware\EnsureRole::class,
    ]);
})
```

Tidak ada Sanctum di stack ini -- auth CMS pakai session guard `web` biasa
(lihat `config/auth.php`), karena FE-nya full Blade + Livewire/Alpine, bukan
SPA terpisah yang butuh token API.

## 3. Contoh route (`routes/web.php`)

Lihat file `routes/web.php` -- CMS pakai `middleware(['auth'])` + `role:admin,member`
per grup, visitor page publik tanpa auth sama sekali.

Super admin panel (`routes/superadmin.php`) pakai guard **terpisah**
(`auth:super_admin`), baca dari `public.super_admins`, sama sekali tidak
tersentuh `ResolveTenant`.

## 4. Alur pembuatan client baru

```bash
php artisan tenant:create "Azzahra Wisata" azzahra --custom-domain=azzahrawisata.com
```

Command ini akan:
1. Insert row ke `public.clients`
2. `CREATE SCHEMA IF NOT EXISTS client_azzahra`
3. Set `search_path` ke `client_azzahra, public`
4. Jalankan semua migration di `database/migrations/tenant/`
5. Jalankan `SectionTypeSeeder` untuk isi 12 tipe section
6. Reset `search_path` kembali ke default

## 5. Rollout migration baru ke client existing

Kalau nanti nambah kolom/tabel baru:

```bash
# taruh file migration baru di database/migrations/tenant/
php artisan tenant:migrate azzahra   # 1 client
php artisan tenant:migrate --all     # semua client aktif
```

## 6. Dashboard monitoring super admin

Dua sumber data, dua mekanisme berbeda:

| Data | Sumber | Mekanisme |
|---|---|---|
| Feed aktivitas (siapa ngapain, kapan) | `public.activity_log` | Real-time, ditulis langsung lewat trait `LogsTenantActivity` / event login |
| Statistik (total user, konten, kapan terakhir aktif) | `public.client_stats` | Rollup periodik lewat `tenant:refresh-stats` |

Kenapa dipisah: statistik butuh `COUNT()` ke tabel-tabel di schema tenant,
yang cuma bisa diakses satu per satu (search_path tidak bisa nunjuk ke
banyak schema sekaligus). Menghitungnya on-demand tiap kali dashboard
dibuka akan berarti loop N schema per request -- terlalu mahal. Activity
log sebaliknya sudah central sejak awal, jadi bisa langsung real-time.

### Jadwalkan refresh stats (`routes/console.php` atau `app/Console/Kernel.php`)

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('tenant:refresh-stats --all')->everyFifteenMinutes();
```

### Daftarkan listener login (`app/Providers/EventServiceProvider.php`)

```php
protected $listen = [
    \Illuminate\Auth\Events\Login::class => [
        \App\Listeners\LogUserLogin::class,
    ],
];
```

### Pasang trait di model yang aktivitasnya perlu dimonitor

Ganti `use Spatie\Activitylog\Traits\LogsActivity;` jadi:
```php
use App\Traits\LogsTenantActivity;

class Page extends Model
{
    use LogsTenantActivity; // bukan LogsActivity langsung
}
```
Lakukan yang sama di `Package` dan `Article`.

### Status "stale" itu sengaja, bukan bug

`ClientStat::isStale()` menandai kalau `stats_refreshed_at` sudah lebih dari
30 menit. Ini penting supaya dashboard jujur ke super admin: kalau job
scheduler gagal/telat untuk 1 client (misal schema-nya rusak), angka yang
ditampilkan bukan angka yang diam-diam basi, tapi eksplisit ditandai
`stats_stale` di endpoint `/superadmin/clients`.

## 7. Catatan tentang queue worker

Kalau ada job yang jalan di background (queue worker long-running proses),
`search_path` yang di-set di 1 job **akan terbawa** ke job berikutnya dalam
proses worker yang sama karena koneksi DB reused. Job yang butuh akses tenant
harus:

```php
app(TenantDatabaseManager::class)->useSchema($client->schema_name);
// ... logic job ...
app(TenantDatabaseManager::class)->resetToDefault();
```

Atau lebih aman: pasang listener di `JobProcessed` / `JobFailed` event untuk
auto-reset search_path supaya tidak ada job yang "salah tenant" akibat state
yang bocor dari job sebelumnya.
