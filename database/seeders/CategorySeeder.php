<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Category::create([
            'name' => 'Electronics',
            'slug' => Str::slug('Electronics'),
            'status' => true
        ]);

        Category::create([
            'name' => 'Fashion',
            'slug' => Str::slug('Fashion'),
            'status' => true
        ]);
    }
}
