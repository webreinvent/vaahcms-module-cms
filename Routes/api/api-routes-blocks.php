<?php

/*
 * API url will be: <base-url>/public/api/cms/blocks
 */
Route::group(
    [
        'prefix' => 'cms/blocks',
        'namespace' => 'Backend',
    ],
function () {

    /**
     * Get Assets
     */
    Route::get('/assets', 'BlocksController@getAssets')
        ->name('vh.backend.cms.api.blocks.assets');
    /**
     * Get List
     */
    Route::get('/', 'BlocksController@getList')
        ->name('vh.backend.cms.api.blocks.list');
    /**
     * Update List
     */
    Route::match(['put', 'patch'], '/', 'BlocksController@updateList')
        ->name('vh.backend.cms.api.blocks.list.update');
    /**
     * Delete List
     */
    Route::delete('/', 'BlocksController@deleteList')
        ->name('vh.backend.cms.api.blocks.list.delete');


    /**
     * Create Item
     */
    Route::post('/', 'BlocksController@createItem')
        ->name('vh.backend.cms.api.blocks.create');
    /**
     * Get Item
     */
    Route::get('/{id}', 'BlocksController@getItem')
        ->name('vh.backend.cms.api.blocks.read');
    /**
     * Update Item
     */
    Route::match(['put', 'patch'], '/{id}', 'BlocksController@updateItem')
        ->name('vh.backend.cms.api.blocks.update');
    /**
     * Delete Item
     */
    Route::delete('/{id}', 'BlocksController@deleteItem')
        ->name('vh.backend.cms.api.blocks.delete');

    /**
     * List Actions
     */
    Route::any('/action/{action}', 'BlocksController@listAction')
        ->name('vh.backend.cms.api.blocks.list.action');

    /**
     * Item actions
     */
    Route::any('/{id}/action/{action}', 'BlocksController@itemAction')
        ->name('vh.backend.cms.api.blocks.item.action');



});
