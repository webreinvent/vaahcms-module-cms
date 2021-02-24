<?php



Route::group(
    [
        'prefix' => 'backend/cms/blocks',
        'namespace'  => 'Backend',
        'middleware' => ['web', 'has.backend.access'],
    ],
    function () {
        //---------------------------------------------------------
        Route::get('/assets', 'BlocksController@getAssets')
            ->name('backend.cms.blocks.assets');
        //---------------------------------------------------------
        Route::post('/create', 'BlocksController@postCreate')
            ->name('backend.cms.blocks.create');
        //---------------------------------------------------------
        Route::get('/list', 'BlocksController@getList')
            ->name('backend.cms.blocks.list');
        //---------------------------------------------------------
        Route::get('/item/{id}', 'BlocksController@getItem')
            ->name('backend.cms.blocks.item');
        //---------------------------------------------------------
        Route::post('/item/{id}/relations', 'BlocksController@getItemRelations')
            ->name('backend.cms.blocks.item.relations');
        //---------------------------------------------------------
        Route::post('/store/{id}', 'BlocksController@postStore')
            ->name('backend.cms.blocks.store');
        //---------------------------------------------------------
        Route::post('/store/{id}/groups', 'BlocksController@postStoreGroups')
            ->name('backend.cms.blocks.store.groups');
        //---------------------------------------------------------
        Route::post('/actions/{action_name}', 'BlocksController@postActions')
            ->name('backend.cms.blocks.actions');
        //---------------------------------------------------------
        Route::get('/getModuleSections', 'BlocksController@getModuleSections')
            ->name('backend.cms.blocks.module-section');
        //---------------------------------------------------------
    });
