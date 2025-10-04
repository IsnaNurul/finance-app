<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    protected $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        
        $query = $this->service->getAll();
        
        // Apply search filter
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        $categories = $query->paginate($perPage);
        
        return Inertia::render('Categories/Index', [
            'categories' => $categories
        ]);
    }

    public function create()
    {
        return Inertia::render('Categories/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:categories'
        ]);

        $this->service->create($data);

        return to_route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = $this->service->find($id);
        return Inertia::render('Categories/Edit', [
            'category' => $category
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:categories,name,' . $id
        ]);

        $this->service->update($id, $data);

        return to_route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return to_route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
