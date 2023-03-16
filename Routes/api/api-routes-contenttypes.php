<?php

/*
 * API url will be: <base-url>/public/api/cms/contenttypes
 */
Route::group(
    [
        'prefix' => 'cms/contenttypes',
        'namespace' => 'Backend',
    ],
function () {

    /**
     * Get Assets
     */
    Route::get('/assets', 'ContentTypesController@getAssets')
        ->name('vh.backend.cms.api.contenttypes.assets');
    /**
     * Get List
     */
    Route::get('/', 'ContentTypesController@getList')
        ->name('vh.backend.cms.api.contenttypes.list');
    /**
     * Update List
     */
    Route::match(['put', 'patch'], '/', 'ContentTypesController@updateList')
        ->name('vh.backend.cms.api.contenttypes.list.update');
    /**
     * Delete List
     */
    Route::delete('/', 'ContentTypesController@deleteList')
        ->name('vh.backend.cms.api.contenttypes.list.delete');


    /**
     * Create Item
     */
    Route::post('/', 'ContentTypesController@createItem')
        ->name('vh.backend.cms.api.contenttypes.create');
    /**
     * Get Item
     */
    Route::get('/{id}', 'ContentTypesController@getItem')
        ->name('vh.backend.cms.api.contenttypes.read');
    /**
     * Update Item
     */
    Route::match(['put', 'patch'], '/{id}', 'ContentTypesController@updateItem')
        ->name('vh.backend.cms.api.contenttypes.update');
    /**
     * Delete Item
     */
    Route::delete('/{id}', 'ContentTypesController@deleteItem')
        ->name('vh.backend.cms.api.contenttypes.delete');

    /**
     * List Actions
     */
    Route::any('/action/{action}', 'ContentTypesController@listAction')
        ->name('vh.backend.cms.api.contenttypes.list.action');

    /**
     * Item actions
     */
    Route::any('/{id}/action/{action}', 'ContentTypesController@itemAction')
        ->name('vh.backend.cms.api.contenttypes.item.action');



});
