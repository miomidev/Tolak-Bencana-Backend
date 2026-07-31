<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\HelpContact;
use Illuminate\Database\Seeder;

class HelpSeeder extends Seeder
{
    public function run(): void
    {
        // ── Help Contacts ─────────────────────────────────────────────
        HelpContact::truncate();

        HelpContact::insert([
            [
                'type' => 'phone',
                'title' => 'Telepon',
                'value' => '0800-1234-5678 (Gratis)',
                'icon' => 'phone',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'email',
                'title' => 'Email',
                'value' => 'bantuan@kaliwedi.id',
                'icon' => 'email',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'website',
                'title' => 'Website Resmi',
                'value' => 'kaliwedi.indramayu.go.id',
                'icon' => 'website',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ── FAQs ─────────────────────────────────────────────────────
        Faq::truncate();

        Faq::insert([
            [
                'question' => 'Bagaimana cara membuat laporan baru?',
                'answer' => "Buka halaman Beranda, lalu ketuk tombol '+' untuk membuat laporan baru. Isi detail masalah, foto, dan lokasi, kemudian kirim.",
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Berapa lama laporan saya diproses?',
                'answer' => 'Laporan biasanya diproses dalam 1–3 hari kerja. Anda dapat memantau status laporan melalui menu Riwayat Laporan.',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Apakah laporan saya bisa anonim?',
                'answer' => 'Saat ini laporan memerlukan akun terdaftar. Namun identitas Anda hanya terlihat oleh petugas yang berwenang, bukan publik.',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Bagaimana jika laporan saya ditolak?',
                'answer' => 'Jika laporan ditolak, Anda akan mendapat notifikasi beserta alasannya. Anda dapat mengedit laporan dan mengirim ulang.',
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Bagaimana cara memperbarui profil saya?',
                'answer' => 'Buka menu Profil, lalu ketuk "Edit Profil" untuk mengubah nama, foto, atau informasi kontak Anda.',
                'sort_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
