<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Discount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Kreiraj Admin korisnika (updateOrCreate sprečava grešku ako seeder pokreneš opet)
        User::updateOrCreate(
            ['email' => 'admin@example.com'], // Proverava po emailu
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
            ]
        );

        // 2. Kreiraj testni popust
        Discount::updateOrCreate(
            ['name' => 'Akcija 10%'],
            [
                'percentage' => 10,
                'is_active' => true,
            ]
        );
        
        // 3. Pozovi ostale seedere
        $this->call([
            SettingSeeder::class, // Ovo je ključno za Logo, Banner i Boje
        ]);

        echo "\n------------------------------------------------\n";
        echo " Seedovanje uspešno: Admin, Popust i Settings! \n";
        echo "------------------------------------------------\n";
    }
}