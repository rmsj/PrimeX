<?php

namespace PrimeX\Packages\Core\Console;

use Illuminate\Console\Command;
use PrimeX\Packages\Features\Products\Actions\CreateProduct;

class BulkAddProductsCommand extends Command
{

    use HandleCSV;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'primex:add-products {csv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add products on database based on a provided CSV file.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->keys = [
            'code',
            'name',
            'description'
        ];

        $this->handleCSV($this->argument('csv'));

        if (empty($this->lines)) {
            return $this->error("Something went wrong");
        }

        if ($products = (new CreateProduct())->executeBulk($this->lines)) {
            return $this->comment("Success. Added $products records to database");
        }
    }
}
