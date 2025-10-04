<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\ChartOfAccount;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $year = now()->year;
        $currentMonth = now()->month;

        // Data transaksi yang lebih banyak dan bervariasi
        $transactions = [
            // === INCOME (CREDIT) ===
            // Gaji dan Pendapatan
            ['date' => Carbon::create($year, $currentMonth, 1), 'coa_code' => '401', 'description' => 'Gaji Karyawan Bulan ' . $currentMonth, 'debit' => 0, 'credit' => 5000000],
            ['date' => Carbon::create($year, $currentMonth, 1), 'coa_code' => '402', 'description' => 'Gaji Ketua MPR', 'debit' => 0, 'credit' => 7000000],
            ['date' => Carbon::create($year, $currentMonth, 5), 'coa_code' => '403', 'description' => 'Profit Trading Saham', 'debit' => 0, 'credit' => 1500000],
            ['date' => Carbon::create($year, $currentMonth, 10), 'coa_code' => '403', 'description' => 'Bonus Proyek', 'debit' => 0, 'credit' => 2000000],
            
            // === EXPENSES (DEBIT) ===
            // Biaya Keluarga
            ['date' => Carbon::create($year, $currentMonth, 12), 'coa_code' => '601', 'description' => 'Seragam Sekolah', 'debit' => 400000, 'credit' => 0],
            ['date' => Carbon::create($year, $currentMonth, 20), 'coa_code' => '601', 'description' => 'Uang Jajan Anak', 'debit' => 200000, 'credit' => 0],
            
            // Biaya Transport
            ['date' => Carbon::create($year, $currentMonth, 1), 'coa_code' => '602', 'description' => 'Bensin Motor', 'debit' => 100000, 'credit' => 0],
            ['date' => Carbon::create($year, $currentMonth, 3), 'coa_code' => '602', 'description' => 'Bensin Mobil', 'debit' => 250000, 'credit' => 0],
            ['date' => Carbon::create($year, $currentMonth, 5), 'coa_code' => '603', 'description' => 'Parkir Mall', 'debit' => 15000, 'credit' => 0],
            
            // Biaya Makan
            ['date' => Carbon::create($year, $currentMonth, 1), 'coa_code' => '604', 'description' => 'Makan Siang Kantor', 'debit' => 25000, 'credit' => 0],
            ['date' => Carbon::create($year, $currentMonth, 2), 'coa_code' => '604', 'description' => 'Makan Siang Restoran', 'debit' => 45000, 'credit' => 0],
            ['date' => Carbon::create($year, $currentMonth, 3), 'coa_code' => '605', 'description' => 'Belanja Bulanan', 'debit' => 800000, 'credit' => 0],
            ['date' => Carbon::create($year, $currentMonth, 4), 'coa_code' => '604', 'description' => 'Makan Siang Client', 'debit' => 85000, 'credit' => 0],
        ];

        // Tambahkan transaksi untuk bulan-bulan sebelumnya
        for ($month = 1; $month < $currentMonth; $month++) {
            $monthTransactions = [
                // Income bulanan
                ['date' => Carbon::create($year, $month, 1), 'coa_code' => '401', 'description' => 'Gaji Karyawan Bulan ' . $month, 'debit' => 0, 'credit' => 5000000],
                ['date' => Carbon::create($year, $month, 1), 'coa_code' => '402', 'description' => 'Gaji Ketua MPR', 'debit' => 0, 'credit' => 7000000],
                ['date' => Carbon::create($year, $month, 15), 'coa_code' => '403', 'description' => 'Profit Trading Bulan ' . $month, 'debit' => 0, 'credit' => rand(500000, 2000000)],
                
                // Expense bulanan
                ['date' => Carbon::create($year, $month, 2), 'coa_code' => '601', 'description' => 'SPP Sekolah', 'debit' => 500000, 'credit' => 0],
                ['date' => Carbon::create($year, $month, 5), 'coa_code' => '602', 'description' => 'Bensin Bulanan', 'debit' => rand(200000, 500000), 'credit' => 0],
                ['date' => Carbon::create($year, $month, 10), 'coa_code' => '605', 'description' => 'Belanja Bulanan', 'debit' => rand(600000, 1000000), 'credit' => 0],
                ['date' => Carbon::create($year, $month, 20), 'coa_code' => '604', 'description' => 'Makan Siang Bulanan', 'debit' => rand(300000, 600000), 'credit' => 0],
            ];
            
            $transactions = array_merge($transactions, $monthTransactions);
        }

        // Buat transaksi
        foreach ($transactions as $trx) {
            $coa = ChartOfAccount::where('code', $trx['coa_code'])->first();
            if ($coa) {
                Transaction::create([
                    'date' => $trx['date'],
                    'chart_of_account_id' => $coa->id,
                    'description' => $trx['description'],
                    'debit' => $trx['debit'],
                    'credit' => $trx['credit'],
                ]);
            }
        }
    }
}
