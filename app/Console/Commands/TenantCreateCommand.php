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
        $schemaName = 'client_' . $subdomain;

        if (!preg_match('/^[a-z0-9-]{2,50}$/', $subdomain)) {
            $this->error('Subdomain hanya boleh huruf kecil, angka, dan dash.');
            return self::FAILURE;
        }

        if (Client::query()->where('subdomain', $subdomain)->exists()) {
            $this->error("Subdomain '{$subdomain}' sudah dipakai.");
            return self::FAILURE;
        }

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

        $this->info('Selesai. Client siap diakses di: ' . ($client->custom_domain ?: "{$subdomain}." . config('tenancy.base_domain')));

        return self::SUCCESS;
    }
}
