<?php

namespace PrimeX\Packages\Features\Products\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use PrimeX\Packages\Features\Products\Models\Product;
use PrimeX\Packages\Features\Products\Models\ProductStock;

class CreateProductStock
{
    /**
     * Executes action to create a product stock
     *
     * @param array $data
     * @return Product|null
     */
    public function execute(array $data): ?Product
    {
        try {
            return ProductStock::create($data);
        } catch (\Exception $e) {
            Log::error('ERROR CREATING PRODUCT STOCK: ' . $e->getTraceAsString());
            return null;
        }
    }

    /**
     * Executes action to create many product stock entries at once
     *
     * @param array $data - in the format [[code => ..., on_hand => ...], [code => ..., on_hand => ...], ...]
     * @return Collection
     */
    public function executeBulk(array $data): ?Collection
    {
        try {
            $productsStockToAdd = collect($data);
            $productsCode = $productsStockToAdd->pluck('product_code')->unique()->toArray();
            /** @var Collection $products */
            $products = Product::whereIn('code', $productsCode)->get();

            // we need the product id, not the code, so we add it to our data entries
            $productsStockToAdd->map(function(array $stock) use ($products) {
                $stock['product_id'] = $products->firstWhere('code', $stock['product_code'])->id ?? null;
                unset($stock['product_code']);

                return $stock;
            })->filter(function (array $stock) {
                // should not be the case, but we clear invalid entries where product code is empty
                return !empty($stock['product_id']);
            });

            foreach ($productsStockToAdd as &$stock) {
                $stock['product_id'] = $products->where('code', $stock['product_code']);
            }

            $productStocks = collect();
            foreach ($productsStockToAdd->chunk(500) as $chunk) {
                $productStocks->merge(ProductStock::create($chunk->toArray()));
            }
            return $productStocks;
        } catch (\Exception $e) {
            Log::error('ERROR CREATING PRODUCT STOCK: ' . $e->getTraceAsString());
            return collect();
        }
    }
}
