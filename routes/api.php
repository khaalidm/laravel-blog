<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix'     => 'v1',
    'namespace'  => '\App\Http\Controllers',
    'middleware' => [
        'api',
        'log.api.requests'
    ],
], static function ($router) {
    // Authentication
    Route::group([
        'prefix' => 'user',
        'namespace' => 'User',
    ], static function ($router) {
        // Authenticated
        Route::group([
            'middleware' => 'auth:sanctum',
        ], static function ($router) {
            Route::get('/', 'UserController@index')->name('user.index');
            Route::get('{user}', 'UserController@show')->name('user.show');
            Route::patch('{user}', 'UserController@update')->name('user.update');
            Route::delete('{user}', 'UserController@destroy')->name('user.destroy');
            Route::post('/', 'UserController@store')->name('user.store');
        });
    });

    Route::group([
        'prefix' => 'category',
        'namespace' => 'Category',
    ], static function ($router) {
        // Authenticated
        Route::group([
            'middleware' => 'auth:sanctum',
        ], static function ($router) {
            Route::get('/', 'CategoryController@index')->name('category.index');
            Route::get('{category}', 'CategoryController@show')->name('category.show');
            Route::patch('{category}', 'CategoryController@update')->name('category.update');
            Route::post('/', 'CategoryController@store')->name('category.store');
            Route::post('/list-by-user/{user_id}', 'CategoryController@listCategoriesByUser')->name('category.list_by_user');
        });
    });

    Route::group([
        'prefix' => 'post',
        'namespace' => 'Post',
    ], static function ($router) {
        // Authenticated
        Route::group([
            'middleware' => 'auth:sanctum',
        ], static function ($router) {
            Route::get('/', 'PostController@index')->name('post.index');
            Route::get('{post}', 'PostController@show')->name('post.show');
            Route::patch('{post}', 'PostController@update')->name('post.update');
            Route::post('/toggle/active/{post}', 'PostController@toggleActiveState')->name('post.toggle_active_state');
            Route::post('/', 'PostController@store')->name('post.store');
            Route::post('/list-by-user/{user_id}', 'PostController@listByUser')->name('post.list_by_user');
            Route::post('/list-by-category/{category_id}', 'PostController@listByCategory')->name('post.list_by_category');
        });
    });

    Route::group([
        'prefix' => 'comment',
        'namespace' => 'Comment',
    ], static function ($router) {
        // Authenticated
        Route::group([
            'middleware' => 'auth:sanctum',
        ], static function ($router) {
            Route::get('/', 'CommentController@index')->name('comment.index');
            Route::get('{comment}', 'CommentController@show')->name('comment.show');
            Route::patch('{comment}', 'CommentController@update')->name('comment.update');
            Route::delete('{comment}', 'CommentController@destroy')->name('comment.destroy');
            Route::post('/', 'CommentController@store')->name('comment.store');
        });
    });
});
