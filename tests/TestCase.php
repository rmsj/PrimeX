<?php

namespace Tests;

use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use PrimeX\Packages\Features\Products\Actions\CreateProduct;
use PrimeX\Packages\Features\Products\Models\Product;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    protected function addProduct($code): Product
    {
        $data = [
            'code'        => $code,
            'name'        => 'Product Test',
            'description' => 'Product Test Desc',
        ];

        return (new CreateProduct())->execute($data);
    }
}
