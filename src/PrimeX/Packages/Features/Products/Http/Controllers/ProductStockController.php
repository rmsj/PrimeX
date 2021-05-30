<?php

namespace PrimeX\Packages\Features\Products\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PrimeX\Packages\Core\AbstractController;
use PrimeX\Packages\Features\Products\Actions\CreateProductStock;
use PrimeX\Packages\Features\Products\Http\Resources\ProductDetailedResource;
use PrimeX\Packages\Features\Products\Models\Product;

class ProductStockController extends AbstractController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse|ProductDetailedResource
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_id'      => 'required|exists:products,id',
            'on_hand'         => 'required|integer|numeric',
            'taken'           => 'sometimes|nullable|integer|numeric',
            'production_date' => 'required|date'
        ]);

        if ($stock = (new CreateProductStock())->execute($request->all())) {
            $product = Product::withStock()->where('products.id', $stock->product_id)->first();
            return ProductDetailedResource::make($product);
        }

        // TODO: be more specific with the errror message
        return $this->badRequest('failed to create product stock entry');
    }
}
