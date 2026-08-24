<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use App\Models\Rpp;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles and Permissions
        $this->call(RoleAndPermissionSeeder::class);

        // 2. Seed Default Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@kadu.com'],
            [
                'name' => 'Administrator KADU',
                'username' => 'admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // 3. Seed Default User (Guru Vokasi)
        $user = User::updateOrCreate(
            ['email' => 'user@kadu.com'],
            [
                'name' => 'Guru Vokasi (User)',
                'username' => 'user',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $user->assignRole('user');

        $budi = User::updateOrCreate(
            ['email' => 'budi@kadu.com'],
            [
                'name' => 'Budi Santoso, S.Pd',
                'username' => 'budi',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $budi->assignRole('user');

        // Assign 'user' role to any existing user in database without a role
        User::doesntHave('roles')->get()->each(function ($u) {
            $u->assignRole('user');
        });

        // Ensure all admin users have email_verified_at set
        User::role('admin')->update(['email_verified_at' => now()]);

        // 4. Seed Initial Sample RPP Vokasi
        Rpp::updateOrCreate(
            ['title' => 'RPP Matematika Vokasi TKR - Persamaan Linier & Rasio Presisi Otomotif'],
            [
                'user_id' => $user->id,
                'mata_pelajaran' => 'Matematika Vokasi',
                'kelas_semester' => 'X / Ganjil',
                'alokasi_waktu' => '3 JP (3 x 45 Menit)',
                'jurusan_smk' => 'Teknik Kendaraan Ringan (TKR)',
                'capaian_pembelajaran' => 'Peserta didik mampu menerapkan sistem persamaan linier dan kalkulasi rasio presisi untuk memecahkan masalah teknis otomotif.',
                'gaya_belajar' => ['Visual', 'Kinestetik'],
                'karakteristik_fisik' => 'Non-Inklusi (Reguler)',
                'model_pembelajaran' => 'Project-Based Learning (PBL)',
                'metode_pembelajaran' => 'Diskusi Kelompok, Simulasi, dan Praktik Bengkel',
                'kemitraan_dudi' => 'Industri Pasangan DU/DI (PT. Astra Otoparts) & Guru Tamu Praktisi',
                'ruang_fisik' => 'Bengkel Otomotif / Ruang Teori SMK',
                'ruang_virtual' => 'LMS Google Classroom & WhatsApp Group Class',
                'software_digital' => 'Platform Merdeka Mengajar (PMM), Simulator Engine Scan, Canva',
                'dimensi_profil' => ['Bernalar Kritis (Critical Thinking)', 'Kreatif & Inovatif (Creativity)', 'Gotong Royong & Kolaboratif (Collaboration)', 'Kompeten & Berstandar Industri (Vokasi)'],
                'content_rpp' => "I. KERANGKA PEMBELAJARAN & EKOSISTEM\n- Model: Project-Based Learning (PBL)\n- Industri: PT. Astra Otoparts\n\nII. DIMENSI PROFIL LULUSAN\n- Bernalar Kritis, Kreatif, & Berstandar Industri\n\nIII. TUJUAN PEMBELAJARAN (MEANINGFUL LEARNING)\n- Memahami kalkulasi rasio presisi dalam penyetelan celah katup mesin kendaraan.\n\nIV. STIMULUS LITERASI & NUMERASI TERAPAN\n- Stimulus Literasi: Panduan service manual kendaraan ringan.\n- Stimulus Numerasi: Pengukuran micrometer & perbandingan rasio kompresi.\n\nV. LANGKAH-LANGKAH PEMBELAJARAN (DEEP LEARNING)\nA. Kegiatan Awal: Langkah Berkesadaran (Mindful), Kebermaknaan (Meaningful), Menggembirakan (Joyful).\nB. Kegiatan Inti: Pemahaman Konsep, Aplikasi Praktik Bengkel, Refleksi Metakognisi.\nC. Kegiatan Penutup.\n\nVI. ASESMEN PEMBELAJARAN TERINTEGRASI\n- Asesmen Formatif & Sumatif.",
                'content_media' => "1. Lembar Kerja Peserta Didik (LKPD) Otomotif\n2. Slide Presentation Rasio Kompresi\n3. Simulation App Engine Scan",
                'content_video_script' => "Prompt AI: 'Buatkan video animasi 3D langkah-langkah pengukuran rasio presisi celah katup otomotif SMK'.",
                'content_materi' => "Ringkasan Materi Literasi & Numerasi Terapan SMK: Rumus persamaan linier untuk pengukuran rasio torsi dan tekanan silinder.",
                'status' => 'published',
            ]
        );

        // 5. Seed Default Token Packages
        \App\Models\TokenPackage::updateOrCreate(
            ['name' => 'Paket Starter'],
            [
                'tokens' => 10,
                'price' => 50000,
                'description' => '10 Token RPP Deep Learning Vokasi. Cocok untuk uji coba pembuatan modul ajar guru SMK.',
                'is_active' => true,
            ]
        );

        \App\Models\TokenPackage::updateOrCreate(
            ['name' => 'Paket Professional'],
            [
                'tokens' => 50,
                'price' => 200000,
                'description' => '50 Token RPP. Hemat & Terfavorit untuk persiapan 1 semester penuh guru SMK.',
                'is_active' => true,
            ]
        );

        \App\Models\TokenPackage::updateOrCreate(
            ['name' => 'Paket Ultimate Sekolah'],
            [
                'tokens' => 200,
                'price' => 650000,
                'description' => '200 Token RPP. Pilihan terbaik untuk MGMP, tim Kurikulum, atau kelompok guru SMK.',
                'is_active' => true,
            ]
        );

        // 6. Seed Settings & Pages
        $this->call(SettingSeeder::class);
        $this->call(PageAndSectionSeeder::class);
    }
}
