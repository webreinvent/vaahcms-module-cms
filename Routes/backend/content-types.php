<?php



Route::group(
    [
        'prefix' => 'backend/cms/content-types',
        'namespace'  => 'Backend',
        'middleware' => ['web', 'has.backend.access'],
    ],
    function () {
        //---------------------------------------------------------
        Route::get('/assets', 'ContentTypesController@getAssets')
            ->name('backend.cms.content.types.assets');
        //---------------------------------------------------------
        Route::post('/create', 'ContentTypesController@postCreate')
            ->name('backend.cms.content.types.create');
        //---------------------------------------------------------
        Route::get('/list', 'ContentTypesController@getList')
            ->name('backend.cms.content.types.list');
        //---------------------------------------------------------
        Route::get('/item/{id}', 'ContentTypesController@getItem')
            ->name('backend.cms.content.types.item');
        //---------------------------------------------------------
        Route::post('/item/{id}/relations', 'ContentTypesController@getItemRelations')
            ->name('backend.cms.content.types.item.relations');
        //---------------------------------------------------------
        Route::post('/store/{id}', 'ContentTypesController@postStore')
            ->name('backend.cms.content.types.store');
        //---------------------------------------------------------
        Route::post('/store/{id}/groups', 'ContentTypesController@postStoreGroups')
            ->name('backend.cms.content.types.store.groups');
        //---------------------------------------------------------
        Route::post('/actions/{action_name}', 'ContentTypesController@postActions')
            ->name('backend.cms.content.types.actions');
        //---------------------------------------------------------
        Route::get('/getModuleSections', 'ContentTypesController@getModuleSections')
            ->name('backend.cms.content.types.module-section');
        //---------------------------------------------------------
    });
