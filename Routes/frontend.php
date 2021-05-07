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
        'prefix'     => 'cms',
        'middleware' => ['web'],
        'namespace' => 'Frontend',
    ],
    function () {
        //------------------------------------------------
        Route::get( '/', 'PublicController@index' )
        ->name( 'vh.public.cms' );
        //------------------------------------------------
    });


/*Route::group(
    [
        'middleware' => ['web'],
        'namespace' => 'Frontend',
    ],
    function () {
        //------------------------------------------------
        Route::get( '/test', 'PublicController@index' )
            ->name( 'vh.cms' );
        //------------------------------------------------
        //------------------------------------------------

        //------------------------------------------------
    });*/


include("frontend/routes-cms.php");
