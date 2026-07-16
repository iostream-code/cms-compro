<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientStat;
use Illuminate\Support\Facades\DB;
use Throwable;

class TenantStatsService
{
    public function __construct(
        private readonly TenantDatabaseManager $db,
    ) {
    }

    /**
     * Hitung ulang statistik untuk 1 client: masuk ke schema-nya,
     * COUNT() beberapa tabel, lalu simpan hasilnya ke public.client_stats.
     */
    public function refresh(Client $client): void
    {
        $this->db->useSchema($client->schema_name);

        try {
            $stats = [
                'total_users' => DB::table('users')->count(),
                'active_users_7d' => DB::table('users')
                    ->where('last_login_at', '>=', now()->subDays(7))
                    ->count(),
                'total_pages' => DB::table('pages')->whereNull('deleted_at')->count(),
                'total_packages' => DB::table('packages')->whereNull('deleted_at')->count(),
                'total_articles' => DB::table('articles')->whereNull('deleted_at')->count(),
                'last_login_at' => DB::table('users')->max('last_login_at'),
            ];
        } finally {
            // WAJIB reset walau query di atas gagal, supaya schema tenant
            // ini tidak "bocor" ke iterasi client berikutnya di refreshAll().
            $this->db->resetToDefault();
        }

        // last_activity_at diambil dari activity_log (connection central),
        // jadi query-nya di luar blok search_path/USE tenant di atas.
        $lastActivityAt = DB::connection('central')->table('activity_log')
            ->where('client_id', $client->id)
            ->max('created_at');

        ClientStat::query()->updateOrCreate(
            ['client_id' => $client->id],
            $stats + [
                'last_activity_at' => $lastActivityAt,
                'stats_refreshed_at' => now(),
            ]
        );
    }

    /**
     * Refresh semua client aktif. Dipanggil dari scheduler (tiap 15 menit).
     * Kegagalan pada 1 client tidak menghentikan client lain.
     *
     * @return array<string, bool>
     */
    public function refreshAll(): array
    {
        $results = [];

        Client::query()->where('is_active', true)->each(function (Client $client) use (&$results) {
            try {
                $this->refresh($client);
                $results[$client->schema_name] = true;
            } catch (Throwable $e) {
                $results[$client->schema_name] = false;
                report($e);
                // pastikan search_path tidak nyangkut di schema yang error
                $this->db->resetToDefault();
            }
        });

        return $results;
    }
}
