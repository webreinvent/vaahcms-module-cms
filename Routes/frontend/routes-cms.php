<?php

/*
|--------------------------------------------------------------------------
| Content Routes
|--------------------------------------------------------------------------
|
| These routes should be kept in the end of the file
|
*/

Route::group(
    [
        'middleware' => ['web', 'set.content'],
        'namespace' => 'Frontend',
    ],
    function () {
        //------------------------------------------------
        Route::get( '/{permalink}', 'PublicController@page' )
            ->name( 'vh.cms.page' );
        //------------------------------------------------
        Route::get( '/{content_type}/{permalink}', 'PublicController@content' )
            ->name( 'vh.cms.content' );
        //------------------------------------------------
    });




Route::group(
    [
        'middleware' => ['web'],
        'namespace' => 'Frontend',
    ],
    function () {
        //------------------------------------------------
        Route::get( '/taxonomies/{taxonomy_type_slug}/{taxonomy_slug}',
            'PublicController@taxonomyContents' )
            ->name( 'vh.cms.taxonomy-content' );
        //------------------------------------------------
        Route::get( '/search',
            'PublicController@searchContents' )
            ->name( 'vh.cms.search-content' );
        //------------------------------------------------
    });
