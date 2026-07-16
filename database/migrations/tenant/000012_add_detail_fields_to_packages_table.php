<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Field tambahan hasil riset struktur company profile travel umrah/haji
 * sungguhan (tomboatitour.net) -- lihat percakapan/README untuk detail.
 * Sengaja migration baru (bukan edit 000008) karena 000008 sudah jalan di
 * client existing.
 *
 * Scope: CMS informasional (visitor lihat detail, booking manual lewat
 * WhatsApp/telepon ke admin) -- BUKAN sistem booking/order transaksional.
 * Karena itu seats_total/seats_available di sini murni informasi tampilan
 * ("Sisa Seat: 88"), bukan inventory yang di-lock saat ada
 * pemesanan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            // Tanggal keberangkatan -- nullable + note karena banyak paket
            // haji tampil "Tanggal Belum Tersedia" / "Estimasi Tahun 2034"
            // (belum ada tanggal pasti, tapi tetap perlu tampil ke visitor).
            $table->date('departure_date')->nullable()->after('departure_city');
            $table->string('departure_date_note')->nullable()->after('departure_date');

            // Beda dengan departure_city (kota) -- ini nama bandara spesifik,
            // mis. "Juanda International Airport (SUB)".
            $table->string('departure_airport')->nullable()->after('departure_date_note');

            $table->unsignedInteger('seats_total')->nullable()->after('departure_airport');
            $table->unsignedInteger('seats_available')->nullable()->after('seats_total');

            // Itinerary harian: [{day, title, description}, ...]
            $table->jsonb('itinerary')->nullable()->after('facilities');

            // Breakdown harga per tipe kamar: [{label, price}, ...],
            // mis. [{"label": "Quad (1 Kamar ber-4)", "price": 33300000}].
            // price_from (kolom lama) tetap dipakai sebagai harga "mulai
            // dari" buat card preview -- room_types buat halaman detail.
            $table->jsonb('room_types')->nullable()->after('itinerary');

            $table->text('requirements')->nullable()->after('room_types');
            $table->text('terms_conditions')->nullable()->after('requirements');
            $table->string('brochure_url')->nullable()->after('terms_conditions');
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'departure_date',
                'departure_date_note',
                'departure_airport',
                'seats_total',
                'seats_available',
                'itinerary',
                'room_types',
                'requirements',
                'terms_conditions',
                'brochure_url',
            ]);
        });
    }
};
