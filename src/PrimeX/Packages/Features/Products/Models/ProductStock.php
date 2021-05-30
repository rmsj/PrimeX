<?php

namespace PrimeX\Packages\Features\Products\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Lumen\Auth\Authorizable;

class ProductStock extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'on_hand',
        'taken',
        'production_date',
    ];

    protected $casts = [
        'production_date' => 'datetime'
    ];

    /**
     * Stock on hand for a specific product
     *
     * @param $productId
     */
    public function onHandForProduct($productId)
    {
        $this->query()->selectRaw('SUM(on_hand - taken) as stock_on_hand')
            ->where('product_id', $productId)
            ->first()
            ->stock_on_hand;
    }

    /**
     * Stock entries for product
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

}
