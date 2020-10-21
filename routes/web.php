<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

Route::group(['prefix' => 'system',  'middleware' => 'auth'], function () {
    Route::get('/home', 'HomeController@index')->name('home');

    Route::prefix('orders')->group(function () {
        Route::get('/new', 'Management\OrderController@new')->name('orders.new');
        Route::post('/store', 'Management\OrderController@store')->name('orders.store');
        Route::post('/delete', 'Management\OrderController@delete')->name('orders.delete');
        Route::get('/list', 'Management\OrderController@showList')->name('orders.list');
    });

    Route::prefix('management')->group(function () {
        Route::prefix('client')->group(function () {
            Route::get('/view/{id?}', 'Management\ClientController@index')->name('management.client');
            Route::get('/list', 'Management\ClientController@list')->name('management.client.list');
            Route::post('/save', 'Management\ClientController@save')->name('management.client.save');
            Route::post('/delete', 'Management\ClientController@delete')->name('management.client.delete');
        });

        Route::prefix('payment_method')->group(function () {
            Route::get('/view/{id?}', 'Management\PaymentMethodController@index')->name('management.payment_method');
            Route::get('/list', 'Management\PaymentMethodController@list')->name('management.payment_method.list');
            Route::post('/save', 'Management\PaymentMethodController@save')->name('management.payment_method.save');
            Route::post('/delete', 'Management\PaymentMethodController@delete')->name('management.payment_method.delete');
        });
    });
});
