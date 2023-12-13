<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->get('/client/masters', 'ClientController@index')->name('client.masters');
    $router->get('/client/masters/create', 'ClientController@create')->name('client.masters.craete');
    $router->post('/client/masters', 'ClientController@store')->name('client.masters.store');
    $router->get('/client/masters/{id}', 'ClientController@show')->name('client.masters.show');
    $router->get('/client/masters/{id}/edit', 'ClientController@edit')->name('client.masters.edit');
    $router->put('/client/masters/{id}', 'ClientController@update')->name('client.masters.update');
    $router->delete('/client/masters/{id}', 'ClientController@destroy')->name('client.masters');

    $router->get('/pajak/klu', 'KluController@index')->name('pajak.klu');
    $router->get('/pajak/klu/create', 'KluController@create')->name('pajak.klu.craete');
    $router->post('/pajak/klu', 'KluController@store')->name('pajak.klu.store');
    $router->get('/pajak/klu/{id}', 'KluController@show')->name('pajak.klu.show');
    $router->get('/pajak/klu/{id}/edit', 'KluController@edit')->name('pajak.klu.edit');
    $router->put('/pajak/klu/{id}', 'KluController@update')->name('pajak.klu.update');
    $router->delete('/pajak/klu/{id}', 'KluController@destroy')->name('pajak.klu');
});
