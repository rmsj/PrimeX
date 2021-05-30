<?php

namespace PrimeX\Packages\Core;

use Laravel\Lumen\Routing\Controller;
use PrimeX\Packages\Core\Traits\BaseController;

/**
 * Class AbstractController
 *
 * @package PrimeX\Packages\Core
 */
abstract class AbstractController extends Controller
{

    use BaseController;

    /**
     * AbstractAdminController constructor.
     */
    public function __construct()
    {
        // TODO: from here we can add auth, etc
        $this->middleware(['api']);
    }
}
