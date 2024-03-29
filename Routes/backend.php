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
        'prefix'     => 'backend/cms',
        'middleware' => ['web', 'has.backend.access'],
        'namespace' => 'Backend',
    ],
    function () {
        //------------------------------------------------
        Route::get( '/', 'DashboardController@index' )
            ->name( 'vh.backend.cms' );
        //------------------------------------------------
        Route::group(
            [
                'prefix'     => 'json',
            ],
            function () {
                //------------------------------------------------
                Route::post( '/assets', 'JsonController@getAssets' )
                    ->name( 'vh.backend.cms.json' );
                //------------------------------------------------
                //------------------------------------------------

                //------------------------------------------------
            });
        //------------------------------------------------
    });


Route::get( '/backend/json/getUserById/{id}', 'Backend\ContentsController@getUserById' )
    ->name( 'vh.backend.cms.getUserById' );

include('backend/routes-blocks.php');
include('backend/routes-contents.php');
include('backend/routes-menus.php');
include('backend/routes-contenttypes.php');
