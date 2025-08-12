<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SupplyController extends Controller
{
    public function index(Request $request)
    {
        // Debug: Log the request parameters
        \Log::info('Auth/SupplyController@index', ['request' => $request->all()]);
        
        // Create a new query builder instance
        $query = Supply::query();
        
        // Eager load relationships
        $query->with(['category', 'department']);
        
        // Get departments for the filter
        $departments = \App\Models\Department::orderBy('officename')->get();
        
        // Apply search filter if provided
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        
        // Apply department filter if provided
        if ($request->filled('department_id')) {
            $query->where('departmentID', $request->department_id);
        }
        
        // Execute the query with pagination
        $supplies = $query->orderBy('name')->paginate(10);
        $categories = Category::all();
        
        // Debug: Log the query SQL and bindings
        \Log::info('Auth/Supply Query', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings(),
            'supplies_type' => get_class($supplies),
            'supplies_count' => $supplies->count(),
            'supplies_total' => $supplies->total()
        ]);

        return view('supplies.index', compact('supplies', 'categories', 'departments'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('supplies.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'acquired_at' => 'required|date',
            'estimated_life' => 'nullable|string',
            'unit_cost' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,categoryID',
            'department_id' => 'required|exists:departments,departmentID',
            'fund_code' => 'required|string|max:255',
            'ppesubacc' => 'required|string|max:255',
            'gl_code' => 'required|string|max:255',
            'added_by' => 'required|exists:users,id',
        ]);

        $supply = Supply::create($validated);

        return redirect()->route('supplies.index')
            ->with('success', 'Supply created successfully.');
    }

    public function show(Supply $supply)
    {
        $supply->load(['category', 'transactions' => function($query) {
            $query->latest()->take(10);
        }]);

        return view('supplies.show', compact('supply'));
    }

    public function edit(Supply $supply)
    {
        $categories = Category::all();
        return view('supplies.edit', compact('supply', 'categories'));
    }

    public function update(Request $request, Supply $supply)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'acquired_at' => 'required|date',
            'estimated_life' => 'nullable|string',
            'unit_cost' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,categoryID',
            'department_id' => 'required|exists:departments,departmentID',
            'fund_code' => 'required|string|max:255',
            'ppesubacc' => 'required|string|max:255',
            'gl_code' => 'required|string|max:255',
        ]);

        $supply->update($validated);

        return redirect()->route('supplies.index')
            ->with('success', 'Supply updated successfully.');
    }

    public function destroy(Supply $supply)
    {
        $supply->delete();

        return redirect()->route('supplies.index')
            ->with('success', 'Supply deleted successfully.');
    }

    public function restock(Request $request, Supply $supply)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'remarks' => 'nullable|string'
        ]);

        $supply->increment('quantity', $validated['quantity']);

        Transaction::create([
            'supply_id' => $supply->id,
            'user_id' => Auth::id(),
            'type' => 'in',
            'quantity' => $validated['quantity'],
            'remarks' => $validated['remarks'] ?? 'Restock'
        ]);

        return redirect()->route('supplies.show', $supply)
            ->with('success', 'Supply restocked successfully.');
    }
}
