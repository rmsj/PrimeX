<?php

namespace PrimeX\Packages\Features\Products\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use PrimeX\Packages\Features\Products\Models\ProductStock;

/**
 * Class ProductStockResource
 *
 * @package PrimeX\Packages\Features\Addresses\Http\Resources
 */
class ProductStockResource extends JsonResource
{
    /** @var ProductStock */
    public $resource;

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'on_hand'         => $this->resource->on_hand,
            'taken'           => $this->resource->taken,
            'production_date' => $this->resource->production_date,
        ];
    }
}
