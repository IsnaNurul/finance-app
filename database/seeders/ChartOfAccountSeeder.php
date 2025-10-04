<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChartOfAccount;
use App\Models\Category;

class ChartOfAccountSeeder extends Seeder
{
    public function run(): void
    {
        $coas = [
            ['code' => '401', 'name' => 'Gaji Karyawan', 'category' => 'Salary'],
            ['code' => '402', 'name' => 'Gaji Ketua MPR', 'category' => 'Salary'],
            ['code' => '403', 'name' => 'Profit Trading', 'category' => 'Other Income'],
            ['code' => '601', 'name' => 'Biaya Sekolah', 'category' => 'Family Expense'],
            ['code' => '602', 'name' => 'Bensin', 'category' => 'Transport Expense'],
            ['code' => '603', 'name' => 'Parkir', 'category' => 'Transport Expense'],
            ['code' => '604', 'name' => 'Makan Siang', 'category' => 'Meal Expense'],
            ['code' => '605', 'name' => 'Makana Pokok Bulanan', 'category' => 'Meal Expense'],
        ];

        foreach ($coas as $coa) {
            $category = Category::where('name', $coa['category'])->first();
            ChartOfAccount::create([
                'code' => $coa['code'],
                'name' => $coa['name'],
                'category_id' => $category->id,
            ]);
        }
    }
}
