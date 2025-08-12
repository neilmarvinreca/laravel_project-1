<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'categoryID';
    protected $table = 'categories';

    protected $fillable = [
        'categoryName',
        'description',
    ];

    protected $appends = ['supplies_count'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];
    protected $hidden = ['deleted_at'];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'categoryID';
    }

    /**
     * Set the categoryName attribute.
     */
    public function setCategoryNameAttribute($value)
    {
        $this->attributes['categoryName'] = trim($value);
    }

    /**
     * All supplies for the category.
     */
    public function supplies()
    {
        return $this->hasMany(Supply::class, 'category_id', 'categoryID')
            ->with('department');
    }

    /**
     * Supplies count attribute.
     */
    public function getSuppliesCountAttribute()
    {
        return $this->supplies()->count();
    }

    /**
     * Total value of all supplies in this category.
     */
    public function getTotalValue(): float
    {
        return $this->supplies->sum(function ($supply) {
            return $supply->getTotalValue();
        });
    }

    /**
     * Count of low stock items in this category.
     */
    public function getLowStockCount(): int
    {
        return $this->supplies->filter(function ($supply) {
            return $supply->isLowStock();
        })->count();
    }
}
