<?php

namespace Tests\Unit;

use Laravel\Lumen\Testing\DatabaseMigrations;
use PrimeX\Packages\Features\Products\Actions\CreateProduct;
use PrimeX\Packages\Features\Products\Actions\DeleteProduct;
use PrimeX\Packages\Features\Products\Models\Product;
use Tests\TestCase;

class DeleteProductTest extends TestCase
{
    use DatabaseMigrations;

    public function testDeleteSingleProduct()
    {
        $data = [
            'code'        => '123456',
            'name'        => 'Product Test',
            'description' => 'Product Test Desc',
        ];

        $product = (new CreateProduct())->execute($data);
        $deleteAction = new DeleteProduct();

        $this->assertTrue($deleteAction->execute([$product->id]));
        $this->notSeeInDatabase('products', ['code' => '123456']);
    }

    public function testDeleteMultipleProducts()
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
        // first we need to add
        (new CreateProduct())->executeBulk($data);
        $products = Product::query()->whereIn('code', $codes);
        $deleteAction = new DeleteProduct();

        $this->assertTrue($deleteAction->execute($products->pluck('id')->toArray()));
        foreach ($codes as $code) {
            $this->notSeeInDatabase('products', ['code' => $code]);
        }
    }
}
