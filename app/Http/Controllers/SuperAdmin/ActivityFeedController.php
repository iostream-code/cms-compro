<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Feed real-time "apa yang sedang terjadi di semua client" -- dipakai untuk
 * halaman monitoring utama super admin. Query 100% ke public.activity_log,
 * di-join manual ke public.clients untuk dapat nama client (bukan ke users
 * tenant, karena causer_name/causer_email sudah di-snapshot di activity_log).
 */
class ActivityFeedController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = DB::table('activity_log')
            ->join('clients', 'clients.id', '=', 'activity_log.client_id')
            ->select([
                'activity_log.id',
                'activity_log.log_name',
                'activity_log.description',
                'activity_log.event',
                'activity_log.causer_name',
                'activity_log.causer_email',
                'activity_log.subject_type',
                'activity_log.subject_id',
                'activity_log.created_at',
                'clients.id as client_id',
                'clients.name as client_name',
            ])
            ->when($request->filled('client_id'), fn ($q) => $q->where('activity_log.client_id', $request->string('client_id')))
            ->when($request->filled('event'), fn ($q) => $q->where('activity_log.event', $request->string('event')))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('activity_log.created_at', '>=', $request->date('from')))
            ->orderByDesc('activity_log.created_at');

        return response()->json($query->paginate($request->integer('per_page', 50)));
    }
}
