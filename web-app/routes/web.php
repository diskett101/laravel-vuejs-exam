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

Route::get('/', function (){
	return redirect('/user-list');
});
Route::get('/user-list', function () {
    return view('admin');
});
Route::get('/user/{any}', function () {
    return view('admin');
})->where('any', '.*');
Route::get('/login', function () {
    return view('admin');
})->where('any', '.*');

