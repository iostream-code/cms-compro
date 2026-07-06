<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Opsional -- hanya diperlukan kalau CACHE_STORE=database di .env.
 * Kalau pakai CACHE_STORE=file atau redis, migration ini tidak perlu dijalankan.
 * TenantResolver & TenantCacheHelper pakai facade Cache generik, jadi otomatis
 * ikut driver manapun yang aktif tanpa perlu ubah kode.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
    }
};
