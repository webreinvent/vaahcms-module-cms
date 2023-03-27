<?php

Route::group(
    [
        'prefix' => 'backend/cms/contenttypes',

        'middleware' => ['web', 'has.backend.access'],

        'namespace' => 'Backend',
    ],
//    function () {
//        //---------------------------------------------------------
//        Route::post('/upload', 'MediaController@upload')
//            ->name('backend.cms.media.upload');
//        //---------------------------------------------------------
//    },
function () {
    /**
     * Get Assets
     */
    Route::get('/assets', 'ContentTypesController@getAssets')
        ->name('vh.backend.cms.contenttypes.assets');
    /**
     * Get List
     */
    Route::get('/', 'ContentTypesController@getList')
        ->name('vh.backend.cms.contenttypes.list');
    /**
     * Update List
     */
    Route::match(['put', 'patch'], '/', 'ContentTypesController@updateList')
        ->name('vh.backend.cms.contenttypes.list.update');
    /**
     * Delete List
     */
    Route::delete('/', 'ContentTypesController@deleteList')
        ->name('vh.backend.cms.contenttypes.list.delete');


    /**
     * Create Item
     */
    Route::post('/', 'ContentTypesController@createItem')
        ->name('vh.backend.cms.contenttypes.create');
    /**
     * Get Item
     */
    Route::get('/{id}', 'ContentTypesController@getItem')
        ->name('vh.backend.cms.contenttypes.read');
    /**
     * Get Content Structure
     */
    Route::get('/relations/{id}', 'ContentTypesController@getItemRelations')
        ->name('backend.cms.content.types.item.relations');
    /**
     * Update Item
     */
    Route::match(['put', 'patch'], '/{id}', 'ContentTypesController@updateItem')
        ->name('vh.backend.cms.contenttypes.update');
    /**
     * Delete Item
     */
    Route::delete('/{id}', 'ContentTypesController@deleteItem')
        ->name('vh.backend.cms.contenttypes.delete');

    /**
     * List Actions
     */
    Route::any('/action/{action}', 'ContentTypesController@listAction')
        ->name('vh.backend.cms.contenttypes.list.actions');

    /**
     * Item actions
     */
    Route::any('/{id}/action/{action}', 'ContentTypesController@itemAction')
        ->name('vh.backend.cms.contenttypes.item.action');
    /**
     * Store group
     */
    Route::post('/store/{id}/groups', 'ContentTypesController@postStoreGroups')
        ->name('backend.cms.content.types.store.groups');

    //---------------------------------------------------------

});
