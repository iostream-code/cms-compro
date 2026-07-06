<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('jamaah_name');
            $table->string('jamaah_city')->nullable();
            $table->string('jamaah_photo_url')->nullable();
            $table->enum('package_type', ['umroh', 'haji', 'wisata_religi'])->nullable();
            $table->year('year')->nullable();
            $table->unsignedTinyInteger('rating')->default(5); // 1-5
            $table->text('content');
            $table->boolean('is_published')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index('is_published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
