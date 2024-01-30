<?php

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'title' => 'cash',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'coin',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'diamond',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        Asset::query()->insert($data);
    }
}
