<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Services\TenantDatabaseManager;
use Database\Seeders\DemoContentSeeder;
use Illuminate\Console\Command;
use Throwable;

class TenantSeedDemoCommand extends Command
{
    protected $signature = 'tenant:seed-demo
        {subdomain : Subdomain client yang mau diisi konten contoh}';

    protected $description = 'Isi 1 client dengan konten contoh (travel fiktif) supaya situsnya langsung tampil penuh';

    public function handle(TenantDatabaseManager $db): int
    {
        $client = Client::query()->where('subdomain', $this->argument('subdomain'))->first();

        if (!$client) {
            $this->error("Client dengan subdomain '{$this->argument('subdomain')}' tidak ditemukan.");

            return self::FAILURE;
        }

        $this->info("Mengisi konten contoh untuk '{$client->name}'...");

        $db->useSchema($client->schema_name);

        try {
            (new DemoContentSeeder())->run();
        } catch (Throwable $e) {
            $this->error("Gagal mengisi konten contoh: {$e->getMessage()}");
            report($e);

            return self::FAILURE;
        } finally {
            // Selalu reset walau gagal, supaya schema tenant ini tidak "bocor"
            // ke perintah berikutnya dalam proses yang sama.
            $db->resetToDefault();
        }

        $this->info('Selesai. Buka situs client untuk melihat hasilnya.');

        return self::SUCCESS;
    }
}
