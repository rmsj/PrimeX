<?php

namespace PrimeX\Packages\Features\Products\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

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
     * Summary of stock on hand for the product
     *
     * @return mixed
     */
    public function getStockOnHandAttribute()
    {
        return $this->stock->onHandForProduct($this->id);
    }

    /**
     * @param Builder $query
     * @return mixed
     */
    public function scopeWithStock(Builder $query)
    {
        return $query->selectRaw('SUM(product_stocks.on_hand - product_stocks.taken) as stock_on_hand')
            ->groupByRaw('product_stocks.product_id')
            ->join(env('DB_DATABASE') . '.product_stocks', 'product.id', '=', 'product_stocks.product_id');
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
