<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Salary'],
            ['name' => 'Other Income'],
            ['name' => 'Family Expense'],
            ['name' => 'Transport Expense'],
            ['name' => 'Meal Expense'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
