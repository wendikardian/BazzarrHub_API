<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

    //    $now = now();
    //    DB::table('products')->insert([
    //           'name' => 'Product 1',
    //           'price' => 1000,
    //           'stock' => 10,
    //           'created_at' => $now,
    //           'updated_at' => $now,
    //      ]);
    //      DB::table('products')->insert([
    //               'name' => 'Product 2',
    //               'price' => 2000,
    //               'stock' => 20,
    //               'created_at' => $now,
    //               'updated_at' => $now,
    //         ]);
    Product::factory(100)->create();
    }
}
