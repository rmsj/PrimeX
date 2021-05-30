<?php

namespace PrimeX\Packages\Features\Products\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use PrimeX\Packages\Features\Products\Models\Product;

/**
 * Class ProductResource
 *
 * @package PrimeX\Packages\Features\Addresses\Http\Resources
 */
class ProductResource extends JsonResource
{
    /** @var Product */
    public $resource;

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->resource->id,
            'code'          => $this->resource->code,
            'name'          => $this->resource->name,
            'description'   => $this->resource->description,
            'stock_on_hand' => $this->resource->stock_on_hand,
        ];
    }
}
