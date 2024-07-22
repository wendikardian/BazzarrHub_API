<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $now = now();
        DB::table('store')->insert([
            'name' => 'store 1',
            'phone_number' => '9123123123',
            'address' => 'address 1',
            'created_at' => $now,
            'updated_at' => $now,
       ]);

         DB::table('store')->insert([
                'name' => 'store 2',
                'phone_number' => '9123123124',
                'address' => 'address 2',
                'created_at' => $now,
                'updated_at' => $now,
         ]);
    }
}
