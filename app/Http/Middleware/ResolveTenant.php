<?php

namespace App\Http\Middleware;

use App\Exceptions\TenantInactiveException;
use App\Exceptions\TenantNotFoundException;
use App\Services\TenantContext;
use App\Services\TenantDatabaseManager;
use App\Services\TenantResolver;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * PENTING: middleware ini harus didaftarkan SEBELUM middleware auth
 * ('auth' / guard 'web') di bootstrap/app.php, karena tabel `users`
 * ada di schema tenant, bukan public. Guard 'web' (session-based)
 * tidak akan menemukan user yang benar kalau search_path belum
 * di-set ke schema client saat itu jalan.
 *
 * Guard 'super_admin' TIDAK terpengaruh middleware ini -- dia selalu
 * baca dari public.super_admins lewat qualifier eksplisit di model.
 */
class ResolveTenant
{
    public function __construct(
        private readonly TenantResolver $resolver,
        private readonly TenantDatabaseManager $db,
        private readonly TenantContext $context,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        try {
            $client = $this->resolver->resolveFromHost($request->getHost());
        } catch (TenantNotFoundException $e) {
            abort(404, $e->getMessage());
        } catch (TenantInactiveException $e) {
            abort(403, $e->getMessage());
        }

        $this->context->set($client);
        $this->db->useSchema($client->schema_name);

        // DEBUG SEMENTARA -- hapus setelah bug ketemu
        \Illuminate\Support\Facades\Log::info('ResolveTenant: search_path di-set', [
            'target_schema' => $client->schema_name,
            'actual_search_path' => \Illuminate\Support\Facades\DB::selectOne('SHOW search_path')->search_path ?? 'GAGAL BACA',
        ]);

        return $next($request);
    }
}
