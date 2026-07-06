<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientMonitoringController extends Controller
{
    /**
     * List semua client + statistik terbaru + status kesehatan.
     * Sort/filter dilakukan di schema public, murni dari tabel clients + client_stats.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Client::query()
            ->with('clientStat') // pastikan relasi hasOne didefinisikan di Client model
            ->when($request->filled('plan'), fn ($q) => $q->where('plan', $request->string('plan')))
            ->when($request->has('is_active'), fn ($q) => $q->where('is_active', $request->boolean('is_active')))
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = '%' . $request->string('search') . '%';
                $q->where(fn ($qq) => $qq->where('name', 'ilike', $term)
                    ->orWhere('subdomain', 'ilike', $term)
                    ->orWhere('custom_domain', 'ilike', $term));
            });

        $clients = $query->orderBy('name')->paginate($request->integer('per_page', 20));

        $clients->getCollection()->transform(function (Client $client) {
            return [
                'id' => $client->id,
                'name' => $client->name,
                'domain' => $client->custom_domain ?: "{$client->subdomain}." . config('tenancy.base_domain'),
                'plan' => $client->plan,
                'is_active' => $client->is_active,
                'stats' => $client->clientStat,
                'health' => $this->healthStatus($client),
            ];
        });

        return response()->json($clients);
    }

    /**
     * Feed aktivitas untuk 1 client spesifik (dipakai saat super admin klik "detail").
     */
    public function activity(Client $client, Request $request): JsonResponse
    {
        $activities = \DB::table('activity_log')
            ->where('client_id', $client->id)
            ->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 30));

        return response()->json($activities);
    }

    /**
     * Status kesehatan sederhana: nonaktif, tidak ada aktivitas lama,
     * atau statistik yang gagal ke-refresh (indikasi ada masalah di schema-nya).
     */
    private function healthStatus(Client $client): string
    {
        if (!$client->is_active) {
            return 'inactive';
        }

        $stat = $client->clientStat;

        if (!$stat || $stat->isStale()) {
            return 'stats_stale';
        }

        if ($stat->last_activity_at && $stat->last_activity_at->lt(now()->subDays(30))) {
            return 'dormant';
        }

        return 'healthy';
    }
}
