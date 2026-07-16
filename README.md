# CMS Company Profile — Travel Haji & Umroh

CMS multi-client (1 schema/database terisolasi per client) untuk company
profile Travel Haji & Umroh. Satu aplikasi Laravel melayani banyak client,
masing-masing dengan data terisolasi penuh, diakses lewat subdomain atau
custom domain masing-masing.

---

## Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | Laravel 13, PHP 8.5+ |
| Frontend | Blade + Livewire 3 + Alpine.js (bawaan Livewire) |
| Database | PostgreSQL (schema-per-client) **atau** MySQL/MariaDB (database-per-client) -- pilih lewat `DB_CONNECTION` di `.env`, tidak ada kode yang perlu diubah |
| Auth | Session-based, 2 guard terpisah (`web` untuk client, `super_admin` untuk internal) |
| Activity Log | Spatie Laravel Activity Log v5 |
| Drag & drop | SortableJS |

**Kenapa Blade+Livewire, bukan React SPA?** Karena halaman visitor-facing
butuh SEO kuat (server-side rendering native), dan CMS dashboard-nya lebih
cepat dibangun lewat Livewire (server-driven, tidak perlu API layer
terpisah) dibanding Alpine.js + fetch API manual.

---

## Arsitektur Singkat

- **PostgreSQL**: 1 database, N schema — 1 schema per client (`client_{subdomain}`),
  isolasi lewat `search_path`.
- **MySQL/MariaDB**: N database — 1 database per client (`client_{subdomain}`),
  isolasi lewat `USE`.
- Domain request (`namaclient.yourcompany.com` atau custom domain) di-resolve
  ke schema/database yang tepat lewat `ResolveTenant` middleware.
- Data central (daftar client, super admin, activity log gabungan, statistik
  rollup) selalu diakses lewat connection `central` -- connection terpisah
  yang tidak pernah ikut berpindah tenant, jadi aman terlepas dari client
  mana yang sedang aktif, di kedua driver.
- Driver dipilih lewat `DB_CONNECTION` di `.env` (`pgsql` atau `mysql`/`mariadb`).
  Semua logika switching sudah driver-aware (lihat `TenantDatabaseManager`),
  jadi ganti driver cukup ganti `.env` + `php artisan migrate`.
- Detail lengkap arsitektur ada di [`README_TENANT_SETUP.md`](./README_TENANT_SETUP.md)

---

## Prasyarat

- PHP 8.5+
- Composer
- Node.js + npm
- PostgreSQL 14+ (`psql` CLI) **atau** MySQL 8+ / MariaDB 10.6+ (`mysql` CLI)

---

## Setup dari Nol

### 1. Install dependency

```bash
composer install
npm install
npm install sortablejs
```

Tambahkan di paling atas `resources/js/app.js` (belum ter-otomasi, harus manual):
```js
import Sortable from 'sortablejs';
window.Sortable = Sortable;
```

### 2. Konfigurasi environment

```bash
cp .env.example .env
php artisan key:generate
```

Set nilai berikut di `.env` (contoh PostgreSQL):
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=db_tenant
DB_USERNAME=postgres
DB_PASSWORD=your_password

CACHE_STORE=file
TENANCY_BASE_DOMAIN=yourcompany.com
```

Atau kalau pakai MySQL/MariaDB, cukup ganti blok `DB_*`-nya:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_tenant
DB_USERNAME=root
DB_PASSWORD=your_password
```
Tidak ada perubahan kode yang diperlukan untuk pindah driver -- lihat
[`README_TENANT_SETUP.md`](./README_TENANT_SETUP.md) bagian "Dukungan
Multi-Driver".

> **Penting:** `CACHE_STORE` harus `file` (bukan default `database`), kecuali
> Anda sudah menjalankan migration tambahan untuk tabel `cache`/`cache_locks`.

### 3. Build asset frontend

```bash
npm run build
```
Atau untuk development dengan hot-reload:
```bash
npm run dev
```

### 4. Migrate database

```bash
php artisan migrate
```
Ini hanya menjalankan migration **central** (schema `public`): `clients`,
`super_admins`, `activity_log`, `client_stats`. Migration **tenant** (users,
pages, sections, dll — di `database/migrations/tenant/`) TIDAK ikut jalan di
sini secara sengaja — itu baru dijalankan otomatis saat provisioning client
baru di langkah berikutnya.

### 5. Buat client pertama

```bash
php artisan tenant:create "Nama Perusahaan" subdomain-nya
```

Command ini otomatis: bikin schema (PostgreSQL) atau database (MySQL/MariaDB)
baru sesuai `DB_CONNECTION` aktif, jalankan 11 migration tenant di dalamnya,
dan seed 12 tipe section (`SectionTypeSeeder`).

