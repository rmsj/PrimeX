<?php

namespace PrimeX\Packages\Features\Products\Actions;

use Illuminate\Support\Facades\Log;
use PrimeX\Packages\Features\Products\Models\Product;

class UpdateProduct
{
    /**
     * Executes action to create a product
     *
     * @param array $data
     * @return bool
     */
    public function executeOnce(array $data): bool
    {
        try {
            if (empty($data['id']) && empty($data['code'])) {
                throw new \Exception('Must provide ID or code to update product');
            }

            if (!empty($data['id'])) {
                $id = $data['id'];
                unset($data['id']);
                return Product::where('id', $id)->update($data);
            }

            if (!empty($data['code'])) {
                $code = $data['code'];
                unset($data['code']);
                return Product::where('code', $code)->update($data);
            }

        } catch (\Exception $e) {
            Log::error('ERROR UPDATING PRODUCT: ' . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Executes action to create many products at once
     *
     * @param array $data - in the format [[code => ..., name => ...], [code => ..., name => ...], ...]
     * @return bool
     */
    public function executeMany(array $data): bool
    {
        try {
            $productsToAdd = collect($data);
            foreach ($productsToAdd->chunk(500) as $chunk) {
                Product::upsert($chunk->toArray(),
                    ['id', 'code'], ['name', 'description']);
            }
            return true;
        } catch (\Exception $e) {
            Log::error('ERROR CREATING PRODUCT: ' . $e->getTraceAsString());
            return false;
        }
    }
}
