<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    public function supplies()
    {
        return $this->hasMany(Supply::class);
    }

    /**
     * Get the total value of all supplies in this category.
     */
    public function getTotalValue(): float
    {
        return $this->supplies->sum(function ($supply) {
            return $supply->getTotalValue();
        });
    }

    /**
     * Get the count of low stock items in this category.
     */
    public function getLowStockCount(): int
    {
        return $this->supplies->filter(function ($supply) {
            return $supply->isLowStock();
        })->count();
    }
}
