<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Services\TenantStatsService;
use Illuminate\Console\Command;

class TenantRefreshStatsCommand extends Command
{
    protected $signature = 'tenant:refresh-stats {subdomain? : Refresh 1 client saja} {--all : Refresh semua client aktif}';

    protected $description = 'Hitung ulang client_stats (total user, konten, aktivitas terakhir) untuk dashboard super admin';

    public function handle(TenantStatsService $statsService): int
    {
        if ($this->option('all') || !$this->argument('subdomain')) {
            $results = $statsService->refreshAll();

            foreach ($results as $schema => $success) {
                $success ? $this->info("  ✓ {$schema}") : $this->error("  ✗ {$schema}");
            }

            return self::SUCCESS;
        }

        $client = Client::query()->where('subdomain', $this->argument('subdomain'))->first();
        if (!$client) {
            $this->error('Client tidak ditemukan.');
            return self::FAILURE;
        }

        $statsService->refresh($client);
        $this->info("Statistik '{$client->name}' diperbarui.");

        return self::SUCCESS;
    }
}
