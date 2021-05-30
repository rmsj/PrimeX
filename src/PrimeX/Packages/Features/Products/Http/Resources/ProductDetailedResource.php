<?php

namespace PrimeX\Packages\Features\Products\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use PrimeX\Packages\Features\Products\Models\Product;

/**
 * Class ProductDetailedResource
 *  Resource for APi requests for products with details information about stock entries
 *
 * @package PrimeX\Packages\Features\Addresses\Http\Resources
 */
class ProductDetailedResource extends JsonResource
{
    /** @var Product */
    public $resource;

    /**
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->resource->id,
            'code'        => $this->resource->code,
            'name'        => $this->resource->name,
            'description' => $this->resource->description,
            'stock'       => ProductStockResource::collection($this->resource->stocks),
        ];
    }
}
