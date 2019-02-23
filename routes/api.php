<?php

use Illuminate\Http\Request;

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

Route::group([
    'middleware' => 'acl',
    'roles' => ['admin', 'moderator']
], function () {
    Route::resource('categories', 'CategoriesController');
    Route::get('categories-leveled', 'CategoriesController@getLeveledTree');
    Route::get('categories-nested', 'CategoriesController@getNestedTree');
});

Route::group([
    'middleware' => 'acl',
    'roles' => ['admin']
], function () {
    Route::resource('products', 'ProductsController');
});