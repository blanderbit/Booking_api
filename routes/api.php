<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------|
*/

Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@signup');
Route::get('get_posts', 'PostController@getPosts');


Route::group([
    'middleware' => 'cors',
    'middleware' => ['auth:api']
], function() {
    Route::get('logout', 'AuthController@logout');

    Route::get('profile/{id}', 'ProfilsController@getProfile');
    Route::post('update_profile/{id}', 'ProfilsController@updateProfile');
    Route::delete('remove_profile/{id}', 'ProfilsController@removeProfile');


    Route::get('get_post/{id}', 'PostController@getPost');
    Route::post('add_post', 'PostController@addPost');
    Route::post('update_post/{id}', 'PostController@updatePost');
    Route::delete('remove_post/{id}', 'PostController@removePost');

    Route::post('post/{id}/add_rent', 'RentController@addRent');
    Route::post('post/{id}/remove_rent', 'RentController@removeRent');

    Route::post('post/{id}/add_review', 'CommentsController@addComment');
    Route::post('post/{id}/update_review', 'CommentsController@updateComment');
    Route::delete('post/{id}/remove_review}', 'CommentsController@removeComment');
});
