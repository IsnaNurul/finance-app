<?php

namespace App\Http\Controllers;

use App\Services\ChartOfAccountService;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChartOfAccountController extends Controller
{
    protected $service;
    protected $categoryService;

    public function __construct(ChartOfAccountService $service, CategoryService $categoryService)
    {
        $this->service = $service;
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        $filterCategory = $request->get('filter_category');
        
        $query = $this->service->getAll();
        
        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }
        
        // Apply category filter
        if ($filterCategory) {
            $query->where('category_id', $filterCategory);
        }
        
        $coas = $query->paginate($perPage);
        $categories = $this->categoryService->getAll()->get();
        
        return Inertia::render('ChartOfAccounts/Index', [
            'coas' => $coas,
            'categories' => $categories
        ]);
    }

    public function create()
    {
        $categories = $this->categoryService->getAll()->get();
        return Inertia::render('ChartOfAccounts/Create', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:chart_of_accounts',
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $this->service->create($data);

        return to_route('chart-of-accounts.index')->with('success', 'Chart of Account created successfully.');
    }

    public function edit($id)
    {
        $coa = $this->service->find($id);
        $categories = $this->categoryService->getAll()->get();
        return Inertia::render('ChartOfAccounts/Edit', [
            'coa' => $coa,
            'categories' => $categories
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:chart_of_accounts,code,' . $id,
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $this->service->update($id, $data);

        return to_route('chart-of-accounts.index')->with('success', 'Chart of Account updated successfully.');
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return to_route('chart-of-accounts.index')->with('success', 'Chart of Account deleted successfully.');
    }
}
