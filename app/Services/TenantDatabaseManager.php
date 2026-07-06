<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TenantDatabaseManager
{
    /**
     * Validasi ketat nama schema. WAJIB dipanggil sebelum nama schema
     * di-interpolate ke raw SQL, karena `SET search_path` tidak bisa
     * pakai parameter binding di PostgreSQL.
     */
    public static function assertValidSchemaName(string $schema): void
    {
        if (!preg_match('/^[a-z][a-z0-9_]{2,62}$/', $schema)) {
            throw new InvalidArgumentException("Nama schema tidak valid: {$schema}");
        }
    }

    /**
     * Set search_path koneksi default ke schema tenant + public.
     * public tetap disertakan agar tabel central (mis. activity_log) tetap terjangkau.
     */
    public function useSchema(string $schema): void
    {
        self::assertValidSchemaName($schema);

        DB::statement("SET search_path TO \"{$schema}\", public");
    }

    /**
     * Kembalikan search_path ke default. Dipanggil setelah request selesai
     * (mis. di queue worker yang memproses banyak job lintas tenant dalam 1 proses)
     * supaya tidak ada state yang "bocor" ke job berikutnya.
     */
    public function resetToDefault(): void
    {
        DB::statement('SET search_path TO public');
    }

    public function schemaExists(string $schema): bool
    {
        self::assertValidSchemaName($schema);

        return DB::table('information_schema.schemata')
            ->where('schema_name', $schema)
            ->exists();
    }

    public function createSchemaIfMissing(string $schema): void
    {
        self::assertValidSchemaName($schema);

        DB::statement("CREATE SCHEMA IF NOT EXISTS \"{$schema}\"");
    }
}
