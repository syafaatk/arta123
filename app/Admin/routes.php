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
    $router->get('/client/masters/create', 'ClientController@create')->name('client.masters.create');
    $router->post('/client/masters', 'ClientController@store')->name('client.masters.store');
    $router->get('/client/masters/{id}', 'ClientController@show')->name('client.masters.show');
    $router->get('/client/masters/{id}/edit', 'ClientController@edit')->name('client.masters.edit');
    $router->put('/client/masters/{id}', 'ClientController@update')->name('client.masters.update');
    $router->delete('/client/masters/{id}', 'ClientController@destroy')->name('client.masters');
    

    $router->get('/ekualisasi/item', 'EkualisasiitemController@index')->name('ekualisasi.item');
    $router->get('/ekualisasi/item/create', 'EkualisasiitemController@create')->name('ekualisasi.item.create');
    $router->post('/ekualisasi/item', 'EkualisasiitemController@store')->name('ekualisasi.item.store');
    $router->get('/ekualisasi/item/{id}', 'EkualisasiitemController@show')->name('ekualisasi.item.show');
    $router->get('/ekualisasi/item/{id}/edit', 'EkualisasiitemController@edit')->name('ekualisasi.item.edit');
    $router->put('/ekualisasi/item/{id}', 'EkualisasiitemController@update')->name('ekualisasi.item.update');
    $router->delete('/ekualisasi/item/{id}', 'EkualisasiitemController@destroy')->name('ekualisasi.item');

    $router->get('/ekualisasi/detail', 'EkualisasidetailController@index')->name('ekualisasi.detail');
    $router->get('/ekualisasi/detail/create', 'EkualisasidetailController@create')->name('ekualisasi.detail.create');
    $router->post('/ekualisasi/detail', 'EkualisasidetailController@store')->name('ekualisasi.detail.store');
    $router->get('/ekualisasi/detail/process/{id}', 'EkualisasidetailController@processItemPemeriksaan')->name('ekualisasi.detail.process');
    $router->get('/ekualisasi/detail/{id}', 'EkualisasidetailController@show')->name('ekualisasi.detail.show');
    $router->get('/ekualisasi/detail/{id}/edit', 'EkualisasidetailController@edit')->name('ekualisasi.detail.edit');
    $router->put('/ekualisasi/detail/{id}', 'EkualisasidetailController@update')->name('ekualisasi.detail.update');
    $router->delete('/ekualisasi/detail/{id}', 'EkualisasidetailController@destroy')->name('ekualisasi.detail');
    $router->get('/ekualisasi/detail/process/{id1}/{id2}/{id3}', 'EkualisasidetailController@processItemPemeriksaan')->name('ekualisasi.detail.process');


    $router->get('/ekualisasi/masters', 'EkualisasiController@index')->name('ekualisasi.masters');
    $router->get('/ekualisasi/masters/create', 'EkualisasiController@create')->name('ekualisasi.masters.create');
    $router->post('/ekualisasi/masters', 'EkualisasiController@storeall')->name('ekualisasi.masters.store');
    $router->get('/ekualisasi/masters/{id}', 'EkualisasiController@show')->name('ekualisasi.masters.show');
    $router->get('/ekualisasi/masters/detail/{id}', 'EkualisasiController@detailall')->name('ekualisasi.masters.detailall');
    $router->get('/ekualisasi/masters/{id}/edit', 'EkualisasiController@edit')->name('ekualisasi.masters.edit');
    $router->put('/ekualisasi/masters/{id}', 'EkualisasiController@updateall')->name('ekualisasi.masters.update');
    $router->delete('/ekualisasi/masters/{id}', 'EkualisasiController@destroy')->name('ekualisasi.masters');
    $router->get('/ekualisasi/masters/process/{id}', 'EkualisasiController@processItemPemeriksaan')->name('ekualisasi.masters.process');

    $router->get('/ekualisasi/tahunan', 'EkualisasitahunanController@index')->name('ekualisasi.tahunan');
    $router->get('/ekualisasi/tahunan/create', 'EkualisasitahunanController@create')->name('ekualisasi.tahunan.create');
    $router->post('/ekualisasi/tahunan', 'EkualisasitahunanController@store')->name('ekualisasi.tahunan.store');
    $router->get('/ekualisasi/tahunan/{id}', 'EkualisasitahunanController@show')->name('ekualisasi.tahunan.show');
    $router->get('/ekualisasi/tahunan/{id}/edit', 'EkualisasitahunanController@edit')->name('ekualisasi.tahunan.edit');
    $router->put('/ekualisasi/tahunan/{id}', 'EkualisasitahunanController@update')->name('ekualisasi.tahunan.update');
    $router->delete('/ekualisasi/tahunan/{id}', 'EkualisasitahunanController@destroy')->name('ekualisasi.tahunan');


    $router->get('/ekualisasi/tahunan-detail', 'EkualisasidetailtahunanController@index')->name('ekualisasi.tahunan-detail');
    $router->get('/ekualisasi/tahunan-detail/create', 'EkualisasidetailtahunanController@create')->name('ekualisasi.tahunan-detail.create');
    $router->post('/ekualisasi/tahunan-detail', 'EkualisasidetailtahunanController@store')->name('ekualisasi.tahunan-detail.store');
    $router->get('/ekualisasi/tahunan-detail/{id}', 'EkualisasidetailtahunanController@show')->name('ekualisasi.tahunan-detail.show');
    $router->get('/ekualisasi/tahunan-detail/{id}/edit', 'EkualisasidetailtahunanController@edit')->name('ekualisasi.tahunan-detail.edit');
    $router->put('/ekualisasi/tahunan-detail/{id}', 'EkualisasidetailtahunanController@update')->name('ekualisasi.tahunan-detail.update');
    $router->delete('/ekualisasi/tahunan-detail/{id}', 'EkualisasidetailtahunanController@destroy')->name('ekualisasi.tahunan-detail');

    $router->get('/pajak/klu', 'KluController@index')->name('pajak.klu');
    $router->get('/pajak/klu/create', 'KluController@create')->name('pajak.klu.create');
    $router->post('/pajak/klu', 'KluController@store')->name('pajak.klu.store');
    $router->get('/pajak/klu/{id}', 'KluController@show')->name('pajak.klu.show');
    $router->get('/pajak/klu/{id}/edit', 'KluController@edit')->name('pajak.klu.edit');
    $router->put('/pajak/klu/{id}', 'KluController@update')->name('pajak.klu.update');
    $router->delete('/pajak/klu/{id}', 'KluController@destroy')->name('pajak.klu');

    $router->get('/pajak/masa-pajak', 'MasapajakController@index')->name('pajak.masa-pajak');
    $router->get('/pajak/masa-pajak/create', 'MasapajakController@create')->name('pajak.masa-pajak.create');
    $router->post('/pajak/masa-pajak', 'MasapajakController@store')->name('pajak.masa-pajak.store');
    $router->get('/pajak/masa-pajak/{id}', 'MasapajakController@show')->name('pajak.masa-pajak.show');
    $router->get('/pajak/masa-pajak/{id}/edit', 'MasapajakController@edit')->name('pajak.masa-pajak.edit');
    $router->put('/pajak/masa-pajak/{id}', 'MasapajakController@update')->name('pajak.masa-pajak.update');
    $router->delete('/pajak/masa-pajak/{id}', 'MasapajakController@destroy')->name('pajak.masa-pajak');

    $router->get('/pajak/kpp', 'KppController@index')->name('pajak.kpp');
    $router->get('/pajak/kpp/create', 'KppController@create')->name('pajak.kpp.create');
    $router->post('/pajak/kpp', 'KppController@store')->name('pajak.kpp.store');
    $router->get('/pajak/kpp/{id}', 'KppController@show')->name('pajak.kpp.show');
    $router->get('/pajak/kpp/{id}/edit', 'KppController@edit')->name('pajak.kpp.edit');
    $router->put('/pajak/kpp/{id}', 'KppController@update')->name('pajak.kpp.update');
    $router->delete('/pajak/kpp/{id}', 'KppController@destroy')->name('pajak.kpp');

    $router->get('/pajak/kppar', 'KpparController@index')->name('pajak.kppar');
    $router->get('/pajak/kppar/create', 'KpparController@create')->name('pajak.kppar.create');
    $router->post('/pajak/kppar', 'KpparController@store')->name('pajak.kppar.store');
    $router->get('/pajak/kppar/{id}', 'KpparController@show')->name('pajak.kppar.show');
    $router->get('/pajak/kppar/{id}/edit', 'KpparController@edit')->name('pajak.kppar.edit');
    $router->put('/pajak/kppar/{id}', 'KpparController@update')->name('pajak.kppar.update');
    $router->delete('/pajak/kppar/{id}', 'KpparController@destroy')->name('pajak.kppar');

    $router->get('/laru', 'LaruController@index')->name('laru');
    $router->get('/laru/create', 'LaruController@create')->name('laru.create');
    $router->post('/laru', 'LaruController@storeall')->name('laru.store');
    $router->get('/laru/{id}', 'LaruController@show')->name('laru.show');
    $router->get('/laru/{id}/edit', 'LaruController@edit')->name('laru.edit');
    $router->put('/laru/{id}', 'LaruController@update')->name('laru.update');
    $router->delete('/laru/{id}', 'LaruController@destroy')->name('laru');

    $router->get('/larudetail', 'LarudetailController@index')->name('larudetail');
    $router->get('/larudetail/create', 'LarudetailController@create')->name('larudetail.create');
    $router->post('/larudetail', 'LarudetailController@store')->name('larudetail.store');
    $router->get('/larudetail/{id}', 'LarudetailController@show')->name('larudetail.show');
    $router->get('/larudetail/{id}/edit', 'LarudetailController@edit')->name('larudetail.edit');
    $router->put('/larudetail/{id}', 'LarudetailController@update')->name('larudetail.update');
    $router->delete('/larudetail/{id}', 'LarudetailController@destroy')->name('larudetail');

    $router->get('/laruitem', 'LaruitemController@index')->name('laruitem');
    $router->get('/laruitem/create', 'LaruitemController@create')->name('laruitem.create');
    $router->post('/laruitem', 'LaruitemController@store')->name('laruitem.store');
    $router->get('/laruitem/{id}', 'LaruitemController@show')->name('laruitem.show');
    $router->get('/laruitem/{id}/edit', 'LaruitemController@edit')->name('laruitem.edit');
    $router->put('/laruitem/{id}', 'LaruitemController@update')->name('laruitem.update');
    $router->delete('/laruitem/{id}', 'LaruitemController@destroy')->name('laruitem');
    
});
