<?php

namespace PrimeX\Packages\Core\Console;

use Illuminate\Console\Command;
use PrimeX\Packages\Features\Products\Actions\CreateProductStock;

class BulkAddProductStockCommand extends Command
{

    use HandleCSV;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'primex:add-product-stock {csv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add product stock entries in database based on a provided CSV file.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->keys = [
            'product_code',
            'on_hand',
            'production_date'
        ];

        $this->handleCSV($this->argument('csv'));

        if (empty($this->lines)) {
            return $this->error("Something went wrong");
        }

        if ($stock = (new CreateProductStock())->executeBulk($this->lines)) {
            return $this->comment("Success. Added $stock product stock records to database");
        }
    }
}
