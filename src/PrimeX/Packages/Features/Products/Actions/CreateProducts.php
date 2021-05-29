<?php

namespace PrimeX\Packages\Features\Products\Actions;

use PrimeX\Packages\Features\Products\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CreateProducts
{
    /**
     * Executes action to create many products at once
     *
     * @param array $data - in the format [[code => ..., name => ...], [code => ..., name => ...], ...]
     * @return Collection
     */
    public function execute(array $data): ?Collection
    {
        try {
            return Product::insert($data);
        } catch (\Exception $e) {
            Log::error('ERROR CREATING PRODUCT: ' . $e->getTraceAsString());
            return collect();
        }
    }
}
