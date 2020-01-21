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


Route::group(['prefix' => 'admincp'], function() {
    Route::get('/', function () {
        return view('admincp.index');
    })->middleware('checkLogin');
    Route::get('/login', function () {
        return view('admincp.login');
    });
    Route::get('{any}', function () {
	    return view('admincp.index');
	})->where(['any' => '.*'])->middleware('checkLogin');

});
