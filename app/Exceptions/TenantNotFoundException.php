<?php

namespace App\Exceptions;

use Exception;

/**
 * Dilempar saat host request tidak cocok dengan subdomain maupun
 * custom_domain client manapun di tabel public.clients.
 */
class TenantNotFoundException extends Exception
{
    protected $message = 'Client tidak ditemukan untuk domain ini.';
}
