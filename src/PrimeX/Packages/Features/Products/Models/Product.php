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
