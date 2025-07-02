<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supplies = Supply::with('category')
            ->orderBy('name')
            ->paginate(10);

        return view('supplies.index', compact('supplies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('supplies.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'unit_cost' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'supplier' => 'nullable|string|max:255',
            'supplier_contact' => 'nullable|string|max:255',
        ]);

        $supply = Supply::create($validated);

        return redirect()
            ->route('supplies.index')
            ->with('success', 'Supply created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supply $supply)
    {
        $supply->load(['category', 'transactions' => function ($query) {
            $query->latest()->take(5);
        }]);

        return view('supplies.show', compact('supply'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supply $supply)
    {
        $categories = Category::orderBy('name')->get();
        return view('supplies.edit', compact('supply', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supply $supply)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'unit_cost' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'supplier' => 'nullable|string|max:255',
            'supplier_contact' => 'nullable|string|max:255',
        ]);

        $supply->update($validated);

        return redirect()
            ->route('supplies.index')
            ->with('success', 'Supply updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supply $supply)
    {
        try {
            DB::beginTransaction();
            
            // Check if there are any related transactions
            if ($supply->transactions()->exists()) {
                return redirect()
                    ->route('supplies.index')
                    ->with('error', 'Cannot delete supply with existing transactions.');
            }

            $supply->delete();
            DB::commit();

            return redirect()
                ->route('supplies.index')
                ->with('success', 'Supply deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('supplies.index')
                ->with('error', 'An error occurred while deleting the supply.');
        }
    }
} 