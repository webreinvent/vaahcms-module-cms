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
        'middleware' => ['auth:api'],
        'namespace' => 'Api',
    ],
    function () {
        //------------------------------------------------
        Route::any( '/contents-types', 'ContentTypesController@getContentTypeList' );
        //------------------------------------------------
        Route::any( '/contents-types/{column}/{value}', 'ContentTypesController@getContentTypeItem' );
        //------------------------------------------------
        Route::any( '/contents/{plural_slug}', 'ContentsController@getContentList' );
        //------------------------------------------------
        Route::any( '/contents/{singular_slug}/{content_slug}', 'ContentsController@getContentItem' );
        //------------------------------------------------
    });
