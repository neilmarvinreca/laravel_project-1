<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supply extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'quantity',
        'minimum_stock',
        'unit',
        'unit_cost',
        'location',
        'supplier',
        'supplier_contact',
        'last_restock_date',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'minimum_stock' => 'integer',
        'unit_cost' => 'decimal:2',
        'last_restock_date' => 'datetime',
    ];

    /**
     * Get the category that owns the supply.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the transactions for the supply.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Check if the supply is low on stock.
     */
    public function isLowStock(): bool
    {
        return $this->quantity <= $this->minimum_stock;
    }

    /**
     * Get the total value of the supply.
     */
    public function getTotalValue(): float
    {
        return $this->quantity * $this->unit_cost;
    }
}
