<?php

use Illuminate\Http\Request;
////원래는 아래 였으나 “Non-static method Illuminate\Routing\Route::middleware() should not be
////called statically in file /home/vagrant/Code/laravel6x/laravel6x/routes/api.php on line 17“
////error가 발생하여 "Illuminate\Support\Facades\Route"으로 바꾸었다.

//use Illuminate\Routing\Route;//origin

use Illuminate\Support\Facades\Route; ////

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->post('/logout', 'AuthController@logout');
Route::get('/login','AuthController@showLogin');
Route::post('/login','AuthController@login');
Route::post('/register','AuthController@register');
