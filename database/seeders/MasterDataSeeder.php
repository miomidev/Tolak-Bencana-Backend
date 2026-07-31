<?php

namespace Database\Seeders;

use App\Models\AlertLevel;
use App\Models\ReportCategory;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        ReportCategory::truncate();
        ReportCategory::insert([
            ['name' => 'Banjir Rob', 'icon' => 'water', 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Banjir Luapan Sungai', 'icon' => 'river', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Genangan', 'icon' => 'puddle', 'sort_order' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tembok / Tanggul Jebol', 'icon' => 'landmark', 'sort_order' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pohon Tumbang', 'icon' => 'tree', 'sort_order' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lainnya', 'icon' => 'circle-info', 'sort_order' => 6, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        AlertLevel::truncate();
        AlertLevel::insert([
            ['name' => 'Aman', 'color' => '#22C55E', 'min_height' => 0, 'max_height' => 30, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Waspada', 'color' => '#FACC15', 'min_height' => 31, 'max_height' => 60, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Siaga', 'color' => '#F97316', 'min_height' => 61, 'max_height' => 100, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Awas / Bahaya', 'color' => '#DC2626', 'min_height' => 101, 'max_height' => null, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
