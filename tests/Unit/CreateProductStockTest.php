<?php

namespace Tests\Unit;

use Laravel\Lumen\Testing\DatabaseMigrations;
use PrimeX\Packages\Features\Products\Actions\CreateProduct;
use PrimeX\Packages\Features\Products\Actions\CreateProductStock;
use PrimeX\Packages\Features\Products\Models\ProductStock;
use Tests\TestCase;

class CreateProductStockTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateSingleProductStock()
    {
        $code = '123456';
        $product = $this->addProduct($code);

        $data = [
            'product_id'      => $product->id,
            'on_hand'         => 1,
            'production_date' => '26/08/2020',
        ];

        $productStock = (new CreateProductStock())->execute($data);

        $this->assertInstanceOf(ProductStock::class, $productStock);
        $this->seeInDatabase('product_stocks', ['product_id' => $product->id]);
    }

    public function testCreateMultipleProductStocks()
    {
        // TODO: this could be done with a data provider
        $codes = ['123456', 'abcdef', '987654'];
        foreach ($codes as $code) {
            $this->addProduct($code);
            $data[] = [
                'product_code'    => $code,
                'on_hand'         => 1,
                'production_date' => '26/08/2020',
            ];
        }

        $added = (new CreateProductStock())->executeBulk($data);
        $this->assertEquals(3, $added);
        foreach ($codes as $code) {
            $this->seeInDatabase('products', ['code' => $code]);
        }
    }
}
