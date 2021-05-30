<?php

namespace PrimeX\Packages\Features\Products\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\ValidationException;
use PrimeX\Packages\Core\AbstractController;
use PrimeX\Packages\Features\Products\Actions\CreateProduct;
use PrimeX\Packages\Features\Products\Actions\UpdateProduct;
use PrimeX\Packages\Features\Products\Http\Resources\ProductDetailedResource;
use PrimeX\Packages\Features\Products\Http\Resources\ProductResource;
use PrimeX\Packages\Features\Products\Models\Product;

class ProductController extends AbstractController
{
    /**
     * Display a listing of products.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $query = Product::query()->withStock();

        $limit = $request->get('limit', $this->pageSize);

        // only product with summarized stock on hand or product with all stock entries
        $detailed = $request->get('detailed', false);

        // code parameter
        if ($code = $request->get('code')) {
            $query->where('code', $code);
        }

        // filter by stock limit?
        if ($stock = $request->get('stock')) {
            $stock = is_numeric($stock) ? $stock : 0;
            $query->having('stock_on_hand', '>=', $stock);
        }

        $sort = $request->get('sort');
        // default sort option
        if (!in_array($sort, ['asc', 'desc'])) {
            $sort = 'asc';
        }
        $query->orderBy('stock_on_hand', $sort);
        $products = $query->paginate($limit);

        if ($detailed) {
            return ProductDetailedResource::collection($products);
        }

        return ProductResource::collection($products);
    }

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
            'code'        => 'required|unique:products,code',
            'name'        => 'required|max:100',
            'description' => 'sometimes|nullable|max:255'
        ]);

        if ($product = (new CreateProduct())->execute($request->all())) {
            return ProductResource::make($product);
        }

        // TODO: be more specific with the errror message
        return $this->badRequest('failed to create product');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse|ProductDetailedResource
     */
    public function show(int $id)
    {
        $product = Product::query()->withStock()->find($id);
        if (empty($product)) {
            return $this->notFound('Product not found with ID ' . $id);
        }

        return ProductDetailedResource::make($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return JsonResponse|ProductDetailedResource
     * @throws ValidationException
     */
    public function update(Request $request)
    {
        $code = $request->get('code');
        $this->validate($request, [
            'id'          => 'required|exists:products,id',
            'code'        => 'sometimes|required|unique:products,code,' . $code,
            'name'        => 'sometimes|required|max:100',
            'description' => 'sometimes|nullable|max:255'
        ]);

        if ((new UpdateProduct())->execute($request->all())) {
            $product = Product::withStock()->where('products.id', $request->get('id'))->first();
            return ProductDetailedResource::make($product);
        }

        // TODO: be more specific with the errror message
        return $this->badRequest('failed to update product');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $product = Product::query()->find($id);
        if (empty($product)) {
            return $this->notFound('Product not found with ID ' . $id);
        }

        $product->delete();
        return $this->noContent();
    }
}
