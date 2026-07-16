<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Package;
use App\Models\Page;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Isi contoh untuk tenant baru: identitas travel FIKTIF + foto stok Unsplash.
 * Tujuannya supaya begitu client di-provision, situsnya langsung tampil penuh
 * (bukan halaman kosong), lalu admin tinggal ganti isinya lewat CMS.
 *
 * Dijalankan lewat: php artisan tenant:seed-demo {subdomain}
 * SENGAJA tidak ikut TenantMigrationService::provision() supaya client
 * produksi tidak otomatis kebawa data contoh.
 *
 * Semua nama, nomor izin, alamat, dan kontak di sini karangan -- jangan
 * diganti dengan data travel asli milik orang lain.
 */
class DemoContentSeeder extends Seeder
{
    private const COMPANY = 'Barokah Mulia Tour & Travel';

    public function run(): void
    {
        $this->seedSettings();
        $this->seedPackages();
        $this->seedTestimonials();
        $this->seedArticles();
        $this->seedHomePage();
    }

    private function seedSettings(): void
    {
        Setting::current()->update([
            'company_name' => self::COMPANY,
            'tagline' => 'Menuju Baitullah dengan Tenang dan Amanah',
            'active_template' => 'corporate',
            'primary_color' => '#C8952B',
            'secondary_color' => '#0E3B35',
            'contact_email' => 'halo@barokahmulia.example.id',
            'contact_phone' => '0812-0000-1111',
            'whatsapp_number' => '6281200001111',
            'whatsapp_default_message' => 'Assalamualaikum, saya ingin bertanya tentang paket umrah.',
            'contact_address' => 'Jl. Contoh Raya No. 123, Kota Malang, Jawa Timur 65100',
            // Format `?q=...&output=embed` tidak butuh API key -- cocok untuk
            // data contoh. Tenant sungguhan tinggal menempel URL embed miliknya
            // sendiri lewat Pengaturan.
            'maps_embed_url' => 'https://www.google.com/maps?q=Kota+Malang,+Jawa+Timur&output=embed',
            'operational_hours' => "Senin - Sabtu, 09.00 - 17.00\nMinggu & Tanggal Merah: Libur",
            'ppiu_license' => 'U.000 TAHUN 2024 (contoh)',
            'pihk_license' => 'D.000 TAHUN 2024 (contoh)',
            'footer_copyright' => '© ' . date('Y') . ' ' . self::COMPANY . '. Seluruh hak cipta dilindungi.',
            'social_links' => [
                ['platform' => 'Instagram', 'url' => 'https://instagram.com/'],
                ['platform' => 'Facebook', 'url' => 'https://facebook.com/'],
                ['platform' => 'YouTube', 'url' => 'https://youtube.com/'],
            ],
        ]);
    }

    private function seedPackages(): void
    {
        foreach ($this->packageDefinitions() as $definition) {
            Package::query()->updateOrCreate(
                ['slug' => Str::slug($definition['name'])],
                $definition + ['is_published' => true],
            );
        }
    }

