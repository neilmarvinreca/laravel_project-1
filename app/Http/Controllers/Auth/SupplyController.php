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
    public function index()
    {
        $supplies = Supply::with('category')
            ->when(request('search'), function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
            ->when(request('category'), function($query, $category) {
                $query->where('category_id', $category);
            })
            ->latest()
            ->paginate(10);

        $categories = Category::all();

        return view('supplies.index', compact('supplies', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('supplies.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:supplies,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'minimum_quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'location' => 'nullable|string|max:255',
            'supplier' => 'nullable|string|max:255',
        ]);

        $supply = Supply::create($validated);

        // Record initial stock as a transaction
        Transaction::create([
            'supply_id' => $supply->id,
            'user_id' => Auth::id(),
            'type' => 'in',
            'quantity' => $validated['quantity'],
            'remarks' => 'Initial stock'
        ]);

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
            'quantity' => 'required|integer|min:0',
            'minimum_quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'location' => 'nullable|string|max:255',
            'supplier' => 'nullable|string|max:255',
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
        $supply->update(['last_restock_date' => now()]);

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
