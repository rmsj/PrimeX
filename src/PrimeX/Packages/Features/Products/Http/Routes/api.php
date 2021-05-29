<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->group([
    'prefix' => 'api/v1/',
    'name' => 'api.',
], function () use ($router) {

    $router->get('products/{id}', [
        'as' => 'products.show', 'uses' => 'ProductController@show'
    ]);

    $router->put('products/{id}', [
        'as' => 'products.update', 'uses' => 'ProductController@update'
    ]);

    $router->delete('products/{id}', [
        'as' => 'products.destroy', 'uses' => 'ProductController@destroy'
    ]);

    $router->post('products', [
        'as' => 'products.create', 'uses' => 'ProductController@store'
    ]);

    $router->get('products', [
        'as' => 'products.all', 'uses' => 'ProductController@index'
    ]);
});
