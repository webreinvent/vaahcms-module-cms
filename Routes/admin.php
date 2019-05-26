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
        'namespace'  => 'VaahCms\Modules\Cms\Http\Controllers\Admin'
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
        //------------------------------------------------
        //------------------------------------------------
    });
