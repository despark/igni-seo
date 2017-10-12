<?php

Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => ['web', 'auth.admin']], function () {
        Route::get('/check/readability', ['as' => 'check.readability', 'uses' => 'Despark\Cms\Seo\Http\Controllers\Admin\SeoReadabilityController@check']);
    });
});
