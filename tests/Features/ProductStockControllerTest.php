<?php

namespace Tests\Features;

use Laravel\Lumen\Testing\DatabaseMigrations;
use PrimeX\Packages\Features\Products\Actions\CreateProduct;
use PrimeX\Packages\Features\Products\Models\Product;
use Tests\TestCase;

class ProductStockControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateProductStock()
    {
        $code = "ABC123";
        $product = $this->addProduct($code);
        $this->seeInDatabase('products', ['code' => $code]);

        $data = [
            "product_id"      => $product->id,
            "on_hand"         => 150,
            "production_date" => "2021-10-30"
        ];

        $this->post(route('product-stock.create'), $data)
            ->assertResponseOk(200);

        $product = Product::query()->withStock()->where('code', $code)->first();
        $this->assertEquals(150, $product->stock_on_hand);
    }
}
