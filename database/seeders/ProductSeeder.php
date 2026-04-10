<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       \App\Models\Product::create([
    'name' => 'Prvi Proizvod',
    'slug' => 'prvi-proizvod',
    'description' => 'Ovo je opis našeg prvog sjajnog proizvoda.',
    'price' => 1500.00,
    'stock' => 10
]);
    }
}
