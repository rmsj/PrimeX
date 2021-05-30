<?php

namespace PrimeX\Packages\Features\Products\Actions;

use PrimeX\Packages\Features\Products\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CreateProduct
{
    /**
     * Executes action to create a product
     *
     * @param array $data
     * @return Product|null
     */
    public function execute(array $data): ?Product
    {
        try {
            return Product::create($data);
        } catch (\Exception $e) {
            Log::error('ERROR CREATING PRODUCT: ' . $e->getTraceAsString());
            return null;
        }
    }

    /**
     * Executes action to create many products at once
     *
     * @param array $data - in the format [[code => ..., name => ...], [code => ..., name => ...], ...]
     * @return int
     */
    public function executeBulk(array $data): int
    {
        try {
            $productsToAdd = collect($data);
            foreach ($productsToAdd->chunk(500) as $chunk) {
                Product::insert($chunk->toArray());
            }
            return $productsToAdd->count();
        } catch (\Exception $e) {
            Log::error('ERROR CREATING PRODUCT: ' . $e->getTraceAsString());
            return 0;
        }
    }
}
