<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $supplies = Supply::with('category')
            ->orderBy('name')
            ->paginate(10);

        return view('inventory.index', compact('supplies'));
    }
} 