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
    
    Route::apiResource('items', 'ItemsController');
    Route::apiResource('categories', 'CategoriesController');
    Route::apiResource('orders', 'OrdersController')->only(['store']);
    Route::post('posttest', function (Request $request) {
        $data = $request->all();
        return response()->json($data);
    });
});
