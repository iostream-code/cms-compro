<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity;

/**
 * Override activity_model bawaan Spatie (lihat config/activitylog.php) supaya
 * activity_log selalu diakses lewat connection 'central' -- kalau tetap pakai
 * connection default, insert/query-nya akan salah sasaran begitu ada tenant
 * aktif (schema/database sedang dialihkan lewat TenantDatabaseManager).
 */
class ActivityLog extends Activity
{
    protected $connection = 'central';
}
