<?php

Route::group(
    [
        'prefix' => 'backend/cms/menus',
        
        'middleware' => ['web', 'has.backend.access'],
        
        'namespace' => 'Backend',
],
function () {
    /**
     * Get Assets
     */
    Route::get('/assets', 'MenusController@getAssets')
        ->name('vh.backend.cms.menus.assets');
    /**
     * Get List
     */
    Route::get('/', 'MenusController@getList')
        ->name('vh.backend.cms.menus.list');
    /**
     * Update List
     */
    Route::match(['put', 'patch'], '/', 'MenusController@updateList')
        ->name('vh.backend.cms.menus.list.update');
    /**
     * Delete List
     */
    Route::delete('/', 'MenusController@deleteList')
        ->name('vh.backend.cms.menus.list.delete');


    /**
     * Create Item
     */
    Route::post('/', 'MenusController@createItem')
        ->name('vh.backend.cms.menus.create');
    /**
     * Get Item
     */
    Route::get('/{id}', 'MenusController@getItem')
        ->name('vh.backend.cms.menus.read');
    /**
     * Update Item
     */
    Route::match(['put', 'patch'], '/{id}', 'MenusController@updateItem')
        ->name('vh.backend.cms.menus.update');
    /**
     * Delete Item
     */
    Route::delete('/{id}', 'MenusController@deleteItem')
        ->name('vh.backend.cms.menus.delete');

    /**
     * List Actions
     */
    Route::any('/action/{action}', 'MenusController@listAction')
        ->name('vh.backend.cms.menus.list.actions');

    /**
     * Item actions
     */
    Route::any('/{id}/action/{action}', 'MenusController@itemAction')
        ->name('vh.backend.cms.menus.item.action');

    //---------------------------------------------------------

});
