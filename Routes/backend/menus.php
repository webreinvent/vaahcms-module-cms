<?php



Route::group(
    [
        'prefix' => 'backend/cms/menus',
        'namespace'  => 'Backend',
        'middleware' => ['web', 'has.backend.access'],
    ],
    function () {
        //---------------------------------------------------------
        Route::get('/assets', 'MenusController@getAssets')
            ->name('backend.cms.menus.assets');
        //---------------------------------------------------------
        Route::post('/create', 'MenusController@postCreate')
            ->name('backend.cms.menus.create');
        //---------------------------------------------------------
        Route::post('/content/list', 'MenusController@getContentList')
            ->name('backend.cms.menus.content.list');
        //---------------------------------------------------------
        Route::post('/item/{id}', 'MenusController@getItem')
            ->name('backend.cms.menus.item');
        //---------------------------------------------------------
        Route::post('/item/{id}/store', 'MenusController@postStore')
            ->name('backend.cms.menus.store');
        //---------------------------------------------------------
        Route::any('/item/{id}/items', 'MenusController@getMenuItems')
            ->name('backend.cms.menus.items');
        //---------------------------------------------------------
        Route::post('/actions/{action_name}', 'MenusController@postActions')
            ->name('backend.cms.menus.actions');
        //---------------------------------------------------------
    });