    private function packageDefinitions(): array
    {
        $standardFacilities = [
            'Tiket pesawat PP', 'Visa umrah', 'Hotel bintang 4/5', 'Konsumsi full board',
            'Bus AC selama perjalanan', 'Muthawif berpengalaman', 'Perlengkapan umrah', 'Air zamzam',
        ];

        return [
            [
                'type' => 'umroh',
                'name' => 'Umrah Hemat 12 Hari - Oktober 2026',
                'short_description' => 'Paket umrah ekonomis dengan pembimbing berpengalaman.',
                'description' => "Paket umrah 12 hari dengan harga terjangkau tanpa mengurangi kenyamanan ibadah. Cocok untuk jamaah yang berangkat pertama kali maupun yang sudah berpengalaman.\n\nDidampingi muthawif bersertifikat dari keberangkatan hingga kembali ke tanah air.",
                'duration' => 'Umrah 12 Hari',
                'price_from' => 27500000,
                'departure_city' => 'Surabaya',
                'departure_airport' => 'Bandara Internasional Juanda (SUB)',
                'departure_date' => '2026-10-12',
                'seats_total' => 45,
                'seats_available' => 18,
                'airline' => 'Garuda Indonesia',
                'hotel_makkah' => 'Grand Al Massa',
                'hotel_madinah' => 'Golden Tulip Al Zahabi',
                'image_url' => 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?w=1200&q=80',
                'order' => 1,
                'facilities' => $standardFacilities,
                'room_types' => [
                    ['label' => 'Quad (1 kamar berempat)', 'price' => 27500000],
                    ['label' => 'Triple (1 kamar bertiga)', 'price' => 29500000],
                    ['label' => 'Double (1 kamar berdua)', 'price' => 32500000],
                ],
                'itinerary' => [
                    ['day' => 1, 'title' => 'Keberangkatan dari Surabaya', 'description' => 'Berkumpul di Bandara Juanda, manasik singkat, lalu terbang menuju Jeddah.'],
                    ['day' => 2, 'title' => 'Tiba di Madinah', 'description' => 'Tiba di Jeddah, perjalanan darat ke Madinah, check-in hotel dan istirahat.'],
                    ['day' => 3, 'title' => 'Ziarah Kota Madinah', 'description' => 'Ziarah Masjid Quba, Jabal Uhud, dan Kebun Kurma. Shalat arbain di Masjid Nabawi.'],
                    ['day' => 4, 'title' => 'Ibadah di Masjid Nabawi', 'description' => 'Memperbanyak ibadah dan ziarah Raudhah sesuai jadwal tasreh.'],
                    ['day' => 5, 'title' => 'Perjalanan ke Makkah', 'description' => 'Miqat di Bir Ali, mengenakan ihram, lanjut perjalanan ke Makkah.'],
                    ['day' => 6, 'title' => 'Umrah Pertama', 'description' => 'Melaksanakan tawaf, sai, dan tahallul didampingi muthawif.'],
                    ['day' => 7, 'title' => 'Ziarah Kota Makkah', 'description' => 'Ziarah Jabal Rahmah, Arafah, Muzdalifah, Mina, dan Jabal Tsur.'],
                    ['day' => 8, 'title' => 'Umrah Kedua', 'description' => 'Miqat di Ji\'ranah untuk pelaksanaan umrah kedua.'],
                    ['day' => 9, 'title' => 'Ibadah Mandiri', 'description' => 'Waktu bebas memperbanyak tawaf sunnah dan ibadah di Masjidil Haram.'],
                    ['day' => 10, 'title' => 'Tawaf Wada', 'description' => 'Melaksanakan tawaf perpisahan, persiapan kembali ke tanah air.'],
                    ['day' => 11, 'title' => 'Perjalanan Pulang', 'description' => 'Perjalanan darat ke Jeddah, city tour singkat, lalu terbang ke Indonesia.'],
                    ['day' => 12, 'title' => 'Tiba di Tanah Air', 'description' => 'Tiba kembali di Bandara Juanda Surabaya dengan selamat.'],
                ],
                'requirements' => "1. Paspor berlaku minimal 8 bulan (nama minimal 3 suku kata)\n2. Kartu vaksin meningitis\n3. Pas foto berwarna 4x6 latar putih (tampak wajah 80%)\n4. Fotokopi KTP dan Kartu Keluarga\n5. Buku nikah bagi pasangan suami istri",
                'terms_conditions' => "1. Uang muka minimal Rp 5.000.000 untuk pendaftaran\n2. Pelunasan paling lambat 30 hari sebelum keberangkatan\n3. Harga dapat berubah mengikuti kurs dan kebijakan maskapai\n4. Pembatalan dikenakan biaya administrasi sesuai ketentuan",
            ],
            [
                'type' => 'umroh',
                'name' => 'Umrah Plus Turki 16 Hari - Agustus 2026',
                'short_description' => 'Umrah lengkap dengan wisata sejarah Islam di Turki.',
                'description' => "Menggabungkan ibadah umrah dengan perjalanan menyusuri jejak peradaban Islam di Turki. Mengunjungi Istanbul, Bursa, dan situs bersejarah lainnya.",
                'duration' => 'Umrah Plus 16 Hari',
                'price_from' => 38900000,
                'departure_city' => 'Jakarta',
                'departure_airport' => 'Bandara Soekarno-Hatta (CGK)',
                'departure_date' => '2026-08-29',
                'seats_total' => 40,
                'seats_available' => 7,
                'airline' => 'Turkish Airlines',
                'hotel_makkah' => 'Swissotel Al Maqam',
                'hotel_madinah' => 'Anwar Al Madinah Movenpick',
                'image_url' => 'https://images.unsplash.com/photo-1524230572899-a752b3835840?w=1200&q=80',
                'order' => 2,
                'facilities' => array_merge($standardFacilities, ['Tour Istanbul & Bursa', 'Visa Turki']),
                'room_types' => [
                    ['label' => 'Quad (1 kamar berempat)', 'price' => 38900000],
                    ['label' => 'Triple (1 kamar bertiga)', 'price' => 41400000],
                    ['label' => 'Double (1 kamar berdua)', 'price' => 44900000],
                ],
                'itinerary' => [
                    ['day' => 1, 'title' => 'Keberangkatan Jakarta - Istanbul', 'description' => 'Berkumpul di Bandara Soekarno-Hatta, terbang menuju Istanbul.'],
                    ['day' => 2, 'title' => 'City Tour Istanbul', 'description' => 'Mengunjungi Hagia Sophia, Blue Mosque, dan Topkapi Palace.'],
                    ['day' => 3, 'title' => 'Bursa', 'description' => 'Perjalanan ke Bursa, mengunjungi Grand Mosque dan Green Tomb.'],
                    ['day' => 4, 'title' => 'Istanbul - Jeddah', 'description' => 'Terbang menuju Jeddah, dilanjutkan perjalanan ke Madinah.'],
                    ['day' => 5, 'title' => 'Ziarah Madinah', 'description' => 'Ziarah Masjid Quba, Jabal Uhud, dan Kebun Kurma.'],
                ],
                'requirements' => "1. Paspor berlaku minimal 8 bulan\n2. Kartu vaksin meningitis\n3. Pas foto berwarna 4x6 latar putih\n4. Dokumen pendukung visa Turki",
                'terms_conditions' => "1. Uang muka minimal Rp 10.000.000\n2. Pelunasan paling lambat 45 hari sebelum keberangkatan\n3. Harga belum termasuk pengeluaran pribadi",
            ],
            [
                'type' => 'haji',
                'name' => 'Haji Khusus Paket Utama',
                'short_description' => 'Haji khusus dengan hotel dekat Masjidil Haram.',
                'description' => "Paket haji khusus dengan akomodasi hotel berjarak dekat dari Masjidil Haram dan Masjid Nabawi, didampingi pembimbing ibadah berpengalaman.",
                'duration' => 'Haji Khusus 26 Hari',
                'price_from' => 178000000,
                'departure_city' => 'Jakarta',
                'departure_airport' => 'Bandara Soekarno-Hatta (CGK)',
                'departure_date_note' => 'Estimasi keberangkatan tahun 2031',
                'seats_total' => 45,
                'seats_available' => 23,
                'airline' => 'Saudia',
                'hotel_makkah' => 'Pullman Zamzam Makkah',
                'hotel_madinah' => 'Dar Al Taqwa',
                'image_url' => 'https://images.unsplash.com/photo-1519074069444-1ba4fff66d16?w=1200&q=80',
                'order' => 3,
                'facilities' => array_merge($standardFacilities, ['Tenda Arafah & Mina VIP', 'Dam/kurban', 'Pembimbing ibadah']),
                'room_types' => [
                    ['label' => 'Quad (1 kamar berempat)', 'price' => 178000000],
                    ['label' => 'Triple (1 kamar bertiga)', 'price' => 192000000],
                    ['label' => 'Double (1 kamar berdua)', 'price' => 215000000],
                ],
                'requirements' => "1. Paspor berlaku minimal 8 bulan\n2. Kartu vaksin meningitis\n3. Nomor porsi haji khusus\n4. Surat keterangan sehat dari dokter",
                'terms_conditions' => "1. Setoran awal sesuai ketentuan Kementerian Agama\n2. Keberangkatan mengikuti kuota dan antrean resmi\n3. Estimasi tahun keberangkatan dapat berubah",
            ],
            [
                'type' => 'wisata_religi',
                'name' => 'Ziarah Wali Songo 5 Hari',
                'short_description' => 'Menyusuri jejak penyebar Islam di tanah Jawa.',
                'description' => "Perjalanan ziarah mengunjungi makam Wali Songo di sepanjang pulau Jawa, dilengkapi pembimbing yang menjelaskan sejarah tiap tempat.",
                'duration' => 'Ziarah 5 Hari',
                'price_from' => 2850000,
                'departure_city' => 'Malang',
                'departure_airport' => '-',
                'departure_date_note' => 'Berangkat setiap akhir pekan',
                'seats_total' => 50,
                'seats_available' => 31,
                'airline' => 'Bus Pariwisata AC',
                'image_url' => 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?w=1200&q=80',
                'order' => 4,
                'facilities' => ['Bus pariwisata AC', 'Konsumsi 3x sehari', 'Penginapan', 'Pembimbing ziarah', 'Air mineral'],
                'room_types' => [
                    ['label' => 'Sharing room', 'price' => 2850000],
                    ['label' => 'Kamar berdua', 'price' => 3450000],
                ],
                'requirements' => "1. Fotokopi KTP\n2. Kondisi sehat jasmani",
                'terms_conditions' => "1. Uang muka Rp 500.000\n2. Pelunasan 7 hari sebelum keberangkatan",
            ],
        ];
    }

