<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SettingSeeder extends Seeder
{
    public function run()
    {
        // 1. KREIRANJE ADMINA
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('lozinka123'),
            ]
        );

        // 2. KREIRANJE MASTER PODEŠAVANJA
        Setting::updateOrCreate(['id' => 1], [
            'site_name' => 'PROSHOP.',
            'primary_color' => '#4f46e5',
            'secondary_color' => '#1e293b',
            
            // Top Bar
            'top_bar_active' => true,
            'top_bar_text' => 'Besplatna dostava za sve porudžbine preko 5.000 RSD! 🚚',
            'top_bar_bg_color' => '#4f46e5',
            'top_bar_text_color' => '#ffffff',

            // Baner (Usklađeno sa novom migracijom)
            'banner_active' => true,
            'banner_title' => 'Tvoj stil, tvoja pravila.',
            'banner_description' => 'Nabavi najnovije trendove i premium artikle uz brzu dostavu do tvojih vrata.',
            'banner_button_text' => 'Kupuj odmah',
            
            // Kontakt i Social
            'contact_email' => 'info@proshop.rs',
            'contact_phone' => '+381 60 123 456',
            'contact_address' => 'Knez Mihailova, Beograd',
            'fb_link' => 'https://facebook.com',
            'ig_link' => 'https://instagram.com',
            'tiktok_link' => 'https://tiktok.com',
        ]);
    }
}