Opsional, custom domain:
```bash
php artisan tenant:create "Nama Perusahaan" subdomain-nya --custom-domain=domainclient.com
```

### 6. Setup akses domain lokal

Tambahkan ke `/etc/hosts` (perlu `sudo`):
```
127.0.0.1   subdomain-nya.yourcompany.com
```
`yourcompany.com` di sini harus sama persis dengan `TENANCY_BASE_DOMAIN` di `.env`.

### 7. Jalankan server

```bash
php artisan serve
```

### 8. Buat user admin pertama untuk client tadi

```bash
php artisan tinker --execute="
app(\App\Services\TenantDatabaseManager::class)->useSchema('client_subdomain-nya');
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@test.com',
    'password' => bcrypt('password'),
    'role' => 'admin',
]);
"
```

### 9. Akses

- CMS login: `http://subdomain-nya.yourcompany.com:8000/login`
- Super admin login: `http://subdomain-nya.yourcompany.com:8000/superadmin/login`
  (perlu bikin row di tabel `public.super_admins` dulu — belum ada seeder-nya)

---

## Perintah Penting Sehari-hari

```bash
# Bikin client baru
php artisan tenant:create "Nama" subdomain

# Migrate migration baru ke 1 client existing
php artisan tenant:migrate subdomain

# Migrate migration baru ke SEMUA client aktif sekaligus
php artisan tenant:migrate --all

# Refresh statistik dashboard super admin (biasanya dijadwalkan tiap 15 menit)
php artisan tenant:refresh-stats --all

# Bersihkan semua cache (route, config, view, dll) -- pakai ini kalau ada
# perubahan yang "tidak nyambung" padahal kodenya sudah benar
php artisan optimize:clear
```

---

## Struktur Folder yang Perlu Diketahui

```
app/
├── Console/Commands/       # tenant:create, tenant:migrate, tenant:refresh-stats
├── Http/
│   ├── Controllers/
│   │   ├── Auth/           # Login CMS & super admin
│   │   ├── Cms/            # Controller CRUD konten (sebagian masih stub)
│   │   ├── Public/         # Controller visitor-facing
│   │   └── SuperAdmin/     # API monitoring dashboard
│   └── Middleware/
│       ├── ResolveTenant.php   # Inti sistem multi-tenant
│       └── EnsureRole.php
├── Livewire/Cms/           # Komponen Livewire (PageManager, SectionManager)
├── Models/                 # Client & ClientStat = schema public, sisanya = schema tenant
├── Services/                # TenantResolver, TenantDatabaseManager, TenantMigrationService, dst
└── Traits/LogsTenantActivity.php

database/migrations/
├── central/                # Schema public -- di-load via TenantServiceProvider
└── tenant/                 # Schema per-client -- HANYA dijalankan via TenantMigrationService,
                             # JANGAN PERNAH lewat `php artisan migrate` biasa

resources/views/
├── components/
│   ├── layouts/app.blade.php    # Layout default untuk Livewire full-page component
│   ├── section/                  # 12 komponen render section (masih shell/placeholder styling)
│   └── include/section-renderer.blade.php
├── livewire/cms/
└── public/                 # View visitor-facing
```

---

## Dokumentasi Lain di Repo Ini

| File | Isinya |
|---|---|
| [`HANDOFF.md`](./HANDOFF.md) | **Baca ini dulu** — known issues/gotchas dari proses development, status terkini per fase |
| [`README_TENANT_SETUP.md`](./README_TENANT_SETUP.md) | Detail teknis arsitektur multi-tenant, registrasi middleware & provider |
| [`SPRINT_PLAN.md`](./SPRINT_PLAN.md) | Breakdown kerja harian untuk sprint saat ini |
| [`ROADMAP.md`](./ROADMAP.md) | Roadmap penuh (skenario timeline longgar) |

---

## Known Issues Singkat

Package `spatie/laravel-activitylog` yang ter-install (v5, rilis Maret 2026)
punya beberapa breaking changes dari versi yang lazim didokumentasikan di
internet (namespace `LogsActivity`/`LogOptions` pindah lokasi, method
`tapActivity()` jadi `beforeActivityLogged()`, dst). Kalau develop fitur baru
yang menyentuh activity log, cek langsung ke
`vendor/spatie/laravel-activitylog/src/` untuk API yang benar — jangan
percaya cuma dari dokumentasi/tutorial lama. Detail lengkap semua known
issues ada di [`HANDOFF.md`](./HANDOFF.md).
