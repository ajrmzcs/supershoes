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

Route::middleware(['auth.basic.once'])->group(function () {
   Route::apiResource('/stores', 'StoreController');
   
   Route::get('/articles/stores/{store}', 'ArticleController@getArticlesByStore');
   Route::apiResource('/articles', 'ArticleController');
});
