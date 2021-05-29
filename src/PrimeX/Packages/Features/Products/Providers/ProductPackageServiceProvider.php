<?php

namespace PrimeX\Packages\Features\Products\Providers;

use PrimeX\Packages\Core\AbstractPackageServiceProvider;

/**
 * Class ProductPackageServiceProvider
 *
 * @package PrimeX\Packages\Products\Providers
 */
class ProductPackageServiceProvider extends AbstractPackageServiceProvider
{

    /**
     * @inheritdoc
     */
    protected function getName(): string
    {
        return 'products';
    }

    /**
     * @inheritdoc
     */
    protected function getNamespace(): string
    {
        return 'PrimeX\Packages\Features\Products\Http\Controllers';
    }
}
