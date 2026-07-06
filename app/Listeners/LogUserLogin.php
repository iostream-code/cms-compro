<?php

namespace App\Listeners;

use App\Services\TenantContext;
use Illuminate\Auth\Events\Login;
use Spatie\Activitylog\Models\Activity;

/**
 * Login tidak lewat Eloquent save(), jadi tidak ke-cover oleh LogsTenantActivity.
 * Didaftarkan di AppServiceProvider (Laravel 11+) untuk event Login pada
 * guard 'web' -- bukan guard 'super_admin', yang loginnya tidak perlu masuk
 * ke activity_log per-client karena bukan aktivitas milik 1 client tertentu.
 *
 * Ini juga titik paling pas untuk update users.last_login_at, supaya
 * "active_users_7d" di client_stats punya sumber data yang akurat.
 *
 * Catatan versi: pakai helper global activity() (bukan facade), dan ->tap()
 * untuk isi kolom custom SEBELUM disimpan -- ini pola resmi v5 untuk
 * manual logging (beda dari beforeActivityLogged() yang khusus otomatis
 * lewat trait LogsActivity di model events).
 */
class LogUserLogin
{
    public function handle(Login $event): void
    {
        $user = $event->user;
        $user->forceFill(['last_login_at' => now()])->saveQuietly();

        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->event('login')
            ->tap(function (Activity $activity) use ($user) {
                if (app(TenantContext::class)->check()) {
                    $activity->client_id = app(TenantContext::class)->get()->id;
                }

                $activity->causer_name = $user->name;
                $activity->causer_email = $user->email;
            })
            ->log('User login');
    }
}
