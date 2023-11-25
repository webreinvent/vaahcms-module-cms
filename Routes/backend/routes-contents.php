<?php


Route::group(
    [
        'prefix' => 'backend/cms/media',
        'namespace'  => 'Backend',
        'middleware' => ['web', 'has.backend.access'],
    ],
    function () {
        //---------------------------------------------------------
        Route::post('/upload', 'MediaController@upload')
            ->name('backend.cms.media.upload');
        //---------------------------------------------------------
    });

Route::group(
    [
        'prefix' => 'backend/cms/contents/{content_type_slug}',

        'middleware' => ['web', 'has.backend.access', 'set.content.type'],

        'namespace' => 'Backend',
],
function () {
    /**
     * Get Assets
     */
    Route::get('/assets', 'ContentsController@getAssets')
        ->name('vh.backend.cms.contents.assets');
    /**
     * Get List
     */
    Route::get('/', 'ContentsController@getList')
        ->name('vh.backend.cms.contents.list');
    /**
     * Update List
     */

    Route::post('/getRelationsInTree', 'ContentsController@getRelationsInTree')
        ->name('backend.cms.contents.getRelationsInTree');
    //---------------------------------------------------------
    Route::match(['put', 'patch'], '/', 'ContentsController@updateList')
        ->name('vh.backend.cms.contents.list.update');
    /**
     * Delete List
     */
    Route::delete('/', 'ContentsController@deleteList')
        ->name('vh.backend.cms.contents.list.delete');


    /**
     * Create Item
     */
    Route::post('/', 'ContentsController@createItem')
        ->name('vh.backend.cms.contents.create');
    /**
     * Get Item
     */
    Route::get('/{id}', 'ContentsController@getItem')
        ->name('vh.backend.cms.contents.read');
    /**
     * Update Item
     */
    Route::match(['put', 'patch'], '/{id}', 'ContentsController@updateItem')
        ->name('vh.backend.cms.contents.update');
    /**
     * Delete Item
     */
    Route::delete('/{id}', 'ContentsController@deleteItem')
        ->name('vh.backend.cms.contents.delete');

    /**
     * List Actions
     */
    Route::any('/action/{action}', 'ContentsController@listAction')
        ->name('vh.backend.cms.contents.list.actions');

    /**
     * Item actions
     */
    Route::any('/{id}/action/{action}', 'ContentsController@itemAction')
        ->name('vh.backend.cms.contents.item.action');

    //---------------------------------------------------------

});
