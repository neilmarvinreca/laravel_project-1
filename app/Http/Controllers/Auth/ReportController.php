<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function inventory()
    {
        $supplies = Supply::with('category')
            ->when(request('category'), function($query, $category) {
                $query->where('category_id', $category);
            })
            ->when(request('stock_status'), function($query, $status) {
                if ($status === 'low') {
                    $query->whereRaw('quantity <= minimum_quantity');
                }
            })
            ->get();

        $categories = Category::all();

        return view('reports.inventory', compact('supplies', 'categories'));
    }

    public function transactions()
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

        return view('reports.transactions', compact('transactions'));
    }

    public function lowStock()
    {
        $supplies = Supply::with('category')
            ->whereRaw('quantity <= minimum_quantity')
            ->orderBy('quantity')
            ->get();

        return view('reports.low-stock', compact('supplies'));
    }

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
                return back()->with('error', 'Invalid report type.');
        }
    }

    private function exportInventory()
    {
        $supplies = Supply::with('category')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="inventory.csv"',
        ];

        $callback = function() use ($supplies) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Code', 'Name', 'Category', 'Quantity', 'Minimum Quantity', 'Unit', 'Unit Price', 'Location', 'Supplier']);

            foreach ($supplies as $supply) {
                fputcsv($file, [
                    $supply->code,
                    $supply->name,
                    $supply->category->name,
                    $supply->quantity,
                    $supply->minimum_quantity,
                    $supply->unit,
                    $supply->unit_price,
                    $supply->location,
                    $supply->supplier
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportTransactions()
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

            fputcsv($file, ['Date', 'Supply', 'Type', 'Quantity', 'User', 'Remarks', 'Reference']);

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

    private function exportLowStock()
    {
        $supplies = Supply::with('category')
            ->whereRaw('quantity <= minimum_quantity')
            ->orderBy('quantity')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="low-stock.csv"',
        ];

        $callback = function() use ($supplies) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Code', 'Name', 'Category', 'Current Stock', 'Minimum Required', 'Status']);

            foreach ($supplies as $supply) {
                fputcsv($file, [
                    $supply->code,
                    $supply->name,
                    $supply->category->name,
                    $supply->quantity,
                    $supply->minimum_quantity,
                    'Critical'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
