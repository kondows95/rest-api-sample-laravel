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

Route::group([], function () {
    Route::get('/', function () {
        return "This endpoint is no problem.";
    });
    
    Route::ApiResource(
        'items', 
        'ItemsController', 
        ['except' => ['show', 'create', 'edit']]
    );
    Route::ApiResource(
        'categories', 
        'CategoriesController', 
        ['except' => ['show', 'create', 'edit']]
    );
});
