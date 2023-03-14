<?php

/*
 * API url will be: <base-url>/public/api/cms/contents
 */
Route::group(
    [
        'prefix' => 'cms/contents',
        'namespace' => 'Backend',
    ],
function () {

    /**
     * Get Assets
     */
    Route::get('/assets', 'ContentsController@getAssets')
        ->name('vh.backend.cms.api.contents.assets');
    /**
     * Get List
     */
    Route::get('/', 'ContentsController@getList')
        ->name('vh.backend.cms.api.contents.list');
    /**
     * Update List
     */
    Route::match(['put', 'patch'], '/', 'ContentsController@updateList')
        ->name('vh.backend.cms.api.contents.list.update');
    /**
     * Delete List
     */
    Route::delete('/', 'ContentsController@deleteList')
        ->name('vh.backend.cms.api.contents.list.delete');


    /**
     * Create Item
     */
    Route::post('/', 'ContentsController@createItem')
        ->name('vh.backend.cms.api.contents.create');
    /**
     * Get Item
     */
    Route::get('/{id}', 'ContentsController@getItem')
        ->name('vh.backend.cms.api.contents.read');
    /**
     * Update Item
     */
    Route::match(['put', 'patch'], '/{id}', 'ContentsController@updateItem')
        ->name('vh.backend.cms.api.contents.update');
    /**
     * Delete Item
     */
    Route::delete('/{id}', 'ContentsController@deleteItem')
        ->name('vh.backend.cms.api.contents.delete');

    /**
     * List Actions
     */
    Route::any('/action/{action}', 'ContentsController@listAction')
        ->name('vh.backend.cms.api.contents.list.action');

    /**
     * Item actions
     */
    Route::any('/{id}/action/{action}', 'ContentsController@itemAction')
        ->name('vh.backend.cms.api.contents.item.action');



});
