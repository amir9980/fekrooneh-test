<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
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


        // create admin user and its wallets:
        $adminUser = User::query()->create([
            'name'=>'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt(123456),
            'is_admin' => true
        ]);

        $adminUser->wallets()->create([
            'asset_id' => $cash->id
        ]);
        $adminUser->wallets()->create([
            'asset_id' => $coin->id
        ]);
        $adminUser->wallets()->create([
            'asset_id' => $diamond->id
        ]);


        // create cash user and its wallets:
        $cashUser = User::query()->create([
            'name'=>'cash',
            'email' => 'cash@gmail.com',
            'password' => bcrypt(123456),
        ]);

        $cashUser->wallets()->create([
            'asset_id' => $cash->id,
            'value' => 100
        ]);
        $cashUser->wallets()->create([
            'asset_id' => $coin->id
        ]);
        $cashUser->wallets()->create([
            'asset_id' => $diamond->id
        ]);


        // create coin user and its wallets:
        $coinUser = User::query()->create([
            'name'=>'coin',
            'email' => 'coin@gmail.com',
            'password' => bcrypt(123456),
        ]);

        $coinUser->wallets()->create([
            'asset_id' => $cash->id
        ]);
        $coinUser->wallets()->create([
            'asset_id' => $coin->id,
            'value' => 100
        ]);
        $coinUser->wallets()->create([
            'asset_id' => $diamond->id
        ]);


        // create diamond user and its wallets:
        $diamondUser = User::query()->create([
            'name'=>'diamond',
            'email' => 'diamond@gmail.com',
            'password' => bcrypt(123456),
        ]);

        $diamondUser->wallets()->create([
            'asset_id' => $cash->id
        ]);
        $diamondUser->wallets()->create([
            'asset_id' => $coin->id
        ]);
        $diamondUser->wallets()->create([
            'asset_id' => $diamond->id,
            'value' => 100
        ]);


    }
}
