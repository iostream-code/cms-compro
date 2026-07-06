<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Services\TenantMigrationService;
use Illuminate\Console\Command;

class TenantMigrateCommand extends Command
{
    protected $signature = 'tenant:migrate
        {subdomain? : Migrate 1 client saja berdasarkan subdomain}
        {--all : Migrate semua client aktif}';

    protected $description = 'Jalankan migration tenant terbaru ke 1 client atau semua client aktif';

    public function handle(TenantMigrationService $migrationService): int
    {
        if ($this->option('all')) {
            $this->info('Migrating semua client aktif...');
            $results = $migrationService->migrateAll();

            foreach ($results as $schema => $success) {
                $success
                    ? $this->info("  ✓ {$schema}")
                    : $this->error("  ✗ {$schema} (lihat log untuk detail)");
            }

            return self::SUCCESS;
        }

        $subdomain = $this->argument('subdomain');
        if (!$subdomain) {
            $this->error('Sebutkan subdomain client, atau pakai --all.');
            return self::FAILURE;
        }

        $client = Client::query()->where('subdomain', $subdomain)->first();
        if (!$client) {
            $this->error("Client dengan subdomain '{$subdomain}' tidak ditemukan.");
            return self::FAILURE;
        }

        $migrationService->migrate($client);
        $this->info("Migration selesai untuk '{$client->name}'.");

        return self::SUCCESS;
    }
}
