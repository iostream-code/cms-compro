<?php

return [
    // Default guard untuk request yang masuk lewat domain client (subdomain/custom domain)
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        // Admin & member CMS -- user-nya hidup di schema tenant aktif.
        // WAJIB: ResolveTenant middleware jalan duluan sebelum guard ini
        // dipakai, karena tabel 'users' cuma ada setelah search_path di-set.
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // Super admin -- selalu baca dari public.super_admins,
        // tidak terpengaruh search_path tenant sama sekali.
        'super_admin' => [
            'driver' => 'session',
            'provider' => 'super_admins',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'super_admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\SuperAdmin::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens', // ada di tiap schema tenant
            'expire' => 60,
            'throttle' => 60,
        ],
        'super_admins' => [
            'provider' => 'super_admins',
            'table' => 'public.password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
