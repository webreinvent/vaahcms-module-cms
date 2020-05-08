<?php



Route::group(
    [
        'prefix' => 'backend/cms/contents/{content_type_slug}',
        'namespace'  => 'Backend',
        'middleware' => ['web', 'has.backend.access', 'set.content.type'],
    ],
    function () {
        //---------------------------------------------------------
        Route::get('/assets', 'ContentsController@getAssets')
            ->name('backend.cms.contents.assets');
        //---------------------------------------------------------
        Route::post('/create', 'ContentsController@postCreate')
            ->name('backend.cms.contents.create');
        //---------------------------------------------------------
        Route::get('/list', 'ContentsController@getList')
            ->name('backend.cms.content.types.list');
        //---------------------------------------------------------
        Route::any('/item/{id}', 'ContentsController@getItem')
            ->name('backend.cms.contents.item');
        //---------------------------------------------------------
        Route::post('/store/{id}', 'ContentsController@postStore')
            ->name('backend.cms.contents.store');
        //---------------------------------------------------------
        Route::post('/actions/{action_name}', 'ContentsController@postActions')
            ->name('backend.cms.contents.actions');
        //---------------------------------------------------------
    });
