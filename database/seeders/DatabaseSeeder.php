<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// import product tableseeder and store seeder
use Database\Seeders\ProductsTableSeeder;
use Database\Seeders\StoreSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // run seeder for product and store
        $this->call([
            ProductsTableSeeder::class,
            StoreSeeder::class,
        ]);
    }
}
