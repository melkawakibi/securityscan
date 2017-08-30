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

Route::get('/', function(){

	return 'Security webserver';

});

Route::post('request_scan', 'ScanController@start');

Route::post('auth', 'ScanController@authenticate');