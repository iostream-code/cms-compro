<?php

return [
    // Domain dasar untuk subdomain client, mis. "azzahra.yourcompany.com"
    'base_domain' => env('TENANCY_BASE_DOMAIN', 'yourcompany.com'),

    // Subdomain yang tidak boleh dipakai client (bentrok dengan sistem)
    'reserved_subdomains' => [
        'www',
        'app',
        'api',
        'admin',
        'superadmin',
        'mail',
        'ftp',
        'cdn',
        'static',
    ],

    // Path migration khusus tenant (terpisah dari database/migrations pusat)
    'tenant_migrations_path' => database_path('migrations/tenant'),
];
