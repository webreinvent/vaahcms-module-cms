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
        Route::get( '/contents-types', 'ContentTypesController@getContentTypeList' );
        //------------------------------------------------
        Route::get( '/contents-types/{slug}', 'ContentTypesController@getContentTypeItem' );
        //------------------------------------------------
        Route::get( '/contents/{plural_slug}', 'ContentsController@getContentList' );
        //------------------------------------------------
        Route::any( '/contents/{singular_slug}/{content_slug}', 'ContentsController@getContentItem' );
        //------------------------------------------------
    });
