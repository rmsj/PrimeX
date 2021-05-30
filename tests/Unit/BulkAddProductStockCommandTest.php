<?php

namespace Tests\Unit;

use Laravel\Lumen\Testing\DatabaseMigrations;
use PrimeX\Packages\Features\Products\Models\Product;
use Tests\TestCase;

class BulkAddProductStockCommandTest extends TestCase
{
    use DatabaseMigrations;

    public function testBulkUpdateProduct()
    {
        // first we add product
        $code = $this->artisan('primex:add-products', ['csv' => '/var/www/html/tests/data/primex-products-test.csv']);
        $this->assertTrue($code === 0);

        // add product stock
        $code = $this->artisan('primex:add-product-stock', ['csv' => '/var/www/html/tests/data/primex-stock-test.csv']);
        $this->assertTrue($code === 0);

        $products = Product::all();
        $this->assertTrue($products->count() === 3340);
    }
}
