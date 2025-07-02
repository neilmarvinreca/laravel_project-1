<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard.
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Display inventory report.
     */
    public function inventory()
    {
        $supplies = Supply::with('category')
            ->orderBy('name')
            ->paginate(10);

        return view('reports.inventory', compact('supplies'));
    }

    /**
     * Display transactions report.
     */
    public function transactions(Request $request)
    {
        $query = Transaction::with(['supply', 'user']);

        // Filter by date range if provided
        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        // Filter by transaction type if provided
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->latest()->paginate(10);

        return view('reports.transactions', compact('transactions'));
    }

    /**
     * Display low stock report.
     */
    public function lowStock()
    {
        $supplies = Supply::whereRaw('quantity <= minimum_quantity')
            ->with('category')
            ->orderBy('quantity')
            ->paginate(10);

        return view('reports.low-stock', compact('supplies'));
    }

    /**
     * Export reports to CSV.
     */
    public function export($type)
    {
        switch ($type) {
            case 'inventory':
                return $this->exportInventory();
            case 'transactions':
                return $this->exportTransactions();
            case 'low-stock':
                return $this->exportLowStock();
            default:
                return redirect()->back()->with('error', 'Invalid report type.');
        }
    }

    /**
     * Export inventory report to CSV.
     */
    private function exportInventory()
    {
        $supplies = Supply::with('category')
            ->orderBy('name')
            ->get()
            ->map(function ($supply) {
                return [
                    'Category' => $supply->category->name,
                    'Name' => $supply->name,
                    'Quantity' => $supply->quantity,
                    'Unit' => $supply->unit,
                    'Unit Cost' => $supply->unit_cost,
                    'Total Value' => $supply->getTotalValue(),
                    'Location' => $supply->location,
                    'Supplier' => $supply->supplier,
                ];
            });

        return $this->generateCsv('inventory.csv', $supplies);
    }

    /**
     * Export transactions report to CSV.
     */
    private function exportTransactions()
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

        return $this->generateCsv('transactions.csv', $transactions);
    }

    /**
     * Export low stock report to CSV.
     */
    private function exportLowStock()
    {
        $supplies = Supply::whereRaw('quantity <= minimum_quantity')
            ->with('category')
            ->get()
            ->map(function ($supply) {
                return [
                    'Category' => $supply->category->name,
                    'Name' => $supply->name,
                    'Current Stock' => $supply->quantity,
                    'Minimum Stock' => $supply->minimum_quantity,
                    'Unit' => $supply->unit,
                    'Supplier' => $supply->supplier,
                    'Supplier Contact' => $supply->supplier_contact,
                ];
            });

        return $this->generateCsv('low-stock.csv', $supplies);
    }

    /**
     * Generate CSV file from data.
     */
    private function generateCsv($filename, $data)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' . $filename,
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_keys($data->first()));

            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 