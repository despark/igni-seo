<?php

Route::group(['prefix' => 'admin'], function () {
	if (file_exists(base_path('vendor/despark/igni-core'))) {
		Route::group(['middleware' => ['web', 'auth.admin']], function () {
	        Route::post('/check/readability', ['as' => 'check.readability', 'uses' => 'Despark\Cms\Seo\Http\Controllers\Admin\SeoReadabilityController@check']);
	    });
	} else {
		Route::group(['middleware' => ['web']], function () {
	        Route::post('/check/readability', ['as' => 'check.readability', 'uses' => 'Despark\Cms\Seo\Http\Controllers\Admin\SeoReadabilityController@check']);
	    });
	}
    
});
