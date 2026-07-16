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

## 8. Dukungan Multi-Driver (PostgreSQL & MySQL/MariaDB)

Multi-tenancy di aplikasi ini driver-aware: PostgreSQL pakai schema-per-client
(`search_path`), MySQL/MariaDB pakai database-per-client (`USE`). Pindah
driver cukup ganti `DB_CONNECTION` di `.env` -- tidak ada baris kode yang
perlu disentuh manual.

### Bagaimana ini bisa jalan tanpa cabang kode di banyak tempat

- **Connection `central`** (didaftarkan otomatis di
  `TenantServiceProvider::register()`) adalah salinan config dari connection
  default (`pgsql` atau `mysql`, ikut `DB_CONNECTION`), tapi PDO-nya terpisah
  dan **tidak pernah** disentuh oleh `TenantDatabaseManager`. Model yang
  datanya harus selalu bisa diakses terlepas dari tenant mana yang aktif --
  `Client`, `SuperAdmin`, `ClientStat`, dan `App\Models\ActivityLog` (override
  `activity_model` Spatie, lihat `config/activitylog.php`) -- semuanya pakai
  connection ini, jadi tidak perlu qualifier schema seperti `public.clients`
  lagi.
- **Connection default** (nama connection = `DB_CONNECTION`) dipakai untuk
  akses data tenant (users, pages, sections, dll, semua tabel di
  `database/migrations/tenant/`), dan schema/database aktifnya dialihkan oleh
  `TenantDatabaseManager::useSchema()` / `resetToDefault()`:
  - PostgreSQL: `SET search_path TO "client_x", public`
  - MySQL/MariaDB: `USE \`client_x\``
- Query lintas tenant/central yang tadinya butuh qualifier eksplisit (mis.
  `DB::table('activity_log')` di dashboard super admin) HARUS lewat
  `DB::connection('central')->table(...)` -- unqualified saja TIDAK CUKUP di
  MySQL karena `USE` (beda dengan `search_path` Postgres) tidak ada fallback
  ke database lain.

### Perbedaan penting yang perlu diketahui

- **DDL non-transactional di MySQL.** `CREATE DATABASE`/`CREATE TABLE`
  auto-commit di MySQL, tidak seperti PostgreSQL yang DDL-nya transactional.
  `TenantMigrationService::provision()` karena itu TIDAK membungkus
  `createSchemaIfMissing()` + `migrate()` + `seed()` dalam
  `DB::transaction()` untuk MySQL (beda dengan PostgreSQL) -- kalau gagal di
  tengah jalan, `TenantDatabaseManager::dropSchema()` dipanggil sebagai
  compensating action manual supaya tidak ada database tenant yang "zombie"
  (setengah jadi).
- **Operator case-insensitive.** `ilike` cuma ada di PostgreSQL. Pakai
  `TenantDatabaseManager::caseInsensitiveLikeOperator()` (bukan hardcode
  `ilike`/`like`) di query pencarian mana pun -- lihat pemakaiannya di
  `ClientMonitoringController` dan `PageManager`.
- **Tipe kolom `jsonb`.** Migration tenant (`sections`, `section_types`,
  `packages`, `settings`) pakai `$table->jsonb(...)`. Laravel otomatis
  fallback ke tipe `JSON` biasa di MySQL grammar, jadi migration yang sama
  jalan di kedua driver tanpa perubahan.

### Checklist kalau menambah query/model central baru

1. Model central baru? Set `protected $connection = 'central';`, table name
   TANPA qualifier (`clients`, bukan `public.clients`).
2. Raw query ke tabel central dari dalam kode yang mungkin jalan saat tenant
   aktif (controller super admin, service scheduler)? Pakai
   `DB::connection('central')->table(...)`, jangan `DB::table(...)` polos.
3. Perlu `LIKE` case-insensitive? Pakai
   `TenantDatabaseManager::caseInsensitiveLikeOperator()`.
