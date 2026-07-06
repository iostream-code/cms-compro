<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel rollup -- diisi ulang secara berkala oleh TenantStatsService, BUKAN
 * di-query real-time lintas schema (mahal & tidak mungkin dalam 1 koneksi).
 * client_id sekaligus jadi primary key karena relasinya 1-1 dengan clients.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_stats', function (Blueprint $table) {
            $table->foreignUuid('client_id')
                ->primary()
                ->constrained('clients')
                ->cascadeOnDelete();

            $table->unsignedInteger('total_users')->default(0);
            $table->unsignedInteger('active_users_7d')->default(0);
            $table->unsignedInteger('total_pages')->default(0);
            $table->unsignedInteger('total_packages')->default(0);
            $table->unsignedInteger('total_articles')->default(0);

            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamp('stats_refreshed_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_stats');
    }
};
