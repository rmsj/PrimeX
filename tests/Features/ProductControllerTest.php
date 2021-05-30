<?php

namespace Tests\Features;

use Laravel\Lumen\Testing\DatabaseMigrations;
use PrimeX\Packages\Features\Products\Actions\CreateProduct;
use PrimeX\Packages\Features\Products\Models\Product;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateProduct()
    {
        $data = [
            'code'        => 'ABCDEF1',
            'name'        => 'Product Test',
            'description' => 'Product Test Desc',
        ];

        $this->post(route('products.create'), $data)
            ->assertResponseStatus(201);
        $this->seeInDatabase('products', ['code' => 'ABCDEF1']);
    }

    public function testUpdateProduct()
    {
        $code = "ABC123";
        $product = $this->addProduct($code);
        $this->seeInDatabase('products', ['code' => $code]);

        $data = [
            'id'   => $product->id,
            'name' => 'New Product Name',
        ];

        $this->put(route('products.update'), $data)
            ->assertResponseOk();
        $this->seeInDatabase('products', ['name' => 'New Product Name']);
    }

    public function testDeleteProduct()
    {
        $code = "ABC123";
        $product = $this->addProduct($code);
        $this->seeInDatabase('products', ['code' => $code]);

        $this->delete(route('products.destroy', ['id' => $product->id]))
            ->assertResponseStatus(204);
        $this->notSeeInDatabase('products', ['code' => $code]);
    }
}
