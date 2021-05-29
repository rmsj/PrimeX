<?php

namespace PrimeX\Packages\Features\Products\Actions;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CreateSingleProduct
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
}
