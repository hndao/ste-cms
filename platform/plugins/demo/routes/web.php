<?php

Route::group(['namespace' => 'Botble\Demo\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'demos', 'as' => 'demo.'], function () {
            Route::resource('', 'DemoController')->parameters(['' => 'demo']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'DemoController@deletes',
                'permission' => 'demo.destroy',
            ]);
        });

        Route::group(['prefix' => 'categories', 'as' => 'category.'], function () {
            Route::resource('', 'CategoryController')->parameters(['' => 'category']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'CategoryController@deletes',
                'permission' => 'categories.destroy',
            ]);
        });
    });

});
