<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use App\Services\ChartOfAccountService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    protected $service;
    protected $coaService;

    public function __construct(TransactionService $service, ChartOfAccountService $coaService)
    {
        $this->service = $service;
        $this->coaService = $coaService;
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        $filterCoa = $request->get('filter_coa');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        $query = $this->service->getAll();
        
        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('chartOfAccount', function($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Apply COA filter
        if ($filterCoa) {
            $query->where('chart_of_account_id', $filterCoa);
        }
        
        // Apply date range filter
        if ($dateFrom) {
            $query->where('date', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->where('date', '<=', $dateTo);
        }
        
        $transactions = $query->orderBy('date', 'desc')->paginate($perPage);
        $coas = $this->coaService->getAll()->get();
        
        return Inertia::render('Transactions/Index', [
            'transactions' => $transactions,
            'coas' => $coas,
            'filters' => [
                'search' => $search,
                'filter_coa' => $filterCoa,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ]
        ]);
    }

    public function create()
    {
        $coas = $this->coaService->getAll()->get();
        return Inertia::render('Transactions/Create', [
            'coas' => $coas
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'chart_of_account_id' => 'required|exists:chart_of_accounts,id',
            'description' => 'nullable|string',
            'debit' => 'numeric|min:0',
            'credit' => 'numeric|min:0',
        ]);

        $this->service->create($data);

        return to_route('transactions.index')->with('success', 'Transaction created successfully.');
    }

    public function edit($id)
    {
        $transaction = $this->service->find($id);
        $coas = $this->coaService->getAll()->get();
        return Inertia::render('Transactions/Edit', [
            'transaction' => $transaction,
            'coas' => $coas
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'chart_of_account_id' => 'required|exists:chart_of_accounts,id',
            'description' => 'nullable|string',
            'debit' => 'numeric|min:0',
            'credit' => 'numeric|min:0',
        ]);

        $this->service->update($id, $data);

        return to_route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return to_route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}
