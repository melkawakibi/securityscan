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

Route::get('/');

Route::post('request_scan', 'ScanController@setupScan');

Route::post('register', 'ScanController@store');

Route::post('auth', 'ScanController@authenticate');

Route::get('report', 'PDFController@getReport');

Auth::routes();

Route::get('/admin', 'AdminController@index');

Route::get('customers', ['as' => 'customers', 'uses' => 'AdminController@showCustomers']);

Route::get('reports', ['as' => 'reports', 'uses' => 'AdminController@showReports']);

Route::get('active/{id}', 'AdminController@updateActiveState');
