<?php

use App\Models\ActivityLog;

return [
    /*
     * Override model bawaan package supaya activity_log selalu diakses lewat
     * connection 'central', bukan connection default yang berpindah-pindah
     * mengikuti tenant aktif. Key lain yang tidak disebut di sini otomatis
     * jatuh ke default package (lihat vendor/spatie/laravel-activitylog/config/activitylog.php).
     */
    'activity_model' => ActivityLog::class,
];
