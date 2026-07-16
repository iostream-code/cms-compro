<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Services\TenantMigrationService;
use Illuminate\Console\Command;

class TenantCreateCommand extends Command
{
    protected $signature = 'tenant:create
        {name : Nama perusahaan client}
        {subdomain : Subdomain, mis. "azzahra"}
        {--custom-domain= : Custom domain opsional, mis. "azzahrawisata.com"}
        {--plan=basic}';

    protected $description = 'Buat client baru: insert row + provisioning schema PostgreSQL + migrate + seed';

    public function handle(TenantMigrationService $migrationService): int
    {
        $subdomain = strtolower($this->argument('subdomain'));

        if (!preg_match('/^[a-z0-9-]{2,50}$/', $subdomain)) {
            $this->error('Subdomain hanya boleh huruf kecil, angka, dan dash.');
            return self::FAILURE;
        }

        // Subdomain boleh mengandung dash (dipakai di URL), tapi nama schema
        // PostgreSQL kita batasi cuma huruf kecil, angka, underscore (lihat
        // TenantDatabaseManager::assertValidSchemaName) -- jadi dash di sini
        // WAJIB dikonversi ke underscore sebelum jadi nama schema.
        $schemaName = 'client_' . str_replace('-', '_', $subdomain);

        if (Client::query()->where('subdomain', $subdomain)->exists()) {
            $this->error("Subdomain '{$subdomain}' sudah dipakai.");
            return self::FAILURE;
        }

        // Cegah tabrakan schema_name antar subdomain berbeda yang setelah
        // konversi dash->underscore jadi identik (mis. "haji-barokah" vs
        // "haji_barokah") -- dua subdomain itu beda tapi akan berebut 1 schema
        // yang sama kalau tidak dicek eksplisit di sini.
        if (Client::query()->where('schema_name', $schemaName)->exists()) {
            $this->error("Nama schema '{$schemaName}' hasil konversi dari subdomain ini sudah dipakai client lain (kemungkinan subdomain dengan dash/underscore yang mirip). Pilih subdomain lain.");
            return self::FAILURE;
        }

        // Bungkus insert row client + provisioning schema dalam SATU transaction
        // di connection 'central' (sama dengan connection model Client).
        //
        // Di PostgreSQL, DDL transactional -- CREATE SCHEMA + migrate + seed
        // di dalam provision() (yang sudah punya transaction sendiri) otomatis
        // jadi nested transaction/savepoint di sini, jadi kalau gagal di
        // tengah jalan, row client YANG BARU DIBUAT ikut di-rollback juga:
        // tidak ada client "zombie" (row ada di clients tapi schema-nya
        // setengah jadi / tidak ada sama sekali).
        //
        // Di MySQL, DDL auto-commit dan TIDAK ikut rollback transaction ini --
        // row client tetap ter-rollback seperti biasa, tapi database tenant-nya
        // dibersihkan manual lewat compensating action di
        // TenantMigrationService::provision() (lihat TenantDatabaseManager::dropSchema()).
        try {
            $client = \Illuminate\Support\Facades\DB::connection('central')->transaction(function () use ($migrationService, $subdomain, $schemaName) {
                $client = Client::query()->create([
                    'name' => $this->argument('name'),
                    'subdomain' => $subdomain,
                    'custom_domain' => $this->option('custom-domain'),
                    'domain_type' => $this->option('custom-domain') ? 'custom' : 'subdomain',
                    'schema_name' => $schemaName,
                    'is_active' => true,
                    'plan' => $this->option('plan'),
                ]);

                $this->info("Client '{$client->name}' dibuat. Menjalankan provisioning schema '{$schemaName}'...");

                $migrationService->provision($client);

                return $client;
            });
        } catch (\Throwable $e) {
            $this->error("Gagal provisioning client -- semua perubahan (termasuk row client) sudah di-rollback. Detail: {$e->getMessage()}");
            report($e);

            return self::FAILURE;
        }

        $this->info('Selesai. Client siap diakses di: ' . ($client->custom_domain ?: "{$subdomain}." . config('tenancy.base_domain')));

        return self::SUCCESS;
    }
}
