<?php

namespace App\Exceptions;

use Exception;

/**
 * Dilempar saat client ditemukan tapi is_active = false
 * (misal langganan nonaktif / suspend).
 */
class TenantInactiveException extends Exception
{
    protected $message = 'Client ditemukan namun sedang nonaktif.';
}
