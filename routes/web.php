<?php

Route::group(['middleware'=>'auth'],function () {

    Route::get('/', 'InvoicesController@index');

    Route::get('/home', 'InvoicesController@index');

    Route::resource('invoices', 'InvoicesController');

    Route::resource('clients', 'ClientsController');

    Route::resource('logs', 'LogsController');
});

Auth::routes();
