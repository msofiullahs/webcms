<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@webcms.local'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create default language
        Language::firstOrCreate(
            ['code' => 'en'],
            [
                'name' => 'English',
                'native_name' => 'English',
                'is_active' => true,
                'is_default' => true,
                'direction' => 'ltr',
            ]
        );

        // Create default theme
        Theme::firstOrCreate(
            ['slug' => 'default'],
            [
                'name' => 'Default',
                'description' => 'Clean, minimal default theme',
                'is_active' => true,
                'config' => [
                    'colors' => ['primary' => '#3B82F6', 'secondary' => '#10B981'],
                    'fonts' => ['heading' => 'Inter', 'body' => 'Inter'],
                ],
            ]
        );

        // Create header menu
        $headerMenu = Menu::firstOrCreate(
            ['location' => 'header'],
            ['name' => 'Header Navigation']
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $headerMenu->id, 'url' => '/'],
            ['title' => ['en' => 'Home'], 'type' => 'custom', 'order' => 0]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $headerMenu->id, 'url' => '/posts'],
            ['title' => ['en' => 'Blog'], 'type' => 'custom', 'order' => 1]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $headerMenu->id, 'url' => '/contact'],
            ['title' => ['en' => 'Contact'], 'type' => 'custom', 'order' => 2]
        );

        // Create footer menu
        $footerMenu = Menu::firstOrCreate(
            ['location' => 'footer'],
            ['name' => 'Footer Navigation']
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $footerMenu->id, 'url' => '/'],
            ['title' => ['en' => 'Home'], 'type' => 'custom', 'order' => 0]
        );

        MenuItem::firstOrCreate(
            ['menu_id' => $footerMenu->id, 'url' => '/contact'],
            ['title' => ['en' => 'Contact'], 'type' => 'custom', 'order' => 1]
        );
    }
}