    private function seedTestimonials(): void
    {
        $testimonials = [
            ['Siti Rahmawati', 'Malang', 'umroh', 2025, 5, 'Alhamdulillah pelayanannya sangat memuaskan. Muthawif sabar membimbing dari awal sampai akhir, hotelnya juga dekat dengan Masjidil Haram.'],
            ['Ahmad Fauzi', 'Surabaya', 'umroh', 2025, 5, 'Ini keberangkatan kedua saya bersama Barokah Mulia. Jadwal ibadah tertata rapi, konsumsi terjamin, dan pembimbingnya komunikatif.'],
            ['Hj. Nurhayati', 'Sidoarjo', 'haji', 2024, 5, 'Pelayanan haji khususnya benar-benar amanah. Sejak pendaftaran sampai kepulangan semua diurus dengan baik dan transparan.'],
            ['Bambang Sutrisno', 'Kediri', 'umroh', 2024, 4, 'Harga sesuai dengan fasilitas yang didapat. Tim pendamping responsif menjawab pertanyaan keluarga di tanah air.'],
            ['Dewi Kartika', 'Blitar', 'wisata_religi', 2025, 5, 'Ziarah Wali Songo-nya berkesan sekali. Pembimbing menjelaskan sejarah tiap makam dengan detail dan mudah dipahami.'],
            ['H. Slamet Riyadi', 'Malang', 'umroh', 2023, 5, 'Sudah tiga kali berangkat bersama travel ini dan tidak pernah kecewa. Insya Allah akan mengajak keluarga besar tahun depan.'],
        ];

        foreach ($testimonials as $index => [$name, $city, $type, $year, $rating, $content]) {
            Testimonial::query()->updateOrCreate(
                ['jamaah_name' => $name],
                [
                    'jamaah_city' => $city,
                    'package_type' => $type,
                    'year' => $year,
                    'rating' => $rating,
                    'content' => $content,
                    'is_published' => true,
                    'order' => $index + 1,
                ],
            );
        }
    }

