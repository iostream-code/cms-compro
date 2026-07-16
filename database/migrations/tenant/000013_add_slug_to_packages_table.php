<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // Backfill slug untuk row yang mungkin sudah ada sebelum kolom ini ditambahkan.
        DB::table('packages')->whereNull('slug')->orderBy('id')->get(['id', 'name'])->each(function ($package) {
            DB::table('packages')->where('id', $package->id)->update([
                'slug' => Str::slug($package->name) . '-' . Str::substr($package->id, 0, 8),
            ]);
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
