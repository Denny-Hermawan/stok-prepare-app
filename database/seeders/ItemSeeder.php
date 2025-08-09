<?php
// database/seeders/ItemSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Ayam Goreng',
            'Ayam Bakar',
            'Nasi Putih',
            'Es Teh Manis',
            'Kerupuk',
            'Sambal',
            'Lalapan',
            'Soto Ayam',
            'Gado-Gado',
            'Bakso'
        ];

        foreach ($items as $item) {
            Item::create(['nama_item' => $item]);
        }
    }
}
