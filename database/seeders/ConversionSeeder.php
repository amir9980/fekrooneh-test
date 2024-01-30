<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Conversion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConversionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // get assets:
        $assets = Asset::all();
        $cash = $assets->where('title', 'cash')->first();
        $coin = $assets->where('title', 'coin')->first();
        $diamond = $assets->where('title', 'diamond')->first();

        // cash-coin conversion:
        Conversion::query()->create([
           'numerator_id'=>$cash->id,
           'denominator_id'=>$coin->id,
           'ratio'=>0.02,
           'fee'=>5
        ]);
        Conversion::query()->create([
           'numerator_id'=>$coin->id,
           'denominator_id'=>$cash->id,
           'ratio'=>50,
           'fee'=>0.01
        ]);

        // cash-diamond conversion:
        Conversion::query()->create([
           'numerator_id'=>$cash->id,
           'denominator_id'=>$diamond->id,
           'ratio'=>0.01,
           'fee'=>5
        ]);
        Conversion::query()->create([
           'numerator_id'=>$diamond->id,
           'denominator_id'=>$cash->id,
           'ratio'=>100,
           'fee'=>0.01
        ]);

        // coin-diamond conversion:
        Conversion::query()->create([
           'numerator_id'=>$coin->id,
           'denominator_id'=>$diamond->id,
           'ratio'=>0.5,
           'fee'=>0.5
        ]);
        Conversion::query()->create([
           'numerator_id'=>$diamond->id,
           'denominator_id'=>$coin->id,
           'ratio'=>2,
           'fee'=>0.01
        ]);
    }
}
