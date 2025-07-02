<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['supply', 'user'])
            ->when(request('type'), function($query, $type) {
                $query->where('type', $type);
            })
            ->when(request('date_from'), function($query, $date) {
                $query->whereDate('created_at', '>=', Carbon::parse($date));
            })
            ->when(request('date_to'), function($query, $date) {
                $query->whereDate('created_at', '<=', Carbon::parse($date));
            })
            ->latest()
            ->paginate(15);

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $supplies = Supply::all();
        return view('transactions.create', compact('supplies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supply_id' => 'required|exists:supplies,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'remarks' => 'nullable|string',
            'reference_number' => 'nullable|string|max:255'
        ]);

        $supply = Supply::findOrFail($validated['supply_id']);

        if ($validated['type'] === 'out' && $supply->quantity < $validated['quantity']) {
            return back()->with('error', 'Insufficient stock available.');
        }

        $validated['user_id'] = Auth::id();

        Transaction::create($validated);

        // Update supply quantity
        if ($validated['type'] === 'in') {
            $supply->increment('quantity', $validated['quantity']);
            $supply->update(['last_restock_date' => now()]);
        } else {
            $supply->decrement('quantity', $validated['quantity']);
        }

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction recorded successfully.');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['supply', 'user']);
        return view('transactions.show', compact('transaction'));
    }

    public function export()
    {
        $transactions = Transaction::with(['supply', 'user'])
            ->when(request('type'), function($query, $type) {
                $query->where('type', $type);
            })
            ->when(request('date_from'), function($query, $date) {
                $query->whereDate('created_at', '>=', Carbon::parse($date));
            })
            ->when(request('date_to'), function($query, $date) {
                $query->whereDate('created_at', '<=', Carbon::parse($date));
            })
            ->latest()
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="transactions.csv"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, ['Date', 'Supply', 'Type', 'Quantity', 'User', 'Remarks', 'Reference']);

            // Add data
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->created_at->format('Y-m-d H:i:s'),
                    $transaction->supply->name,
                    $transaction->type,
                    $transaction->quantity,
                    $transaction->user->name,
                    $transaction->remarks,
                    $transaction->reference_number
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy(Transaction $transaction)
    {
        try {
            // Get the supply associated with the transaction
            $supply = $transaction->supply;

            // Only adjust supply quantity if the supply still exists
            if ($supply) {
                // Reverse the transaction effect on supply quantity
                if ($transaction->type === 'in') {
                    $supply->decrement('quantity', $transaction->quantity);
                } else {
                    $supply->increment('quantity', $transaction->quantity);
                }
            }

            // Delete the transaction
            $transaction->delete();

            return redirect()->route('transactions.index')
                ->with('success', 'Transaction deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('transactions.index')
                ->with('error', 'Failed to delete transaction. Please try again.');
        }
    }
}
