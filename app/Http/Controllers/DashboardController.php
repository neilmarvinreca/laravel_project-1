<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSupplies = Supply::count();
        $lowStockItems = Supply::whereRaw('quantity <= minimum_quantity')
            ->orderBy('quantity')
            ->get();
        $lowStockCount = $lowStockItems->count();
        $totalCategories = Category::count();
        $totalTransactions = Transaction::count();
        $recentTransactions = Transaction::with(['supply', 'user'])
            ->latest()
            ->take(5)
            ->get();

        $todayTransactions = Transaction::whereDate('created_at', Carbon::today())->count();

        // Calendar events (example: upcoming restock dates)
        $calendarEvents = Supply::whereNotNull('last_restock_date')
            ->where('last_restock_date', '>=', Carbon::now()->subDays(30))
            ->get()
            ->map(function ($supply) {
                return [
                    'title' => "Restock: {$supply->name}",
                    'start' => $supply->last_restock_date->addDays(30)->format('Y-m-d'),
                    'color' => '#dc3545'
                ];
            });

        return view('dashboard', compact(
            'totalSupplies',
            'lowStockItems',
            'lowStockCount',
            'totalCategories',
            'totalTransactions',
            'recentTransactions',
            'todayTransactions',
            'calendarEvents'
        ));
    }
}
