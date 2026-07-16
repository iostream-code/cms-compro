<?php

namespace App\Services;

use App\Models\Client;
use Database\Seeders\SectionTypeSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Throwable;

class TenantMigrationService
{
    public function __construct(
        private readonly TenantDatabaseManager $db,
    ) {
    }

    /**
     * Jalankan full provisioning untuk 1 client: buat schema, migrate semua
     * tabel tenant, lalu seed section_types. Dipanggil saat client baru dibuat.
     *
     * @throws Throwable
     */
    public function provision(Client $client): void
    {
        TenantDatabaseManager::assertValidSchemaName($client->schema_name);

        $run = function () use ($client) {
            $this->db->createSchemaIfMissing($client->schema_name);
            $this->migrate($client);
            $this->seed($client);
        };

        try {
            if ($this->db->hasTransactionalDdl()) {
                // PostgreSQL: DDL transactional, jadi CREATE SCHEMA + migrate
                // + seed benar-benar atomic dalam 1 transaction.
                DB::transaction($run);
            } else {
                // MySQL: DDL (CREATE DATABASE/CREATE TABLE) auto-commit --
                // membungkusnya dalam DB::transaction() tidak memberi atomicity
                // apa pun, malah bikin PDO error "There is no active
                // transaction" begitu statement DDL pertama jalan (transaction
                // implicitly ke-commit tapi Laravel tidak tahu). Jalankan
                // langsung, andalkan dropSchema() di bawah sebagai
                // compensating action kalau gagal di tengah jalan.
                $run();
            }
        } catch (Throwable $e) {
            // Row client (connection 'central') tetap di-rollback oleh
            // transaction pembungkus di TenantCreateCommand terlepas dari
            // ini -- itu connection terpisah. Di sini kita cuma perlu
            // bersihkan schema/database tenant supaya tidak ada yang
            // "zombie" (setengah jadi) kalau drivernya non-transactional DDL.
            if (!$this->db->hasTransactionalDdl()) {
                $this->db->dropSchema($client->schema_name);
            }

            throw $e;
        }
    }

    /**
     * Jalankan migration tenant untuk 1 client. Dipakai baik saat provisioning
     * awal maupun saat ada migration baru yang perlu di-rollout ke client existing.
     */
    public function migrate(Client $client): void
    {
        $this->db->useSchema($client->schema_name);

        Artisan::call('migrate', [
            '--path' => 'database/migrations/tenant',
            '--realpath' => false,
            '--force' => true,
            // schema tujuan sudah ditentukan lewat search_path di atas,
            // bukan lewat opsi --database, supaya tetap 1 connection pool
        ]);

        $this->db->resetToDefault();
    }

    /**
     * Migrate semua client aktif -- dipakai saat ada migration baru
     * yang perlu di-deploy ke seluruh client sekaligus.
     *
     * @return array<string, bool> map schema_name => sukses/tidak
     */
    public function migrateAll(): array
    {
        $results = [];

        Client::query()->where('is_active', true)->each(function (Client $client) use (&$results) {
            try {
                $this->migrate($client);
                $results[$client->schema_name] = true;
            } catch (Throwable $e) {
                $results[$client->schema_name] = false;
                report($e);
            }
        });

        return $results;
    }

    public function seed(Client $client): void
    {
        $this->db->useSchema($client->schema_name);

        // Dipanggil langsung (bukan Artisan::call('db:seed')) supaya tetap
        // dalam 1 request lifecycle & 1 transaction dengan migrate().
        (new SectionTypeSeeder())->run();

        $this->db->resetToDefault();
    }
}
