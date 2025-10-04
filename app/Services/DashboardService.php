<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\ChartOfAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getDashboardData()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $currentYear = Carbon::now()->startOfYear();

        return [
            'summary' => $this->getSummaryStats($currentMonth, $lastMonth),
            'monthly_transactions' => $this->getMonthlyTransactions($currentYear),
            'top_categories' => $this->getTopCategories($currentMonth),
            'recent_transactions' => $this->getRecentTransactions(),
            'account_balances' => $this->getAccountBalances(),
        ];
    }

    private function getSummaryStats($currentMonth, $lastMonth)
    {
        // Total income bulan ini (kode 4xx adalah income)
        $currentIncome = Transaction::whereHas('chartOfAccount', function($query) {
            $query->where('code', 'like', '4%');
        })
        ->where('date', '>=', $currentMonth)
        ->sum('credit');

        // Total expense bulan ini (kode 6xx adalah expense)
        $currentExpense = Transaction::whereHas('chartOfAccount', function($query) {
            $query->where('code', 'like', '6%');
        })
        ->where('date', '>=', $currentMonth)
        ->sum('debit');

        // Total income bulan lalu
        $lastIncome = Transaction::whereHas('chartOfAccount', function($query) {
            $query->where('code', 'like', '4%');
        })
        ->whereBetween('date', [$lastMonth, $currentMonth->copy()->subDay()])
        ->sum('credit');

        // Total expense bulan lalu
        $lastExpense = Transaction::whereHas('chartOfAccount', function($query) {
            $query->where('code', 'like', '6%');
        })
        ->whereBetween('date', [$lastMonth, $currentMonth->copy()->subDay()])
        ->sum('debit');

        // Hitung persentase perubahan
        $incomeChange = $lastIncome > 0 ? (($currentIncome - $lastIncome) / $lastIncome) * 100 : 0;
        $expenseChange = $lastExpense > 0 ? (($currentExpense - $lastExpense) / $lastExpense) * 100 : 0;

        return [
            'current_income' => $currentIncome,
            'current_expense' => $currentExpense,
            'current_profit' => $currentIncome - $currentExpense,
            'income_change' => round($incomeChange, 2),
            'expense_change' => round($expenseChange, 2),
            'total_transactions' => Transaction::where('date', '>=', $currentMonth)->count(),
        ];
    }

    private function getMonthlyTransactions($currentYear)
    {
        return Transaction::select(
            DB::raw('MONTH(date) as month'),
            DB::raw('SUM(CASE WHEN chart_of_accounts.code LIKE "4%" THEN credit ELSE 0 END) as income'),
            DB::raw('SUM(CASE WHEN chart_of_accounts.code LIKE "6%" THEN debit ELSE 0 END) as expense')
        )
        ->join('chart_of_accounts', 'transactions.chart_of_account_id', '=', 'chart_of_accounts.id')
        ->where('date', '>=', $currentYear)
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->map(function($item) {
            return [
                'month' => $item->month,
                'month_name' => Carbon::create()->month($item->month)->format('M'),
                'income' => (float) $item->income,
                'expense' => (float) $item->expense,
                'profit' => (float) $item->income - (float) $item->expense,
            ];
        });
    }

    private function getTopCategories($currentMonth)
    {
        return Transaction::select(
            'categories.name',
            DB::raw('SUM(transactions.debit) as total_expense'),
            DB::raw('COUNT(transactions.id) as transaction_count')
        )
        ->join('chart_of_accounts', 'transactions.chart_of_account_id', '=', 'chart_of_accounts.id')
        ->join('categories', 'chart_of_accounts.category_id', '=', 'categories.id')
        ->where('transactions.date', '>=', $currentMonth)
        ->where('chart_of_accounts.code', 'like', '6%')
        ->groupBy('categories.id', 'categories.name')
        ->orderBy('total_expense', 'desc')
        ->limit(5)
        ->get();
    }

    private function getRecentTransactions()
    {
        return Transaction::with(['chartOfAccount.category'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($transaction) {
                $accountCode = $transaction->chartOfAccount->code;
                $type = 'asset'; // default
                if (str_starts_with($accountCode, '4')) {
                    $type = 'income';
                } elseif (str_starts_with($accountCode, '6')) {
                    $type = 'expense';
                }
                
                return [
                    'id' => $transaction->id,
                    'date' => $transaction->date,
                    'description' => $transaction->description,
                    'account_name' => $transaction->chartOfAccount->name,
                    'category_name' => $transaction->chartOfAccount->category->name ?? 'N/A',
                    'debit' => $transaction->debit,
                    'credit' => $transaction->credit,
                    'type' => $type,
                ];
            });
    }

    private function getAccountBalances()
    {
        return ChartOfAccount::with('category')
            ->get()
            ->map(function($account) {
                $balance = Transaction::where('chart_of_account_id', $account->id)
                    ->selectRaw('SUM(credit) - SUM(debit) as balance')
                    ->value('balance') ?? 0;

                $accountCode = $account->code;
                $type = 'asset'; // default
                if (str_starts_with($accountCode, '4')) {
                    $type = 'income';
                } elseif (str_starts_with($accountCode, '6')) {
                    $type = 'expense';
                }

                return [
                    'id' => $account->id,
                    'name' => $account->name,
                    'type' => $type,
                    'category_name' => $account->category->name ?? 'N/A',
                    'balance' => (float) $balance,
                ];
            })
            ->sortByDesc('balance')
            ->take(10);
    }
}