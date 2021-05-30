<?php

namespace PrimeX\Packages\Features\Products\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PrimeX\Packages\Core\AbstractController;
use PrimeX\Packages\Features\Products\Actions\CreateProduct;
use PrimeX\Packages\Features\Products\Http\Resources\ProductResource;

class ProductStockController extends AbstractController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse|ProductResource
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'product_id'      => 'required|exists:products,id',
            'on_hand'         => 'required|integer|numeric',
            'taken'           => 'required|integer|numeric',
            'production_date' => 'required|date'
        ]);

        if ($product = (new CreateProduct())->execute($request->all())) {
            return ProductResource::make($product);
        }

        // TODO: be more specific with the errror message
        return $this->badRequest('failed to create product stock entry');
    }
}
