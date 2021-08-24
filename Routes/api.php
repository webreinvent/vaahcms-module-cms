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

Route::group(
    [
        'prefix'     => 'cms',
        'namespace' => 'Api',
    ],
    function () {
        //------------------------------------------------
        Route::get( '/contents/{plural_slug}', 'ContentController@getContentList' );
        //------------------------------------------------
        Route::any( '/contents/{singular_slug}/{content_slug}', 'ContentController@getContentItem' );
        //------------------------------------------------
    });
