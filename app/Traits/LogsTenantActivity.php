<?php

namespace App\Traits;

use App\Services\TenantContext;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Models\Activity;

/**
 * Pasang trait ini (bukan LogsActivity langsung) di semua model tenant yang
 * histori-nya perlu tampil di dashboard super admin: Page, Package, Article.
 *
 * Kenapa perlu wrapper: LogsActivity bawaan Spatie tidak tahu apa-apa soal
 * konsep "tenant". Tap ini yang mengisi client_id + snapshot causer supaya
 * activity_log tetap bisa dibaca lengkap walau search_path sudah pindah
 * ke client lain saat dashboard super admin query-nya.
 *
 * Catatan versi: pakai spatie/laravel-activitylog v5+. Namespace LogsActivity
 * & LogOptions pindah lokasi di v5, dan method tapActivity() di-rename jadi
 * beforeActivityLogged() -- lihat UPGRADING.md resmi package ini kalau nanti
 * ada perubahan serupa lagi di versi berikutnya.
 */
trait LogsTenantActivity
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function beforeActivityLogged(Activity $activity, string $eventName): void
    {
        if (app(TenantContext::class)->check()) {
            $activity->client_id = app(TenantContext::class)->get()->id;
        }

        if ($user = Auth::user()) {
            $activity->causer_name = $user->name;
            $activity->causer_email = $user->email;
        }
    }
}
