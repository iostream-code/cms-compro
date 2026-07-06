<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('type', ['umroh', 'haji', 'wisata_religi']);
            $table->string('name');
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('duration')->nullable();
            $table->decimal('price_from', 12, 2)->nullable();
            $table->string('price_currency', 3)->default('IDR');
            $table->string('departure_city')->nullable();
            $table->string('airline')->nullable();
            $table->string('hotel_makkah')->nullable();
            $table->string('hotel_madinah')->nullable();
            $table->jsonb('facilities')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('is_published')->default(false);
            $table->integer('order')->default(0);
            $table->foreignUuid('created_by')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['type', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
