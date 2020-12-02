<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/me', function (Request $request) {
    return $request->user();
});

Route::post('signup','Auth\RegisterController@store');
Route::post('login','Auth\LoginController@login');

Route::post('productFilter', 'ProductController@productFilter');


Route::group(['middleware' => 'auth:api'], function () {
    Route::resource('payment','PaymentController');
    Route::post('pay','PaymentGatewayController@index');
    Route::resource('product', 'ProductController');
    Route::resource('store', 'StoreController');
    Route::resource('extra', 'ExtraController');
    Route::resource('ingredient', 'IngredientController');
    Route::resource('category', 'ProductCategoryController');
    Route::resource('role', 'RoleController');
    Route::resource('notification', 'NotificationController');
    Route::resource('favorite', 'FavoriteController');
    Route::resource('user', 'UserController');
    Route::get('userData', 'UserController@currentUser');
    Route::put('userEdit', 'UserController@update');
    Route::put('userRole', 'UserController@changeRole');
    Route::get('users', 'UserController@allUsers');
});