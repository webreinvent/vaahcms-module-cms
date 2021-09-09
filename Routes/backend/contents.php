<?php


Route::group(
    [
        'prefix' => 'backend/cms/media',
        'namespace'  => 'Backend',
        'middleware' => ['web', 'has.backend.access'],
    ],
    function () {
        //---------------------------------------------------------
        Route::post('/upload', 'MediaController@upload')
            ->name('backend.cms.media.upload');
        //---------------------------------------------------------
    });

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
        Route::post('/item/{id}/store', 'ContentsController@postStore')
            ->name('backend.cms.contents.store');
        //---------------------------------------------------------
        Route::post('/item/{id}/groups/template', 'ContentsController@getTemplateGroups')
            ->name('backend.cms.contents.groups.template');
        //---------------------------------------------------------
        Route::post('/actions/{action_name}', 'ContentsController@postActions')
            ->name('backend.cms.contents.actions');
        //---------------------------------------------------------
        Route::post('/sync/seeds', 'ContentsController@syncSeeds')
            ->name('backend.cms.contents.syncSeeds');
        //---------------------------------------------------------
        Route::post('/getRelationsInTree', 'ContentsController@getRelationsInTree')
            ->name('backend.cms.contents.getRelationsInTree');
        //---------------------------------------------------------
    });
