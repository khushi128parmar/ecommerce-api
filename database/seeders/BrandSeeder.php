<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Brand::create([
            'name' => 'Apple',
            'slug' => Str::slug('Apple'),
            'status' => true
        ]);

        Brand::create([
            'name' => 'Samsung',
            'slug' => Str::slug('Samsung'),
            'status' => true
        ]);
    }
}
