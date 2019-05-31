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
        Route::any( '/{id}', 'PageController@getPageDetails' )
            ->name( 'vh.cms.pages.detail' );
        //------------------------------------------------
        Route::any( '/{id}/custom/fields', 'PageController@getPageCustomFields' )
            ->name( 'vh.cms.pages.detail.custom.fields' );
        //------------------------------------------------
    });



Route::group(
    [
        'prefix'     => 'admin/cms/menus',
        'middleware' => ['web', 'has.admin.access'],
        'namespace'  => 'Admin'
    ],
    function () {
        //------------------------------------------------
        //------------------------------------------------
        Route::get( '/', 'MenuController@index' )
            ->name( 'vh.cms.menus' );
        //------------------------------------------------
        Route::any( '/assets', 'MenuController@assets' )
            ->name( 'vh.cms.menus.assets' );
        //------------------------------------------------
        Route::any( '/list', 'MenuController@getList' )
            ->name( 'vh.cms.menus.list' );
        //------------------------------------------------
        Route::any( '/store', 'MenuController@store' )
            ->name( 'vh.cms.menus.store' );
        //------------------------------------------------
        Route::any( '/location/menus/{location_id}', 'MenuController@getLocationMenus' )
            ->name( 'vh.cms.menus.location' );
        //------------------------------------------------
        Route::any( '/items/{menu_id}', 'MenuController@getMenuItems' )
            ->name( 'vh.cms.menus.menu.items' );
        //------------------------------------------------
        Route::any( '/items/{menu_id}/store', 'MenuController@getMenuItemsStore' )
            ->name( 'vh.cms.menus.menu.items.store' );
        //------------------------------------------------
        //------------------------------------------------
    });
