<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\Category;
use App\Models\DeployedItem;
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
    public function inventory(Request $request)
    {
        $departments = \App\Models\Department::orderBy('officename')->get();
        $query = Supply::with(['category', 'department']);
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        $supplies = $query->orderBy('name')->paginate(10);
        return view('reports.inventory', compact('supplies', 'departments'));
    }

    /**
     * Display deployed items report.
     */
    public function deployedItems(Request $request)
    {
        $departments = \App\Models\Department::orderBy('officename')->get();
        $query = DeployedItem::with('department');
        
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        
        // Filter by date range if provided
        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('dateDeployed', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }
        
        $deployedItems = $query->latest()->paginate(10);
        return view('reports.deployed-items', compact('deployedItems', 'departments'));
    }

    /**
     * Display low stock report.
     * Now uses a fixed threshold of 5 items to determine low stock
     */
    public function lowStock(Request $request)
    {
        $departments = \App\Models\Department::orderBy('officename')->get();
        $query = Supply::where('quantity', '<=', 5) // Using 5 as a general threshold
            ->with(['category', 'department']);
            
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        
        $supplies = $query->orderBy('quantity')->paginate(10);
        return view('reports.low-stock', compact('supplies', 'departments'));
    }

    /**
     * Export reports to CSV.
     */
    public function export($type, Request $request)
    {
        switch ($type) {
            case 'inventory':
                return $this->exportInventory($request);
            case 'deployed-items':
                return $this->exportDeployedItems($request);
            case 'low-stock':
                return $this->exportLowStock($request);
            default:
                return redirect()->back()->with('error', 'Invalid report type.');
        }
    }

    /**
     * Export inventory report to CSV.
     */
    private function exportInventory($request)
    {
        $query = Supply::with(['category', 'department'])->orderBy('name');
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        $supplies = $query->get()->map(function ($supply) {
            return [
                'Category' => $supply->category->name,
                'Department' => $supply->department ? $supply->department->officename . ' (' . $supply->department->departmentID . ')' : '',
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
    private function exportTransactions($request)
    {
        $query = Transaction::with(['supply.department', 'user'])->latest();
        if ($request->filled('department_id')) {
            $query->whereHas('supply', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }
        $transactions = $query->get()->map(function ($transaction) {
            return [
                'Date' => $transaction->created_at->format('Y-m-d H:i:s'),
                'Supply' => $transaction->supply->name ?? '',
                'Department' => $transaction->supply && $transaction->supply->department ? $transaction->supply->department->officename . ' (' . $transaction->supply->department->departmentID . ')' : '',
                'Type' => ucfirst($transaction->type),
                'Quantity' => $transaction->quantity,
                'Unit' => $transaction->supply->unit ?? '',
                'User' => $transaction->user->name,
                'Remarks' => $transaction->remarks,
            ];
        });
        return $this->generateCsv('transactions.csv', $transactions);
    }

    /**
     * Export low stock report to CSV.
     * Now shows items with quantity <= 5 as low stock
     */
    private function exportLowStock($request)
    {
        $query = Supply::where('quantity', '<=', 5) // Using 5 as a general threshold
            ->with(['category', 'department']);
            
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        
        $supplies = $query->get()->map(function ($supply) {
            return [
                'Category' => $supply->category->name,
                'Department' => $supply->department ? $supply->department->officename . ' (' . $supply->department->departmentID . ')' : '',
                'Name' => $supply->name,
                'Current Stock' => $supply->quantity,
                'Status' => $supply->quantity <= 5 ? 'Low Stock' : 'In Stock',
                // Removed unit, supplier, and supplier_contact as they were deleted fields
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