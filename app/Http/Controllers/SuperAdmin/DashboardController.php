<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientStat;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Semua endpoint di sini query dari schema public SAJA (clients, client_stats,
 * activity_log). Tidak pernah loop-switch schema tenant secara live -- itu
 * kerjaan TenantStatsService yang jalan di background lewat scheduler.
 */
class DashboardController extends Controller
{
    public function summary(): JsonResponse
    {
        $totalClients = Client::query()->count();
        $activeClients = Client::query()->where('is_active', true)->count();

        $aggregate = ClientStat::query()->selectRaw('
            COALESCE(SUM(total_users), 0) as total_users,
            COALESCE(SUM(active_users_7d), 0) as active_users_7d,
            COALESCE(SUM(total_pages), 0) as total_pages,
            COALESCE(SUM(total_packages), 0) as total_packages,
            COALESCE(SUM(total_articles), 0) as total_articles
        ')->first();

        $activityToday = DB::connection('central')->table('activity_log')
            ->whereDate('created_at', today())
            ->count();

        $staleStatsCount = ClientStat::query()
            ->where('stats_refreshed_at', '<', now()->subMinutes(30))
            ->orWhereNull('stats_refreshed_at')
            ->count();

        return response()->json([
            'clients' => [
                'total' => $totalClients,
                'active' => $activeClients,
                'inactive' => $totalClients - $activeClients,
            ],
            'content' => $aggregate,
            'activity_today' => $activityToday,
            'stale_stats_warning' => $staleStatsCount, // client yang statistiknya belum ke-refresh > 30 menit
        ]);
    }
}
