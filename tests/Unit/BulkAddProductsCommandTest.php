<?php

namespace Tests\Unit;

use Laravel\Lumen\Testing\DatabaseMigrations;
use PrimeX\Packages\Features\Products\Models\Product;
use Tests\TestCase;

class BulkAddProductsCommandTest extends TestCase
{
    use DatabaseMigrations;

    public function testBulkAddProduct()
    {
        // using wrong CSV, should error
        $this->artisan('primex:add-products', ['csv' => '/var/www/html/tests/data/primex-stock-test.csv']);
        $products = Product::all();
        $this->assertTrue($products->count() === 0);

        $code = $this->artisan('primex:add-products', ['csv' => '/var/www/html/tests/data/primex-products-test.csv']);
        $this->assertTrue($code === 0);

        $products = Product::all();
        $this->assertTrue($products->count() === 3340);
    }
}
