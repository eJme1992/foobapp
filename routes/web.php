<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});


//RUTAS DEL API
Route::post('api/user/login','UserController@login');
Route::post('api/user/register','UserController@register');
Route::put('api/user/update','UserController@update');
//Clase aplicando api.Auth
Route::post('api/user/register/fileUser/{tipo}','UserController@fileUser')->middleware(App\Http\Middleware\ApiAuthMiddleware::class);
Route::get('api/user/getfileuser/{slug_user}/{tipo}','UserController@getFileUser');
Route::get('api/getfile/{filename}','UserController@getFile');
Route::get('api/getusuario/{slug_user}','UserController@getUsuario');