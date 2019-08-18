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

Route::get('/', 'SiswaController@index');

Route::post('/siswa-create', 'SiswaController@store')->name('si.create');

Route::get('/siswa/{id}', 'SiswaController@edit')->name('si.edit');

Route::post('/siswa-update/', 'SiswaController@update')->name('si.update');

Route::get('/siswa/delete/{id}', 'SiswaController@destroy')->name('si.delete');
