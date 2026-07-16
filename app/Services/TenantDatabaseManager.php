<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

/**
 * Driver-aware: mendukung PostgreSQL (schema-per-tenant, via `search_path`)
 * dan MySQL/MariaDB (database-per-tenant, via `USE`). Driver aktif dibaca
 * dari DB_CONNECTION di .env -- ganti driver cukup ganti .env, tidak ada
 * kode lain yang perlu disentuh.
 */
class TenantDatabaseManager
{
    /**
     * Validasi ketat nama schema/database. WAJIB dipanggil sebelum nama
     * schema di-interpolate ke raw SQL, karena `SET search_path` / `USE`
     * tidak bisa pakai parameter binding.
     */
    public static function assertValidSchemaName(string $schema): void
    {
        if (!preg_match('/^[a-z][a-z0-9_]{2,62}$/', $schema)) {
            throw new InvalidArgumentException("Nama schema tidak valid: {$schema}");
        }
    }

    /**
     * Arahkan connection default ke schema/database tenant.
     * - PostgreSQL: `SET search_path` (public tetap disertakan agar tabel
     *   central yang belum dipindah ke connection 'central' tetap terjangkau).
     * - MySQL/MariaDB: `USE`, setara search_path tapi tanpa fallback --
     *   makanya tabel central WAJIB diakses lewat connection 'central'
     *   (lihat TenantServiceProvider), bukan qualifier string seperti di Postgres.
     */
    public function useSchema(string $schema): void
    {
        self::assertValidSchemaName($schema);

        match ($this->driver()) {
            'pgsql' => DB::statement("SET search_path TO \"{$schema}\", public"),
            'mysql', 'mariadb' => DB::statement("USE `{$schema}`"),
            default => $this->unsupportedDriver(),
        };
    }

    /**
     * Kembalikan connection default ke schema/database pusat. Dipanggil
     * setelah request selesai (mis. di queue worker yang memproses banyak
     * job lintas tenant dalam 1 proses) supaya tidak ada state yang "bocor"
     * ke job berikutnya.
     */
    public function resetToDefault(): void
    {
        match ($this->driver()) {
            'pgsql' => DB::statement('SET search_path TO public'),
            'mysql', 'mariadb' => DB::statement("USE `{$this->centralDatabaseName()}`"),
            default => $this->unsupportedDriver(),
        };
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

        match ($this->driver()) {
            'pgsql' => DB::statement("CREATE SCHEMA IF NOT EXISTS \"{$schema}\""),
            'mysql', 'mariadb' => DB::statement(
                "CREATE DATABASE IF NOT EXISTS `{$schema}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
            ),
            default => $this->unsupportedDriver(),
        };
    }

    /**
     * Hapus schema/database tenant. Dipakai sebagai compensating action saat
     * provisioning gagal di tengah jalan pada MySQL -- DDL (CREATE DATABASE/
     * CREATE TABLE) di MySQL auto-commit, TIDAK ikut rollback transaction
     * seperti di PostgreSQL, jadi harus dibersihkan manual (lihat
     * TenantMigrationService::provision()).
     */
    public function dropSchema(string $schema): void
    {
        self::assertValidSchemaName($schema);

        match ($this->driver()) {
            'pgsql' => DB::statement("DROP SCHEMA IF EXISTS \"{$schema}\" CASCADE"),
            'mysql', 'mariadb' => DB::statement("DROP DATABASE IF EXISTS `{$schema}`"),
            default => $this->unsupportedDriver(),
        };
    }

    /**
     * DDL (CREATE SCHEMA/DATABASE, CREATE TABLE) transactional di PostgreSQL
     * tapi auto-commit di MySQL. Dipakai TenantMigrationService untuk
     * memutuskan perlu tidaknya compensating cleanup manual saat provisioning
     * gagal di tengah jalan.
     */
    public function hasTransactionalDdl(): bool
    {
        return $this->driver() === 'pgsql';
    }

    /**
     * Operator LIKE case-insensitive: 'ilike' cuma ada di PostgreSQL, MySQL
     * 'like' sudah case-insensitive by default (collation utf8mb4_unicode_ci).
     */
    public static function caseInsensitiveLikeOperator(): string
    {
        return config('database.default') === 'pgsql' ? 'ilike' : 'like';
    }

    private function driver(): string
    {
        return config('database.default');
    }

    private function centralDatabaseName(): string
    {
        return config('database.connections.' . $this->driver() . '.database');
    }

    private function unsupportedDriver(): never
    {
        throw new RuntimeException("Driver database '{$this->driver()}' belum didukung untuk multi-tenancy.");
    }
}
