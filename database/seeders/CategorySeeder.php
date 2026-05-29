<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Carbon;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
          ['name' =>  'Alcoholic'],
          ['name' =>  'Beer'],
          ['name' =>  'Bitters'],
          ['name' =>  'Enerygy Drink'],
          ['name' =>  'Juice'],
          ['name' =>  'Milk and Dairy Alternatives'],
          ['name' =>  'Soft Drink'],
          ['name' =>  'Spirits'],
          ['name' =>  'Sports and Fitness Drinks'],
          ['name' =>  'Tea and Coffee'],
          ['name' =>  'Water'],
          ['name' => 'Wine']
        ];

    $now = Carbon::now();
    $rows = array_map(function ($c) use ($now) {
      $c['created_at'] = $now;
      $c['updated_at'] = $now;
      return $c;
    }, $categories);

    Category::insert($rows);
    }
}
