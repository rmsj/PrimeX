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
     * @return ProductStock|null
     */
    public function execute(array $data): ?ProductStock
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
     * @return int
     */
    public function executeBulk(array $data): int
    {
        try {
            $productsStockToAdd = collect($data);
            $productsCode = $productsStockToAdd->pluck('product_code')->unique()->toArray();
            /** @var Collection $products */
            $products = Product::whereIn('code', $productsCode)->get();

            // we need the product id, not the code, so we add it to our data entries
            $stockToAdd = $productsStockToAdd->map(function (array $stock) use ($products) {
                $stock['product_id'] = $products->firstWhere('code', $stock['product_code'])->id ?? null;
                if (!isset($stock['taken'])) {
                    $stock['taken'] = 0;
                }
                if (empty($stock['production_date'])) {
                    $date = date('Y-m-d H:i:s', strtotime('now'));
                } else {
                    $date = preg_replace('#(\d{2})/(\d{2})/(\d{4})#', '$3-$2-$1', $stock['production_date']);
                    $date = date('Y-m-d H:i:s', strtotime($date));
                }
                $stock['production_date'] = $date;
                unset($stock['product_code']);
                return $stock;
            })->filter(function (array $stock) {
                // should not be the case, but we clear invalid entries where product code is empty
                return !empty($stock['product_id']);
            });

            foreach ($stockToAdd->chunk(500) as $chunk) {
                ProductStock::insert($chunk->toArray());
            }
            return $stockToAdd->count();
        } catch (\Exception $e) {
            Log::error('ERROR CREATING PRODUCT STOCK: ' . $e->getTraceAsString());
            return 0;
        }
    }
}