    private function seedArticles(): void
    {
        $articles = [
            [
                'Panduan Lengkap Persiapan Umrah untuk Pemula',
                'Info Umroh',
                'https://images.unsplash.com/photo-1565552645632-d725f8bfc19a?w=1200&q=80',
                'Berangkat umrah pertama kali? Simak persiapan dokumen, fisik, dan mental yang perlu Anda lakukan.',
            ],
            [
                'Perbedaan Haji Reguler, Haji Khusus, dan Haji Furoda',
                'Info Haji',
                'https://images.unsplash.com/photo-1542816417-0983c9c9ad53?w=1200&q=80',
                'Memahami perbedaan ketiga jenis penyelenggaraan haji agar Anda dapat memilih sesuai kebutuhan.',
            ],
            [
                'Tips Menjaga Kesehatan Selama Beribadah di Tanah Suci',
                'Tips',
                'https://images.unsplash.com/photo-1580418827493-f2b22c0a76cb?w=1200&q=80',
                'Cuaca dan aktivitas ibadah yang padat menuntut kondisi prima. Berikut tips menjaga stamina.',
            ],
            [
                'Waspada Penipuan Berkedok Travel Umrah Murah',
                'Info Umroh',
                'https://images.unsplash.com/photo-1512236258305-32fb110fdb01?w=1200&q=80',
                'Kenali ciri-ciri travel bodong dan cara memastikan legalitas penyelenggara sebelum mendaftar.',
            ],
            [
                'Amalan Sunnah yang Dianjurkan Saat di Madinah',
                'Panduan Ibadah',
                'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?w=1200&q=80',
                'Selain shalat arbain, ada beberapa amalan sunnah yang sayang jika dilewatkan selama di Madinah.',
            ],
            [
                'Dokumen yang Wajib Disiapkan Sebelum Keberangkatan',
                'Tips',
                'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=1200&q=80',
                'Checklist dokumen penting agar proses keberangkatan Anda berjalan lancar tanpa kendala.',
            ],
        ];

        foreach ($articles as $index => [$title, $category, $image, $excerpt]) {
            Article::query()->updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'category' => $category,
                    'excerpt' => $excerpt,
                    'body' => $this->dummyArticleBody($excerpt),
                    'featured_image_url' => $image,
                    'is_published' => true,
                    'published_at' => now()->subDays(($index + 1) * 6),
                ],
            );
        }
    }

    private function dummyArticleBody(string $excerpt): string
    {
        return $excerpt . "\n\n"
            . "Perjalanan ibadah ke tanah suci adalah momen yang dinanti banyak umat muslim. Karena itu, persiapan yang matang menjadi kunci agar ibadah dapat dijalani dengan khusyuk dan nyaman.\n\n"
            . "Hal pertama yang perlu diperhatikan adalah kelengkapan dokumen. Pastikan paspor masih berlaku minimal delapan bulan sejak tanggal keberangkatan dan nama pada paspor terdiri dari minimal tiga suku kata sesuai ketentuan yang berlaku.\n\n"
            . "Selanjutnya, persiapkan kondisi fisik. Biasakan berjalan kaki setiap hari beberapa minggu sebelum keberangkatan, karena aktivitas ibadah menuntut mobilitas yang cukup tinggi.\n\n"
            . "Terakhir, jangan lupa mempersiapkan bekal ilmu. Ikuti manasik dengan sungguh-sungguh agar setiap rangkaian ibadah dapat dijalankan sesuai tuntunan.\n\n"
            . "Semoga Allah memudahkan langkah kita semua menuju Baitullah.";
    }

    private function seedHomePage(): void
    {
        $page = Page::query()->updateOrCreate(
            ['slug' => 'home'],
            ['title' => 'Home', 'is_published' => true, 'order' => 1],
        );

        // Bersihkan section lama supaya seeder idempotent (aman dijalankan berulang)
        Section::query()->where('page_id', $page->id)->delete();

        foreach ($this->homeSections() as $order => $section) {
            Section::query()->create([
                'page_id' => $page->id,
                'type' => $section['type'],
                'content' => $section['content'],
                'order' => $order + 1,
                'is_visible' => true,
            ]);
        }
    }

    private function homeSections(): array
    {
        return [
            [
                'type' => 'hero_slider',
                'content' => [
                    'slides' => [
                        [
                            'image_url' => 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?w=1600&q=80',
                            'title' => 'Umrah Promo Mulai Rp 27,5 Juta',
                            'subtitle' => 'Keberangkatan Oktober 2026 · 12 hari · Hotel bintang 4 dekat Masjidil Haram',
                            'cta_text' => 'Lihat Paket',
                            'cta_link' => '/paket',
                        ],
                        [
                            'image_url' => 'https://images.unsplash.com/photo-1519074069444-1ba4fff66d16?w=1600&q=80',
                            'title' => 'Haji Khusus dengan Pelayanan Amanah',
                            'subtitle' => 'Hotel dekat Masjidil Haram, tenda VIP di Arafah dan Mina',
                            'cta_text' => 'Selengkapnya',
                            'cta_link' => '/paket?type=haji',
                        ],
                        [
                            'image_url' => 'https://images.unsplash.com/photo-1524230572899-a752b3835840?w=1600&q=80',
                            'title' => 'Umrah Plus Turki 16 Hari',
                            'subtitle' => 'Beribadah sekaligus menyusuri jejak peradaban Islam',
                            'cta_text' => 'Lihat Detail',
                            'cta_link' => '/paket',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'highlights',
                'content' => [
                    'title' => 'Kenapa Memilih Kami',
                    'items' => [
                        ['icon' => 'bx-check-shield', 'title' => 'Legalitas Resmi', 'description' => 'Terdaftar resmi sebagai penyelenggara umrah dan haji khusus.'],
                        ['icon' => 'bx-wallet', 'title' => 'Harga Transparan', 'description' => 'Rincian biaya jelas sejak awal, tanpa biaya tersembunyi.'],
                        ['icon' => 'bx-task', 'title' => 'Pendaftaran Mudah', 'description' => 'Proses pendaftaran sederhana dan dibantu tim kami.'],
                        ['icon' => 'bx-group', 'title' => 'Pendamping Berpengalaman', 'description' => 'Muthawif bersertifikat mendampingi seluruh rangkaian ibadah.'],
                    ],
                ],
            ],
            [
                'type' => 'about',
                'content' => [
                    'eyebrow' => 'Tentang Kami',
                    'title' => 'Melayani Perjalanan Ibadah Sejak 2010',
                    'content' => "Barokah Mulia Tour & Travel adalah penyelenggara perjalanan ibadah umrah dan haji khusus yang telah dipercaya ribuan jamaah dari berbagai kota di Indonesia.\n\nKami berkomitmen menghadirkan pelayanan yang amanah, transparan, dan nyaman, agar setiap jamaah dapat fokus beribadah tanpa mengkhawatirkan urusan teknis perjalanan.",
                    'image_url' => 'https://images.unsplash.com/photo-1466442929976-97f336a657be?w=1200&q=80',
                    'cta_text' => 'Lihat Layanan Kami',
                    'cta_link' => '/paket',
                ],
            ],
            [
                'type' => 'stats',
                'content' => [
                    'items' => [
                        ['value' => '15', 'suffix' => '+', 'label' => 'Tahun Pengalaman'],
                        ['value' => '12.000', 'suffix' => '+', 'label' => 'Jamaah Diberangkatkan'],
                        ['value' => '250', 'suffix' => '+', 'label' => 'Grup Keberangkatan'],
                        // Koma (bukan titik) -- konvensi desimal Indonesia, sekaligus
                        // dibaca benar oleh animasi counter di public.js.
                        ['value' => '4,9', 'suffix' => '/5', 'label' => 'Rata-rata Kepuasan'],
                    ],
                ],
            ],
            [
                'type' => 'packages_preview',
                'content' => [
                    'eyebrow' => 'Layanan Kami',
                    'title' => 'Paket Perjalanan Pilihan',
                    'package_type_filter' => 'all',
                    'limit' => 4,
                ],
            ],
            [
                'type' => 'islamic_quote',
                'content' => [
                    'arabic_text' => 'وَأَتِمُّوا الْحَجَّ وَالْعُمْرَةَ لِلَّهِ',
                    'quote_text' => 'Dan sempurnakanlah ibadah haji dan umrah karena Allah.',
                    'source' => 'QS. Al-Baqarah: 196',
                ],
            ],
            [
                'type' => 'facilities',
                'content' => [
                    'eyebrow' => 'Fasilitas Jamaah',
                    'title' => 'Yang Anda Dapatkan',
                    'subtitle' => 'Seluruh kebutuhan perjalanan ibadah Anda kami siapkan dengan teliti.',
                    'items' => [
                        ['icon' => 'bxs-plane-take-off', 'label' => 'Tiket Pesawat', 'description' => 'Penerbangan pulang-pergi dengan maskapai terpercaya.'],
                        ['icon' => 'bx-buildings', 'label' => 'Hotel Nyaman', 'description' => 'Akomodasi berjarak dekat dari masjid.'],
                        ['icon' => 'bx-restaurant', 'label' => 'Konsumsi', 'description' => 'Menu Indonesia tiga kali sehari selama perjalanan.'],
                        ['icon' => 'bx-id-card', 'label' => 'Visa Umrah', 'description' => 'Pengurusan visa ditangani sepenuhnya oleh tim kami.'],
                        ['icon' => 'bx-bus', 'label' => 'Transportasi', 'description' => 'Bus ber-AC untuk seluruh perjalanan darat.'],
                        ['icon' => 'bx-briefcase-alt-2', 'label' => 'Perlengkapan', 'description' => 'Koper, kain ihram, mukena, dan buku panduan.'],
                        ['icon' => 'bx-user-voice', 'label' => 'Muthawif', 'description' => 'Pembimbing bersertifikat selama ibadah.'],
                        ['icon' => 'bx-camera', 'label' => 'Dokumentasi', 'description' => 'Foto dan video momen perjalanan Anda.'],
                    ],
                ],
            ],
            [
                'type' => 'testimonials_preview',
                'content' => [
                    'eyebrow' => 'Testimoni',
                    'title' => 'Kata Mereka yang Telah Berangkat',
                    'limit' => 3,
                ],
            ],
            [
                'type' => 'gallery',
                'content' => [
                    'eyebrow' => 'Dokumentasi',
                    'title' => 'Galeri Perjalanan Jamaah',
                    'subtitle' => 'Momen kebersamaan jamaah selama menunaikan ibadah di tanah suci.',
                    'images' => [
                        ['image_url' => 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?w=800&q=80', 'caption' => 'Masjid Nabawi, Madinah'],
                        ['image_url' => 'https://images.unsplash.com/photo-1519074069444-1ba4fff66d16?w=800&q=80', 'caption' => 'Masjidil Haram, Makkah'],
                        ['image_url' => 'https://images.unsplash.com/photo-1565552645632-d725f8bfc19a?w=800&q=80', 'caption' => 'Suasana tawaf'],
                        ['image_url' => 'https://images.unsplash.com/photo-1519817650390-64a93db51149?w=800&q=80', 'caption' => 'Ziarah Jabal Rahmah'],
                        ['image_url' => 'https://images.unsplash.com/photo-1580418827493-f2b22c0a76cb?w=800&q=80', 'caption' => 'Kegiatan manasik'],
                        ['image_url' => 'https://images.unsplash.com/photo-1524230572899-a752b3835840?w=800&q=80', 'caption' => 'Umrah plus Turki'],
                        ['image_url' => 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?w=800&q=80', 'caption' => 'Ziarah Wali Songo'],
                        ['image_url' => 'https://images.unsplash.com/photo-1466442929976-97f336a657be?w=800&q=80', 'caption' => 'Kebersamaan jamaah'],
                    ],
                ],
            ],
            [
                'type' => 'articles_preview',
                'content' => [
                    'eyebrow' => 'Berita Terbaru',
                    'title' => 'Artikel & Informasi',
                    'limit' => 3,
                ],
            ],
            [
                'type' => 'cta_whatsapp',
                'content' => [
                    'title' => 'Siap Menuju Baitullah?',
                    'description' => 'Konsultasikan rencana perjalanan ibadah Anda dengan tim kami. Gratis, tanpa kewajiban mendaftar.',
                    'button_text' => 'Konsultasi Sekarang',
                ],
            ],
            [
                'type' => 'contact',
                'content' => [
                    'eyebrow' => 'Kontak',
                    'title' => 'Hubungi Kami',
                    'show_map' => true,
                    'show_form' => false,
                ],
            ],
        ];
    }
}
