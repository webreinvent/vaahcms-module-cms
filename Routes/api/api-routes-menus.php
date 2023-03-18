<?php

/*
 * API url will be: <base-url>/public/api/cms/menus
 */
Route::group(
    [
        'prefix' => 'cms/menus',
        'namespace' => 'Api',
    ],
function () {
    /**
     * Get List
     */
    Route::get('/{menu_slug}', 'MenusController@getList')
        ->name('vh.backend.cms.api.menus.list');




});
