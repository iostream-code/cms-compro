<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Dibuat self-contained (bukan lewat `php artisan vendor:publish
 * --tag=activitylog-migrations`) supaya tidak ada dependency urutan
 * terhadap publish package Spatie -- penting untuk kecepatan setup di sprint ini.
 * Struktur kolom mengikuti skema resmi Spatie Laravel Activity Log v5.
 *
 * PENTING: subject & causer pakai nullableUuidMorphs(), BUKAN nullableMorphs()
 * bawaan Laravel -- karena Page/Package/Article (subject) dan User/SuperAdmin
 * (causer) semuanya pakai UUID sebagai primary key, bukan bigint auto-increment.
 * Kalau pakai nullableMorphs() biasa, subject_id/causer_id akan ke-generate
 * sebagai unsignedBigInteger dan gagal cocok dengan UUID.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_log', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->nullableUuidMorphs('subject', 'subject');
            $table->string('event')->nullable();
            $table->nullableUuidMorphs('causer', 'causer');
            $table->json('properties')->nullable();
            $table->json('attribute_changes')->nullable(); // v5: terpisah dari properties, simpan before/after
            $table->uuid('batch_uuid')->nullable();
            $table->timestamps();

            // Kolom tambahan khusus arsitektur multi-tenant kita:
            $table->foreignUuid('client_id')->nullable()->constrained('clients')->nullOnDelete();

            $table->index('log_name');
            $table->index(['client_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_log');
    }
};
