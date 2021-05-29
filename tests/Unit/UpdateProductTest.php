<?php

namespace Tests\Unit;

use Laravel\Lumen\Testing\DatabaseMigrations;
use PrimeX\Packages\Features\Products\Actions\CreateProduct;
use PrimeX\Packages\Features\Products\Actions\UpdateProduct;
use Tests\TestCase;

class UpdateProductTest extends TestCase
{
    use DatabaseMigrations;

    public function testUpdateSingleProduct()
    {
        $data = [
            'code'        => '123456',
            'name'        => 'Product Test',
            'description' => 'Product Test Desc',
        ];

        $product = (new CreateProduct())->execute($data);
        $data['name'] = 'Updated Name';


        $updateAction = new UpdateProduct();

        $this->assertTrue($updateAction->execute($data));
        $this->seeInDatabase('products', ['code' => '123456']);
        $this->seeInDatabase('products', ['name' => 'Updated Name']);
        $this->notSeeInDatabase('products', ['name' => 'Product Test']);
    }

    public function testUpdateMultipleProducts()
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
        // first we create
        $products = (new CreateProduct())->executeBulk($data);

        // now to update
        foreach ($data as $key => $product) {
            $data[$key]['name'] = 'Changed name ' . $key;
        }


        $updateAction = new UpdateProduct();

        $this->assertTrue($updateAction->execute($data));
        foreach ($data as $product) {
            $this->seeSeeInDatabase('products', ['code' => $product['code']]);
            $this->seeSeeInDatabase('products', ['name' => $product['name']]);
        }
    }
}
