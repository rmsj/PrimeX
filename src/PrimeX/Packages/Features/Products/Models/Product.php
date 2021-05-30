<?php

namespace PrimeX\Packages\Features\Products\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    protected $appends = [
        'stock_on_hand'
    ];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeWithStock($query)
    {
        return $query->selectRaw('products.*, SUM(COALESCE(product_stocks.on_hand, 0) - COALESCE(product_stocks.taken, 0)) as stock_on_hand')
            ->groupByRaw('products.id')
            ->leftJoin('product_stocks', 'products.id', '=', 'product_stocks.product_id');
    }

    /**
     * Stock entries for product
     *
     * @return HasMany
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(ProductStock::class);
    }
}
