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
        Route::get('/new', 'Manage\OrderController@new')->name('orders.new');
        Route::post('/store', 'Manage\OrderController@store')->name('orders.store');
        Route::post('/delete', 'Manage\OrderController@delete')->name('orders.delete');
        Route::get('/list', 'Manage\OrderController@showList')->name('orders.list');
    });
});
