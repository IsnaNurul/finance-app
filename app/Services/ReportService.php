<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getProfitLossReport($year = null)
    {
        $year = $year ?? date('Y');

        $transactions = Transaction::select(
                'categories.name as category',
                DB::raw('MONTH(transactions.date) as month'),
                DB::raw('SUM(transactions.credit) as total_credit'),
                DB::raw('SUM(transactions.debit) as total_debit')
            )
            ->join('chart_of_accounts', 'transactions.chart_of_account_id', '=', 'chart_of_accounts.id')
            ->join('categories', 'chart_of_accounts.category_id', '=', 'categories.id')
            ->whereYear('transactions.date', $year)
            ->groupBy('categories.name', 'month')
            ->orderBy('categories.name')
            ->get();

        $months = range(1, 12);
        $report = [];
        $totalIncome = array_fill(1, 12, 0);
        $totalExpense = array_fill(1, 12, 0);

        foreach ($transactions as $trx) {
            $category = $trx->category;

            if (!isset($report[$category])) {
                $report[$category] = array_fill(1, 12, 0);
                $report[$category]['total'] = 0;
            }

            // income = credit, expense = debit
            $amount = $trx->total_credit > 0 ? $trx->total_credit : ($trx->total_debit > 0 ? $trx->total_debit : 0);

            $report[$category][$trx->month] += $amount;
            $report[$category]['total'] += $amount;

            if ($trx->total_credit > 0) {
                $totalIncome[$trx->month] += $trx->total_credit;
            } else {
                $totalExpense[$trx->month] += $trx->total_debit;
            }
        }

        // Hitung total per bulan & net income
        $summary = [
            'income'  => $totalIncome,
            'expense' => $totalExpense,
            'net'     => []
        ];

        foreach ($months as $m) {
            $summary['net'][$m] = $totalIncome[$m] - $totalExpense[$m];
        }

        $summary['income']['total']  = array_sum($totalIncome);
        $summary['expense']['total'] = array_sum($totalExpense);
        $summary['net']['total']     = $summary['income']['total'] - $summary['expense']['total'];

        return [
            'year' => $year,
            'months' => $months,
            'data' => $report,
            'summary' => $summary
        ];
    }
}
