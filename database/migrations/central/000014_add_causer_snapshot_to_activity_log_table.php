<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Prasyarat: migration 000003_create_activity_log_table sudah jalan.
 *
 * causer_name & causer_email disimpan sebagai SNAPSHOT saat activity dibuat.
 * Alasan: causer (User) hidup di schema tenant, sedangkan activity_log ada
 * di schema public. Kalau dashboard super admin butuh menampilkan "siapa yang
 * melakukan aksi ini" lintas banyak client sekaligus, relasi polymorphic
 * causer() tidak bisa diandalkan karena search_path cuma menunjuk ke 1 schema
 * dalam satu waktu. Snapshot ini membuat activity_log berdiri sendiri.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->string('causer_name')->nullable()->after('causer_id');
            $table->string('causer_email')->nullable()->after('causer_name');
        });
    }

    public function down(): void
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->dropColumn(['causer_name', 'causer_email']);
        });
    }
};
