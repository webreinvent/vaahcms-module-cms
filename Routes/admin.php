<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(
    [
        'prefix'     => 'admin/cms/pages',
        'middleware' => ['web', 'has.admin.access'],
        'namespace'  => 'Admin'
    ],
    function () {
        //------------------------------------------------
        //------------------------------------------------
        Route::get( '/', 'PageController@index' )
            ->name( 'vh.cms.pages' );
        //------------------------------------------------
        Route::any( '/assets', 'PageController@assets' )
            ->name( 'vh.cms.pages.assets' );
        //------------------------------------------------
        Route::any( '/custom/fields', 'PageController@getCustomFields' )
            ->name( 'vh.cms.custom.fields' );
        //------------------------------------------------
        Route::any( '/store', 'PageController@storePage' )
            ->name( 'vh.cms.pages.store' );
        //------------------------------------------------
        Route::any( '/list', 'PageController@getList' )
            ->name( 'vh.cms.pages.list' );
        //------------------------------------------------
        //------------------------------------------------
        //------------------------------------------------
    });
