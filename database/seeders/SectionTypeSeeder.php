<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Dijalankan oleh TenantMigrationService::seed() setelah search_path
 * diarahkan ke schema tenant yang baru dibuat -- BUKAN lewat
 * `php artisan db:seed` biasa.
 *
 * Catatan sprint: field di `schema` ini disiapkan untuk dokumentasi &
 * validasi payload (lihat method validationRules() di bawah), TAPI untuk
 * sprint 1,5 minggu ini form CMS-nya di-hard-code per tipe (bukan
 * auto-generate dari sini) -- lihat SPRINT_PLAN.md Hari 3. Blueprint ini
 * tetap penting supaya validasi & struktur `content` konsisten sejak awal.
 */
class SectionTypeSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->definitions() as $order => $definition) {
            DB::table('section_types')->updateOrInsert(
                ['type_key' => $definition['type_key']],
                [
                    'label' => $definition['label'],
                    'description' => $definition['description'],
                    'schema' => json_encode($definition['schema']),
                    'is_active' => true,
                    'order' => $order + 1,
                ]
            );
        }
    }

    /**
     * @return array<int, array{type_key: string, label: string, description: string, schema: array}>
     */
    private function definitions(): array
    {
        return [
            [
                'type_key' => 'hero_slider',
                'label' => 'Hero Slider',
                'description' => 'Slider gambar besar di paling atas halaman, biasanya berisi tagline utama.',
                'schema' => [
                    'fields' => [
                        [
                            'key' => 'slides',
                            'label' => 'Slide',
                            'type' => 'repeater',
                            'min' => 1,
                            'max' => 6,
                            'fields' => [
                                ['key' => 'image_url', 'label' => 'Gambar', 'type' => 'image', 'required' => true],
                                ['key' => 'title', 'label' => 'Judul', 'type' => 'text', 'required' => true, 'max' => 80],
                                ['key' => 'subtitle', 'label' => 'Subjudul', 'type' => 'text', 'max' => 150],
                                ['key' => 'cta_text', 'label' => 'Teks Tombol', 'type' => 'text', 'max' => 30],
                                ['key' => 'cta_link', 'label' => 'Link Tombol', 'type' => 'text'],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type_key' => 'about',
                'label' => 'Tentang Kami',
                'description' => 'Profil singkat perusahaan travel, biasanya dengan foto pendukung.',
                'schema' => [
                    'fields' => [
                        ['key' => 'title', 'label' => 'Judul', 'type' => 'text', 'required' => true, 'max' => 100],
                        ['key' => 'content', 'label' => 'Isi', 'type' => 'richtext', 'required' => true],
                        ['key' => 'image_url', 'label' => 'Gambar Pendukung', 'type' => 'image'],
                    ],
                ],
            ],
            [
                'type_key' => 'highlights',
                'label' => 'Keunggulan Layanan',
                'description' => 'Poin-poin keunggulan (mis. pembimbing berpengalaman, harga transparan).',
                'schema' => [
                    'fields' => [
                        ['key' => 'title', 'label' => 'Judul Section', 'type' => 'text', 'max' => 100],
                        [
                            'key' => 'items',
                            'label' => 'Poin Keunggulan',
                            'type' => 'repeater',
                            'min' => 1,
                            'max' => 8,
                            'fields' => [
                                ['key' => 'icon', 'label' => 'Icon', 'type' => 'text'],
                                ['key' => 'title', 'label' => 'Judul', 'type' => 'text', 'required' => true, 'max' => 60],
                                ['key' => 'description', 'label' => 'Deskripsi', 'type' => 'textarea', 'max' => 200],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type_key' => 'packages_preview',
                'label' => 'Preview Paket',
                'description' => 'Auto-fetch dari tabel packages -- tampilkan beberapa paket terbaru/pilihan.',
                'schema' => [
                    'fields' => [
                        ['key' => 'title', 'label' => 'Judul Section', 'type' => 'text', 'max' => 100],
                        ['key' => 'package_type_filter', 'label' => 'Filter Tipe', 'type' => 'select', 'options' => ['all', 'umroh', 'haji', 'wisata_religi'], 'default' => 'all'],
                        ['key' => 'limit', 'label' => 'Jumlah Ditampilkan', 'type' => 'number', 'min' => 1, 'max' => 12, 'default' => 3],
                    ],
                ],
            ],
            [
                'type_key' => 'facilities',
                'label' => 'Fasilitas Jamaah',
                'description' => 'Daftar fasilitas yang didapat jamaah (hotel, katering, dsb).',
                'schema' => [
                    'fields' => [
                        ['key' => 'title', 'label' => 'Judul Section', 'type' => 'text', 'max' => 100],
                        [
                            'key' => 'items',
                            'label' => 'Fasilitas',
                            'type' => 'repeater',
                            'min' => 1,
                            'max' => 12,
                            'fields' => [
                                ['key' => 'icon', 'label' => 'Icon', 'type' => 'text'],
                                ['key' => 'label', 'label' => 'Nama Fasilitas', 'type' => 'text', 'required' => true, 'max' => 60],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type_key' => 'stats',
                'label' => 'Statistik Pencapaian',
                'description' => 'Angka pencapaian, mis. jumlah jamaah diberangkatkan, tahun pengalaman.',
                'schema' => [
                    'fields' => [
                        [
                            'key' => 'items',
                            'label' => 'Statistik',
                            'type' => 'repeater',
                            'min' => 1,
                            'max' => 6,
                            'fields' => [
                                ['key' => 'value', 'label' => 'Angka', 'type' => 'text', 'required' => true, 'max' => 20],
                                ['key' => 'suffix', 'label' => 'Suffix (mis. +, K)', 'type' => 'text', 'max' => 10],
                                ['key' => 'label', 'label' => 'Keterangan', 'type' => 'text', 'required' => true, 'max' => 60],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type_key' => 'islamic_quote',
                'label' => 'Kutipan Islami',
                'description' => 'Kutipan Al-Quran/Hadits yang relevan, biasanya untuk penyegaran spiritual.',
                'schema' => [
                    'fields' => [
                        ['key' => 'quote_text', 'label' => 'Teks Kutipan', 'type' => 'textarea', 'required' => true, 'max' => 500],
                        ['key' => 'source', 'label' => 'Sumber (mis. QS. Al-Baqarah: 197)', 'type' => 'text', 'max' => 100],
                    ],
                ],
            ],
            [
                'type_key' => 'testimonials_preview',
                'label' => 'Preview Testimoni',
                'description' => 'Auto-fetch dari tabel testimonials -- social proof dari jamaah.',
                'schema' => [
                    'fields' => [
                        ['key' => 'title', 'label' => 'Judul Section', 'type' => 'text', 'max' => 100],
                        ['key' => 'limit', 'label' => 'Jumlah Ditampilkan', 'type' => 'number', 'min' => 1, 'max' => 12, 'default' => 3],
                    ],
                ],
            ],
            [
                'type_key' => 'gallery',
                'label' => 'Galeri Dokumentasi',
                'description' => 'Kumpulan foto dokumentasi kegiatan/perjalanan.',
                'schema' => [
                    'fields' => [
                        ['key' => 'title', 'label' => 'Judul Section', 'type' => 'text', 'max' => 100],
                        [
                            'key' => 'images',
                            'label' => 'Foto',
                            'type' => 'repeater',
                            'min' => 1,
                            'max' => 20,
                            'fields' => [
                                ['key' => 'image_url', 'label' => 'Gambar', 'type' => 'image', 'required' => true],
                                ['key' => 'caption', 'label' => 'Keterangan', 'type' => 'text', 'max' => 100],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type_key' => 'articles_preview',
                'label' => 'Preview Artikel',
                'description' => 'Auto-fetch dari tabel articles -- konten edukatif terbaru.',
                'schema' => [
                    'fields' => [
                        ['key' => 'title', 'label' => 'Judul Section', 'type' => 'text', 'max' => 100],
                        ['key' => 'category_filter', 'label' => 'Filter Kategori', 'type' => 'text'],
                        ['key' => 'limit', 'label' => 'Jumlah Ditampilkan', 'type' => 'number', 'min' => 1, 'max' => 12, 'default' => 3],
                    ],
                ],
            ],
            [
                'type_key' => 'cta_whatsapp',
                'label' => 'CTA WhatsApp',
                'description' => 'Ajakan bertindak untuk hubungi via WhatsApp. Nomor & pesan default ambil dari settings.',
                'schema' => [
                    'fields' => [
                        ['key' => 'title', 'label' => 'Judul', 'type' => 'text', 'max' => 100],
                        ['key' => 'description', 'label' => 'Deskripsi Singkat', 'type' => 'textarea', 'max' => 200],
                        ['key' => 'button_text', 'label' => 'Teks Tombol', 'type' => 'text', 'max' => 30, 'default' => 'Chat via WhatsApp'],
                    ],
                ],
            ],
            [
                'type_key' => 'contact',
                'label' => 'Kontak',
                'description' => 'Info kontak & peta lokasi. Detail kontak diambil dari settings, section ini cuma atur tampilannya.',
                'schema' => [
                    'fields' => [
                        ['key' => 'title', 'label' => 'Judul Section', 'type' => 'text', 'max' => 100],
                        ['key' => 'show_map', 'label' => 'Tampilkan Peta', 'type' => 'boolean', 'default' => true],
                        ['key' => 'show_form', 'label' => 'Tampilkan Form Kontak', 'type' => 'boolean', 'default' => false],
                    ],
                ],
            ],
        ];
    }
}
