<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('tagline')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('favicon_url')->nullable();
            $table->enum('active_template', ['corporate', 'creative', 'minimal'])->default('corporate');
            $table->string('primary_color')->nullable();
            $table->string('secondary_color')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->text('whatsapp_default_message')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('contact_address')->nullable();
            $table->text('maps_embed_url')->nullable();
            $table->string('operational_hours')->nullable();
            $table->string('ppiu_license')->nullable();
            $table->string('pihk_license')->nullable();
            $table->jsonb('social_links')->nullable();
            $table->string('footer_copyright')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
