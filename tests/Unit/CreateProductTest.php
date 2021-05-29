<?php

namespace Tests\Unit;

use PrimeX\Packages\Features\Products\Actions\CreateProducts;
use PrimeX\Packages\Features\Products\Models\Product;
use Laravel\Lumen\Testing\DatabaseMigrations;
use PrimeX\Packages\Features\Products\Actions\CreateSingleProduct;
use Tests\TestCase;

class CreateProductTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreateSingleProduct()
    {
        $data = [
            'code' => '123456',
            'name' => 'Product Test',
            'description' => 'Product Test Desc',
        ];

        $product = (new CreateSingleProduct())->execute($data);

        $this->assertInstanceOf(Product::class, $product);
        $this->seeInDatabase('products', ['code' => '123456']);
    }

    public function testCreateMultipleProducts()
    {
        $codes = ['123456', 'abcdef', '987654'];
        foreach ($codes as $code) {
            $data[] = [
                'code' => $code,
                'name' => 'Product Test',
                'description' => 'Product Test Desc',
            ];
        }

        $products = (new CreateProducts())->execute($data);

        $this->assertTrue($products->count() === 3);
        foreach ($codes as $code) {
            $this->seeInDatabase('products', ['code' => $code]);
        }
    }
}
