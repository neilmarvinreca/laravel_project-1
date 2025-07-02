<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with(['supply', 'user'])
            ->latest()
            ->paginate(10);
            
        $supplies = Supply::orderBy('name')->get();

        return view('transactions.index', compact('transactions', 'supplies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $supplies = Supply::orderBy('name')->get();
        return view('transactions.create', compact('supplies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supply_id' => 'required|exists:supplies,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'remarks' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $supply = Supply::findOrFail($validated['supply_id']);

            // Check if there's enough stock for 'out' transactions
            if ($validated['type'] === 'out' && $supply->quantity < $validated['quantity']) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Insufficient stock available.');
            }

            // Update supply quantity
            if ($validated['type'] === 'in') {
                $supply->quantity += $validated['quantity'];
                $supply->last_restock_date = now();
            } else {
                $supply->quantity -= $validated['quantity'];
            }
            
            $supply->save();

            // Create transaction record
            $transaction = new Transaction($validated);
            $transaction->user_id = Auth::id();
            $transaction->save();

            DB::commit();

            return redirect()
                ->route('transactions.index')
                ->with('success', 'Transaction recorded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while processing the transaction.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['supply', 'user']);
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        try {
            DB::beginTransaction();

            $supply = $transaction->supply;

            // Reverse the quantity change
            if ($transaction->type === 'in') {
                if ($supply->quantity < $transaction->quantity) {
                    return redirect()
                        ->route('transactions.index')
                        ->with('error', 'Cannot delete transaction: Would result in negative stock.');
                }
                $supply->quantity -= $transaction->quantity;
            } else {
                $supply->quantity += $transaction->quantity;
            }

            $supply->save();
            $transaction->delete();

            DB::commit();

            return redirect()
                ->route('transactions.index')
                ->with('success', 'Transaction deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('transactions.index')
                ->with('error', 'An error occurred while deleting the transaction.');
        }
    }

    /**
     * Export transactions to CSV/Excel
     */
    public function export()
    {
        $transactions = Transaction::with(['supply', 'user'])
            ->latest()
            ->get()
            ->map(function ($transaction) {
                return [
                    'Date' => $transaction->created_at->format('Y-m-d H:i:s'),
                    'Supply' => $transaction->supply->name,
                    'Type' => ucfirst($transaction->type),
                    'Quantity' => $transaction->quantity,
                    'User' => $transaction->user->name,
                    'Remarks' => $transaction->remarks,
                ];
            });

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=transactions.csv',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_keys($transactions->first()));

            foreach ($transactions as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'supply_id' => 'required|exists:supplies,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'remarks' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $supply = Supply::findOrFail($validated['supply_id']);
            $originalQuantity = $transaction->quantity;
            $newQuantity = $validated['quantity'];

            // For stock out transactions, prevent increasing the quantity
            if ($validated['type'] === 'out' && $newQuantity > $originalQuantity) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Cannot increase the quantity of a stock out transaction.');
            }

            // Adjust supply quantity based on the difference
            if ($validated['type'] === 'in') {
                $supply->quantity = $supply->quantity - $originalQuantity + $newQuantity;
            } else {
                // For stock out, first add back the original quantity, then subtract the new quantity
                $supply->quantity = $supply->quantity + $originalQuantity - $newQuantity;
                
                // Check if there's enough stock
                if ($supply->quantity < 0) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with('error', 'Insufficient stock available for the new quantity.');
                }
            }

            $supply->save();
            $transaction->update($validated);

            DB::commit();

            return redirect()
                ->route('transactions.index')
                ->with('success', 'Transaction updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the transaction.');
        }
    }
} 