<?php

namespace PrimeX\Packages\Features\Products\Actions;

use Illuminate\Support\Facades\Log;
use PrimeX\Packages\Features\Products\Models\Product;

class DeleteProduct
{
    /**
     * Executes action to create a product
     *
     * @param array $ids
     * @return bool
     */
    public function execute(array $ids): bool
    {
        try {
            if (empty($ids)) {
                throw new \Exception('No ids provided to delete products');
            }

            return (bool)Product::destroy($ids);

        } catch (\Exception $e) {
            Log::error('ERROR DELETING PRODUCT: ' . $e->getTraceAsString());
            return false;
        }
    }
}
