<?php

namespace Tests\Unit;

use Laravel\Lumen\Testing\DatabaseMigrations;
use PrimeX\Packages\Features\Products\Actions\CreateProduct;
use PrimeX\Packages\Features\Products\Models\Product;
use Tests\TestCase;

class CreateProductTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateSingleProduct()
    {
        $data = [
            'code'        => '123456',
            'name'        => 'Product Test',
            'description' => 'Product Test Desc',
        ];

        $product = (new CreateProduct())->execute($data);

        $this->assertInstanceOf(Product::class, $product);
        $this->seeInDatabase('products', ['code' => '123456']);
    }

    public function testCreateMultipleProducts()
    {
        // TODO: this could be done with a data provider
        $codes = ['123456', 'abcdef', '987654'];
        $data = [];
        foreach ($codes as $code) {
            $data[] = [
                'code'        => $code,
                'name'        => 'Product Test',
                'description' => 'Product Test Desc',
            ];
        }

        $added = (new CreateProduct())->executeBulk($data);

        $this->assertTrue($added === 3);
        foreach ($codes as $code) {
            $this->seeInDatabase('products', ['code' => $code]);
        }
    }
}
