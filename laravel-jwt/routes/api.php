<?php

use Illuminate\Support\Facades\Route;

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

Route::group([
    'prefix' => 'auth'
], function () {
    //Auth
    Route::post('register', 'API\AuthController@register');
    Route::post('login', 'API\AuthController@login');
    Route::post('logout', 'API\AuthController@logout');
    Route::post('refresh', 'API\AuthController@refresh');
    Route::post('get-user', 'API\AuthController@me');
});

Route::middleware('jwt')->group(function () {
   Route::get('test',function (){
       return response()->json(["role" => auth()->user()->hasRole('customer') ? 'customer' : 'not a customer']);
   });
});
