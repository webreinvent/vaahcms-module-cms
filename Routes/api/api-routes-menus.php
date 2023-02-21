<?php

/*
 * API url will be: <base-url>/public/api/cms/menus
 */
Route::group(
    [
        'prefix' => 'cms/menus',
        'namespace' => 'Backend',
    ],
function () {

    /**
     * Get Assets
     */
    Route::get('/assets', 'MenusController@getAssets')
        ->name('vh.backend.cms.api.menus.assets');
    /**
     * Get List
     */
    Route::get('/', 'MenusController@getList')
        ->name('vh.backend.cms.api.menus.list');
    /**
     * Update List
     */
    Route::match(['put', 'patch'], '/', 'MenusController@updateList')
        ->name('vh.backend.cms.api.menus.list.update');
    /**
     * Delete List
     */
    Route::delete('/', 'MenusController@deleteList')
        ->name('vh.backend.cms.api.menus.list.delete');


    /**
     * Create Item
     */
    Route::post('/', 'MenusController@createItem')
        ->name('vh.backend.cms.api.menus.create');
    /**
     * Get Item
     */
    Route::get('/{id}', 'MenusController@getItem')
        ->name('vh.backend.cms.api.menus.read');
    /**
     * Update Item
     */
    Route::match(['put', 'patch'], '/{id}', 'MenusController@updateItem')
        ->name('vh.backend.cms.api.menus.update');
    /**
     * Delete Item
     */
    Route::delete('/{id}', 'MenusController@deleteItem')
        ->name('vh.backend.cms.api.menus.delete');

    /**
     * List Actions
     */
    Route::any('/action/{action}', 'MenusController@listAction')
        ->name('vh.backend.cms.api.menus.list.action');

    /**
     * Item actions
     */
    Route::any('/{id}/action/{action}', 'MenusController@itemAction')
        ->name('vh.backend.cms.api.menus.item.action');



});
