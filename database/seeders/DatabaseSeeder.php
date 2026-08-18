<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Post;
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

        // 2. Seed Default Users and Assign Roles
        $admin = User::updateOrCreate(
            ['email' => 'admin@xseller.id'],
            [
                'name' => 'President Director (Admin)',
                'username' => 'admin',
                'password' => bcrypt('password'),
                'left_count' => 3,
                'right_count' => 2,
                'left_points' => 1,
                'right_points' => 0,
                'package_name' => 'Ultimate',
            ]
        );
        $admin->assignRole('admin');

        // Level 2 (Children of Admin)
        $budi = User::updateOrCreate(
            ['email' => 'budi@xseller.id'],
            [
                'name' => 'Budi Santoso',
                'username' => 'budi',
                'password' => bcrypt('password'),
                'parent_id' => $admin->id,
                'position' => 'left',
                'left_count' => 1,
                'right_count' => 1,
                'left_points' => 0,
                'right_points' => 0,
                'package_name' => 'Pro',
            ]
        );
        $budi->assignRole('client');

        $siti = User::updateOrCreate(
            ['email' => 'siti@xseller.id'],
            [
                'name' => 'Siti Rahma',
                'username' => 'siti',
                'password' => bcrypt('password'),
                'parent_id' => $admin->id,
                'position' => 'right',
                'left_count' => 1,
                'right_count' => 0,
                'left_points' => 0,
                'right_points' => 0,
                'package_name' => 'Medium',
            ]
        );
        $siti->assignRole('client');

        // Level 3 (Grandchildren)
        $dewi = User::updateOrCreate(
            ['email' => 'dewi@xseller.id'],
            [
                'name' => 'Dewi Lestari',
                'username' => 'dewi',
                'password' => bcrypt('password'),
                'parent_id' => $budi->id,
                'position' => 'left',
                'left_count' => 0,
                'right_count' => 0,
                'left_points' => 0,
                'right_points' => 0,
                'package_name' => 'Basic',
            ]
        );
        $dewi->assignRole('client');

        $eko = User::updateOrCreate(
            ['email' => 'eko@xseller.id'],
            [
                'name' => 'Eko Prasetyo',
                'username' => 'eko',
                'password' => bcrypt('password'),
                'parent_id' => $budi->id,
                'position' => 'right',
                'left_count' => 0,
                'right_count' => 0,
                'left_points' => 0,
                'right_points' => 0,
                'package_name' => 'Basic',
            ]
        );
        $eko->assignRole('client');

        $fajar = User::updateOrCreate(
            ['email' => 'fajar@xseller.id'],
            [
                'name' => 'Fajar Hidayat',
                'username' => 'fajar',
                'password' => bcrypt('password'),
                'parent_id' => $siti->id,
                'position' => 'left',
                'left_count' => 0,
                'right_count' => 0,
                'left_points' => 0,
                'right_points' => 0,
                'package_name' => 'Starter',
            ]
        );
        $fajar->assignRole('client');

        // 2b. Seed Active Vouchers for Admin
        \App\Models\Voucher::updateOrCreate(
            ['code' => 'VCH-2026-XSEL-8921'],
            [
                'user_id' => $admin->id,
                'package_name' => 'Basic',
                'status' => 'active',
            ]
        );

        \App\Models\Voucher::updateOrCreate(
            ['code' => 'VCH-2026-XSEL-4412'],
            [
                'user_id' => $admin->id,
                'package_name' => 'Ultimate',
                'status' => 'active',
            ]
        );

        // 3. Seed Settings
        $this->call(SettingSeeder::class);

        // 4. Seed Pages and Sections
        $this->call(PageAndSectionSeeder::class);

        // 5. Seed Categories & Posts
        $general = Category::updateOrCreate(
            ['slug' => 'general'],
            ['name' => 'General']
        );

        $tech = Category::updateOrCreate(
            ['slug' => 'technology'],
            ['name' => 'Technology']
        );

        Post::updateOrCreate(
            ['slug' => 'selamat-datang-di-boilerplate-cms-baru-anda'],
            [
                'category_id' => $general->id,
                'title' => 'Selamat Datang di Boilerplate CMS Baru Anda',
                'content' => 'Ini adalah postingan pertama di CMS Anda. Anda dapat mengedit, menghapus, atau membuat postingan baru melalui dashboard admin dengan sangat mudah.',
                'image' => null,
                'status' => 'published',
                'is_featured' => true
            ]
        );

        Post::updateOrCreate(
            ['slug' => 'mengapa-laravel-11-dan-vue-3-sangat-powerful'],
            [
                'category_id' => $tech->id,
                'title' => 'Mengapa Laravel 11 dan Vue 3 Sangat Powerful?',
                'content' => 'Kombinasi Laravel dan Vue 3 yang dihubungkan oleh Inertia.js menciptakan pengalaman pengembangan Single Page Application (SPA) murni tanpa overhead penulisan API terpisah. Hal ini mempercepat siklus development secara signifikan.',
                'image' => null,
                'status' => 'published',
                'is_featured' => false
            ]
        );
    }
}
