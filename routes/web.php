<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Home
Route::get('/', 'Auth\LoginController@home');

//Posts
Route::get('posts', 'PostController@feed');
Route::get('posts/{id}', 'PostController@show');

//Users
Route::get('user/{id}', 'UserController@show');

//Search
Route::get('search', 'SearchController@search');

//Admin
Route::get('admin', 'AdminController@admin');

// API
Route::put('api/posts', array('before'=>'csrf','PostController@create'));
Route::put('api/comments', 'CommentController@create');
Route::post('api/posts/{id}', 'PostController@update');
Route::post('api/user/{id}', 'UserController@update');
Route::delete('api/posts/{id}', 'PostController@destroy');
Route::delete('api/comments/{id}', 'CommentController@destroy');
Route::delete('api/user/{id}', 'UserController@ban');

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
