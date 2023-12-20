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

    $router->get('/pemeriksaan/item', 'PemeriksaanItemController@index')->name('pemeriksaan.item');
    $router->get('/pemeriksaan/item/create', 'PemeriksaanItemController@create')->name('pemeriksaan.item.craete');
    $router->post('/pemeriksaan/item', 'PemeriksaanItemController@store')->name('pemeriksaan.item.store');
    $router->get('/pemeriksaan/item/{id}', 'PemeriksaanItemController@show')->name('pemeriksaan.item.show');
    $router->get('/pemeriksaan/item/{id}/edit', 'PemeriksaanItemController@edit')->name('pemeriksaan.item.edit');
    $router->put('/pemeriksaan/item/{id}', 'PemeriksaanItemController@update')->name('pemeriksaan.item.update');
    $router->delete('/pemeriksaan/item/{id}', 'PemeriksaanItemController@destroy')->name('pemeriksaan.masters');

    $router->get('/pemeriksaan/detail', 'PemeriksaandetailController@index')->name('pemeriksaan.detail');
    $router->get('/pemeriksaan/detail/create', 'PemeriksaandetailController@create')->name('pemeriksaan.detail.craete');
    $router->post('/pemeriksaan/detail', 'PemeriksaandetailController@store')->name('pemeriksaan.detail.store');
    $router->get('/pemeriksaan/detail/{id}', 'PemeriksaandetailController@show')->name('pemeriksaan.detail.show');
    $router->get('/pemeriksaan/detail/{id}/edit', 'PemeriksaandetailController@edit')->name('pemeriksaan.detail.edit');
    $router->put('/pemeriksaan/detail/{id}', 'PemeriksaandetailController@update')->name('pemeriksaan.detail.update');
    $router->delete('/pemeriksaan/detail/{id}', 'PemeriksaandetailController@destroy')->name('pemeriksaan.masters');

    $router->get('/pemeriksaan/masters', 'PemeriksaanController@index')->name('pemeriksaan.masters');
    $router->get('/pemeriksaan/masters/create', 'PemeriksaanController@create')->name('pemeriksaan.masters.craete');
    $router->post('/pemeriksaan/masters', 'PemeriksaanController@storeall')->name('pemeriksaan.masters.store');
    $router->get('/pemeriksaan/masters/{id}', 'PemeriksaanController@show')->name('pemeriksaan.masters.show');
    $router->get('/pemeriksaan/masters/detail/{id}', 'PemeriksaanController@detailall')->name('pemeriksaan.masters.detailall');
    $router->get('/pemeriksaan/masters/{id}/edit', 'PemeriksaanController@edit')->name('pemeriksaan.masters.edit');
    $router->put('/pemeriksaan/masters/{id}', 'PemeriksaanController@updateall')->name('pemeriksaan.masters.update');
    $router->delete('/pemeriksaan/masters/{id}', 'PemeriksaanController@destroy')->name('pemeriksaan.masters');

    $router->get('/pajak/klu', 'KluController@index')->name('pajak.klu');
    $router->get('/pajak/klu/create', 'KluController@create')->name('pajak.klu.craete');
    $router->post('/pajak/klu', 'KluController@store')->name('pajak.klu.store');
    $router->get('/pajak/klu/{id}', 'KluController@show')->name('pajak.klu.show');
    $router->get('/pajak/klu/{id}/edit', 'KluController@edit')->name('pajak.klu.edit');
    $router->put('/pajak/klu/{id}', 'KluController@update')->name('pajak.klu.update');
    $router->delete('/pajak/klu/{id}', 'KluController@destroy')->name('pajak.klu');

    $router->get('/pajak/masa-pajak', 'MasapajakController@index')->name('pajak.masa-pajak');
    $router->get('/pajak/masa-pajak/create', 'MasapajakController@create')->name('pajak.masa-pajak.craete');
    $router->post('/pajak/masa-pajak', 'MasapajakController@store')->name('pajak.masa-pajak.store');
    $router->get('/pajak/masa-pajak/{id}', 'MasapajakController@show')->name('pajak.masa-pajak.show');
    $router->get('/pajak/masa-pajak/{id}/edit', 'MasapajakController@edit')->name('pajak.masa-pajak.edit');
    $router->put('/pajak/masa-pajak/{id}', 'MasapajakController@update')->name('pajak.masa-pajak.update');
    $router->delete('/pajak/masa-pajak/{id}', 'MasapajakController@destroy')->name('pajak.masa-pajak');

    $router->get('/pajak/kpp', 'KppController@index')->name('pajak.kpp');
    $router->get('/pajak/kpp/create', 'KppController@create')->name('pajak.kpp.craete');
    $router->post('/pajak/kpp', 'KppController@store')->name('pajak.kpp.store');
    $router->get('/pajak/kpp/{id}', 'KppController@show')->name('pajak.kpp.show');
    $router->get('/pajak/kpp/{id}/edit', 'KppController@edit')->name('pajak.kpp.edit');
    $router->put('/pajak/kpp/{id}', 'KppController@update')->name('pajak.kpp.update');
    $router->delete('/pajak/kpp/{id}', 'KppController@destroy')->name('pajak.kpp');

    $router->get('/pajak/kppar', 'KpparController@index')->name('pajak.kppar');
    $router->get('/pajak/kppar/create', 'KpparController@create')->name('pajak.kppar.craete');
    $router->post('/pajak/kppar', 'KpparController@store')->name('pajak.kppar.store');
    $router->get('/pajak/kppar/{id}', 'KpparController@show')->name('pajak.kppar.show');
    $router->get('/pajak/kppar/{id}/edit', 'KpparController@edit')->name('pajak.kppar.edit');
    $router->put('/pajak/kppar/{id}', 'KpparController@update')->name('pajak.kppar.update');
    $router->delete('/pajak/kppar/{id}', 'KpparController@destroy')->name('pajak.kppar');
    
});
