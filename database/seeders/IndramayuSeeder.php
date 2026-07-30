<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Report;
use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Models\SavedLocation;
use App\Models\Shelter;
use App\Models\DisasterZone;
use App\Models\AppInfo;
use App\Models\ReportLike;
use App\Models\ReportComment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class IndramayuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function up(): void
    {
        // Handled in run()
    }

    public function run(): void
    {
        // 1. Seed Users
        $usersData = [
            [
                'name' => 'Super Administrator',
                'username' => 'superadmin',
                'email' => 'superadmin@kaliwedi.id',
                'password' => Hash::make('password'),
                'phone' => '081100000000',
                'role' => 'superadmin',
                'fcm_token' => 'fcm_token_superadmin_mock_123',
            ],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@kaliwedi.id',
                'password' => Hash::make('password'),
                'phone' => '081122334455',
                'role' => 'admin',
                'fcm_token' => 'fcm_token_admin_mock_123',
            ],
            [
                'name' => 'Petugas Lohbener',
                'username' => 'officer_lohbener',
                'email' => 'officer_lohbener@kaliwedi.id',
                'password' => Hash::make('password'),
                'phone' => '087722334455',
                'role' => 'admin',
                'fcm_token' => 'fcm_token_officer_lohbener_mock_123',
            ],
            [
                'name' => 'Petugas Jatibarang',
                'username' => 'officer_jatibarang',
                'email' => 'officer_jatibarang@kaliwedi.id',
                'password' => Hash::make('password'),
                'phone' => '085222334455',
                'role' => 'admin',
                'fcm_token' => 'fcm_token_officer_jatibarang_mock_123',
            ],
            [
                'name' => 'Budi Santoso (Indramayu Kota)',
                'username' => 'budisantoso',
                'email' => 'budi@gmail.com',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'role' => 'user',
                'fcm_token' => 'fcm_token_budi_mock_123',
            ],
            [
                'name' => 'Siti Aminah (Haurgeulis)',
                'username' => 'sitiaminah',
                'email' => 'siti@gmail.com',
                'password' => Hash::make('password'),
                'phone' => '081234567891',
                'role' => 'user',
                'fcm_token' => 'fcm_token_siti_mock_123',
            ],
            [
                'name' => 'Ahmad Fauzi (Patrol)',
                'username' => 'ahmadfauzi',
                'email' => 'ahmad@gmail.com',
                'password' => Hash::make('password'),
                'phone' => '081234567892',
                'role' => 'user',
                'fcm_token' => 'fcm_token_ahmad_mock_123',
            ],
            [
                'name' => 'Dewi Lestari (Cantigi)',
                'username' => 'dewilestari',
                'email' => 'dewi@gmail.com',
                'password' => Hash::make('password'),
                'phone' => '081234567893',
                'role' => 'user',
                'fcm_token' => 'fcm_token_dewi_mock_123',
            ],
        ];

        $users = [];
        foreach ($usersData as $userData) {
            $userData['avatar_url'] = 'https://ui-avatars.com/api/?name=' . urlencode($userData['name']) . '&background=0F767E&color=fff';
            $user = User::create($userData);
            $users[$user->email] = $user;

            // 2. Seed Notification Settings (One-to-One with User)
            NotificationSetting::create([
                'user_id' => $user->id,
                'flood_alert' => true,
                'report_update' => true,
                'emergency_alert' => true,
                'location_alert' => $user->role === 'user',
            ]);
        }

        // 3. Seed Saved Locations
        $savedLocations = [
            [
                'user_email' => 'budi@gmail.com',
                'place_name' => 'Rumah Utama',
                'latitude' => -6.32630000,
                'longitude' => 108.32470000,
                'address' => 'Jl. Jend. Sudirman No. 12, Lemahmekar, Kec. Indramayu',
                'is_primary' => true,
                'icon_type' => 'home',
            ],
            [
                'user_email' => 'budi@gmail.com',
                'place_name' => 'Toko Kelontong',
                'latitude' => -6.33580000,
                'longitude' => 108.30750000,
                'address' => 'Jl. Raya Sindang, Kec. Sindang',
                'is_primary' => false,
                'icon_type' => 'work',
            ],
            [
                'user_email' => 'siti@gmail.com',
                'place_name' => 'Rumah Utama',
                'latitude' => -6.45890000,
                'longitude' => 107.96220000,
                'address' => 'Blok Mawar, Sukajati, Kec. Haurgeulis',
                'is_primary' => true,
                'icon_type' => 'home',
            ],
            [
                'user_email' => 'ahmad@gmail.com',
                'place_name' => 'Rumah Utama',
                'latitude' => -6.29740000,
                'longitude' => 107.99470000,
                'address' => 'Desa Patrol Lor RT 05 RW 02, Kec. Patrol',
                'is_primary' => true,
                'icon_type' => 'home',
            ],
            [
                'user_email' => 'dewi@gmail.com',
                'place_name' => 'Rumah Utama',
                'latitude' => -6.29520000,
                'longitude' => 108.26120000,
                'address' => 'Desa Panyingkiran Kidul, Kec. Cantigi',
                'is_primary' => true,
                'icon_type' => 'home',
            ],
        ];

        foreach ($savedLocations as $loc) {
            $user = $users[$loc['user_email']] ?? null;
            if ($user) {
                SavedLocation::create([
                    'user_id' => $user->id,
                    'place_name' => $loc['place_name'],
                    'latitude' => $loc['latitude'],
                    'longitude' => $loc['longitude'],
                    'address' => $loc['address'],
                    'is_primary' => $loc['is_primary'],
                    'icon_type' => $loc['icon_type'],
                ]);
            }
        }

        // 4. Seed Reports
        $reports = [
            [
                'user_email' => 'budi@gmail.com',
                'latitude' => -6.40690000,
                'longitude' => 108.28250000,
                'address' => 'Blok Bojong, Desa Lohbener, Kec. Lohbener',
                'description' => 'Luapan sungai Cimanuk menggenangi pemukiman warga setinggi 50cm. Beberapa warga mulai mengevakuasi barang berharga.',
                'photo_url' => 'https://images.unsplash.com/photo-1547683905-f686c993aae5',
                'severity' => 'high',
                'status' => 'verified',
                'category' => 'Banjir',
                'reported_at' => Carbon::now()->subHours(2),
            ],
            [
                'user_email' => 'siti@gmail.com',
                'latitude' => -6.47440000,
                'longitude' => 108.31010000,
                'address' => 'Blok Karanganyar, Kec. Jatibarang (Dekat Jembatan Cimanuk)',
                'description' => 'Tanggul Sungai Cimanuk rembes dan kritis. Aliran air sangat deras, dikhawatirkan tanggul akan jebol jika hujan terus berlanjut.',
                'photo_url' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c',
                'severity' => 'critical',
                'status' => 'pending',
                'category' => 'Tanggul Kritis',
                'reported_at' => Carbon::now()->subMinutes(30),
            ],
            [
                'user_email' => 'dewi@gmail.com',
                'latitude' => -6.29520000,
                'longitude' => 108.26120000,
                'address' => 'Area Tambak Udang Desa Cantigi Kulon, Kec. Cantigi',
                'description' => 'Banjir rob air pasang laut masuk ke pemukiman dan merendam puluhan hektar tambak warga setinggi 30cm.',
                'photo_url' => null,
                'severity' => 'medium',
                'status' => 'resolved',
                'category' => 'Banjir Rob',
                'reported_at' => Carbon::now()->subDays(1),
            ],
            [
                'user_email' => 'ahmad@gmail.com',
                'latitude' => -6.29740000,
                'longitude' => 107.99470000,
                'address' => 'Jalan Raya Patrol, Depan Pasar Patrol, Kec. Patrol',
                'description' => 'Genangan air akibat drainase buruk setinggi 20-30cm setelah hujan deras selama 3 jam. Arus lalu lintas tersendat.',
                'photo_url' => 'https://images.unsplash.com/photo-1547683905-f686c993aae5',
                'severity' => 'low',
                'status' => 'verified',
                'category' => 'Genangan Air',
                'reported_at' => Carbon::now()->subHours(4),
            ],
        ];

        foreach ($reports as $rep) {
            $user = $users[$rep['user_email']] ?? null;
            if ($user) {
                Report::create([
                    'user_id' => $user->id,
                    'latitude' => $rep['latitude'],
                    'longitude' => $rep['longitude'],
                    'address' => $rep['address'],
                    'description' => $rep['description'],
                    'photo_url' => $rep['photo_url'],
                    'severity' => $rep['severity'],
                    'status' => $rep['status'],
                    'category' => $rep['category'],
                    'reported_at' => $rep['reported_at'],
                ]);
            }
        }

        // 5. Seed Notifications
        $notifications = [
            [
                'user_email' => 'budi@gmail.com',
                'title' => 'Peringatan Banjir Lohbener',
                'body' => 'Laporan banjir di Lohbener telah terverifikasi. Ketinggian air mencapai 50cm. Harap waspada dan siapkan barang darurat Anda.',
                'type' => 'flood_alert',
                'is_read' => false,
                'sent_at' => Carbon::now()->subHours(2),
            ],
            [
                'user_email' => 'siti@gmail.com',
                'title' => 'Laporan Banjir Diterima',
                'body' => 'Terima kasih atas laporan Anda mengenai kondisi kritis tanggul Cimanuk Jatibarang. Petugas sedang menuju ke lokasi.',
                'type' => 'report_update',
                'is_read' => true,
                'sent_at' => Carbon::now()->subMinutes(25),
            ],
            [
                'user_email' => 'budi@gmail.com',
                'title' => 'Evakuasi Darurat Cimanuk',
                'body' => 'Siaga 1 Sungai Cimanuk! Warga di bantaran sungai Lohbener dan Jatibarang diminta segera mengungsi ke posko terdekat.',
                'type' => 'emergency_alert',
                'is_read' => false,
                'sent_at' => Carbon::now()->subMinutes(10),
            ],
        ];

        foreach ($notifications as $notif) {
            $user = $users[$notif['user_email']] ?? null;
            if ($user) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => $notif['title'],
                    'body' => $notif['body'],
                    'type' => $notif['type'],
                    'is_read' => $notif['is_read'],
                    'sent_at' => $notif['sent_at'],
                ]);
            }
        }

        // 6. Seed Shelters
        $shelters = [
            [
                'name' => 'Posko Pengungsian Balai Desa Lohbener',
                'latitude' => -6.40690000,
                'longitude' => 108.28250000,
                'address' => 'Jl. Raya Lohbener No. 45, Desa Lohbener, Kec. Lohbener',
                'capacity' => 250,
                'current_occupancy' => 85,
                'status' => 'active',
                'contact' => '081299988877 (Call Center Desa)',
            ],
            [
                'name' => 'Aula Kantor Kecamatan Jatibarang',
                'latitude' => -6.47440000,
                'longitude' => 108.31010000,
                'address' => 'Jl. Mayor Dasuki No. 1, Jatibarang, Kec. Jatibarang',
                'capacity' => 500,
                'current_occupancy' => 495,
                'status' => 'full',
                'contact' => '087711223344 (BPBD Posko Jatibarang)',
            ],
            [
                'name' => 'Gedung Serbaguna Haurgeulis',
                'latitude' => -6.45890000,
                'longitude' => 107.96220000,
                'address' => 'Jl. Siliwangi No. 10, Haurgeulis, Kec. Haurgeulis',
                'capacity' => 300,
                'current_occupancy' => 0,
                'status' => 'active',
                'contact' => '085244556677 (Satpol PP Kecamatan)',
            ],
            [
                'name' => 'Masjid Raya Al-Hurriyyah Patrol',
                'latitude' => -6.29740000,
                'longitude' => 107.99470000,
                'address' => 'Jalan Pantura Patrol, Desa Patrol, Kec. Patrol',
                'capacity' => 400,
                'current_occupancy' => 30,
                'status' => 'active',
                'contact' => '089911223344 (DKM Masjid)',
            ],
        ];

        foreach ($shelters as $shelter) {
            Shelter::create($shelter);
        }

        // 7. Seed Disaster Zones
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';

        $disasterZones = [
            [
                'name' => 'Zona Rawan Luapan Cimanuk Lohbener',
                'polygon' => $isSqlite 
                    ? 'POLYGON((108.2750 -6.4000, 108.2950 -6.4000, 108.2950 -6.4200, 108.2750 -6.4200, 108.2750 -6.4000))'
                    : DB::raw("ST_GeomFromText('POLYGON((108.2750 -6.4000, 108.2950 -6.4000, 108.2950 -6.4200, 108.2750 -6.4200, 108.2750 -6.4000))')"),
                'risk_level' => 'high',
                'description' => 'Daerah aliran sungai Cimanuk di kecamatan Lohbener yang rawan terkena luapan air saat debit hulu meningkat tajam.',
            ],
            [
                'name' => 'Kawasan Tanggul Kritis Jatibarang',
                'polygon' => $isSqlite
                    ? 'POLYGON((108.3000 -6.4650, 108.3200 -6.4650, 108.3200 -6.4850, 108.3000 -6.4850, 108.3000 -6.4650))'
                    : DB::raw("ST_GeomFromText('POLYGON((108.3000 -6.4650, 108.3200 -6.4650, 108.3200 -6.4850, 108.3000 -6.4850, 108.3000 -6.4650))')"),
                'risk_level' => 'critical',
                'description' => 'Zona sekitar kelokan sungai Cimanuk Jatibarang dengan konstruksi tanggul tanah yang rentan rembes dan jebol.',
            ],
            [
                'name' => 'Zona Rob Cantigi',
                'polygon' => $isSqlite
                    ? 'POLYGON((108.2500 -6.2800, 108.2750 -6.2800, 108.2750 -6.3100, 108.2500 -6.3100, 108.2500 -6.2800))'
                    : DB::raw("ST_GeomFromText('POLYGON((108.2500 -6.2800, 108.2750 -6.2800, 108.2750 -6.3100, 108.2500 -6.3100, 108.2500 -6.2800))')"),
                'risk_level' => 'medium',
                'description' => 'Area pesisir Cantigi yang sering tergenang air pasang laut (banjir rob), terutama saat bulan purnama.',
            ],
        ];

        foreach ($disasterZones as $zone) {
            DisasterZone::create($zone);
        }

        // 8. Seed App Info
        AppInfo::create([
            'app_name' => 'Kaliwedi - Flood Early Warning System',
            'description' => 'Aplikasi Peringatan Dini dan Pelaporan Bencana Banjir Kabupaten Indramayu. Memudahkan masyarakat mendapatkan informasi shelter, zona rawan, serta mengirim laporan bencana secara real-time.',
            'developer' => 'Badan Penanggulangan Bencana Daerah (BPBD) Kabupaten Indramayu',
            'website' => 'https://bpbd.indramayukab.go.id',
            'support_email' => 'hubungi@bpbd.indramayukab.go.id',
            'privacy_policy_url' => 'https://bpbd.indramayukab.go.id/privacy-policy',
            'privacy_policy_content' => 'Kaliwedi berkomitmen melindungi privasi seluruh penggunanya.

Data yang dikumpulkan: Nama, email, nomor telepon, dan lokasi yang Anda berikan saat membuat laporan.

Penggunaan data: Data digunakan semata-mata untuk memproses laporan warga dan mengirimkan notifikasi terkait status laporan Anda.

Keamanan data: Data Anda disimpan dengan enkripsi dan tidak dibagikan kepada pihak ketiga tanpa persetujuan Anda, kecuali diwajibkan hukum.

Hak pengguna: Anda berhak mengakses, memperbarui, atau menghapus data pribadi Anda kapan saja melalui menu Edit Profil.

Kebijakan ini berlaku sejak 15 Januari 2026 dan dapat diperbarui sewaktu-waktu.',
            'terms_url' => 'https://bpbd.indramayukab.go.id/terms-of-service',
            'terms_content' => 'Dengan menggunakan Kaliwedi, Anda menyetujui syarat berikut:

Penggunaan yang diperbolehkan: Aplikasi ini hanya untuk melaporkan kondisi banjir nyata di wilayah Kabupaten Indramayu. Laporan harus berisi informasi yang akurat dan dapat dipertanggungjawabkan.

Larangan: Dilarang menyampaikan laporan palsu, konten provokatif, SARA, atau informasi yang menyesatkan. Pelanggaran dapat mengakibatkan penonaktifan akun.

Tanggung jawab pemerintah: Pemerintah Kabupaten Indramayu berkomitmen menindaklanjuti laporan dalam 3–7 hari kerja sesuai kategori dan prioritas.

Perubahan ketentuan: Ketentuan dapat diperbarui sewaktu-waktu. Pengguna akan diberitahu melalui notifikasi aplikasi.

Berlaku sejak 15 Januari 2026.',
        ]);

        // 9. Seed Likes and Comments
        $allReports = Report::all();
        $allUsers = User::all();

        foreach ($allReports as $report) {
            // Seed 2-4 random likes
            $likedUsers = $allUsers->random(rand(2, 4));
            foreach ($likedUsers as $u) {
                ReportLike::create([
                    'user_id' => $u->id,
                    'report_id' => $report->id,
                ]);
            }

            // Seed 1-2 random comments
            $commentedUsers = $allUsers->random(rand(1, 2));
            $sampleComments = [
                'Tetap waspada semuanya!',
                'Apakah bantuan sudah tiba?',
                'Lokasi terpantau hujan gerimis sekarang.',
                'Terima kasih atas laporannya.',
                'Semoga lekas surut airnya.',
            ];
            foreach ($commentedUsers as $u) {
                ReportComment::create([
                    'user_id' => $u->id,
                    'report_id' => $report->id,
                    'comment' => $sampleComments[array_rand($sampleComments)],
                ]);
            }
        }
    }
}
