<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('section_types', function (Blueprint $table) {
            $table->id();
            $table->string('type_key')->unique();
            $table->string('label');
            $table->text('description')->nullable();
            $table->jsonb('schema'); // blueprint field, dipakai form section (lihat catatan sprint: form hard-coded per tipe untuk sprint ini)
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('section_types');
    }
};
