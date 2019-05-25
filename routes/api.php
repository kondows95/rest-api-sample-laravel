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

//Dummy endpoint to make sure you can access from cross origin.
Route::group([], function () {
    Route::get('todos', function() {
        return response()->json(
            array('data'=> array(
                array('id'=>1, 'text'=>'test1', 'completed'=>false),
                array('id'=>2, 'text'=>'test2', 'completed'=>true)
            ))
        );
    });
});