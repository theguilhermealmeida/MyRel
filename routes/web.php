<?php

use App\Http\Controllers\TestController;



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
Route::get('posts/{id}/reactions', 'PostController@get_reactions')->name('getPostReactions');

//Users
Route::get('user/{id}', 'UserController@show');

//Search
Route::get('search', 'SearchController@search');

//Admin
Route::get('admin', 'AdminController@admin');

// Relationships
Route::get('relationships', 'RelationshipController@show');

//Contacts 
Route::view('contacts', 'pages.contacts');

//Contacts 
Route::view('about-us', 'pages.aboutus');

// API
Route::put('api/posts', 'PostController@create');
Route::put('api/comments', 'CommentController@create');
Route::put('api/replies', 'ReplyController@create');
Route::put('api/relationships/{id}', 'RelationshipController@create');
Route::post('api/posts/{id}', 'PostController@update');
Route::post('api/posts/{id}/reaction', 'PostController@addReaction')->name('addReaction');
Route::post('api/comments/{id}', 'CommentController@update');
Route::post('api/comments/{id}/reaction', 'CommentController@addReaction')->name('addCommentReaction');
Route::post('api/replies/{id}', 'ReplyController@update');
Route::post('api/replies/{id}/reaction', 'ReplyController@addReaction')->name('addReplyReaction');
Route::post('api/user/{id}', 'UserController@update');
Route::post('api/relationships/{id}', 'RelationshipController@accept');
Route::post('api/user/ban/{id}', 'UserController@ban');
Route::delete('api/posts/{id}', 'PostController@destroy');
Route::delete('api/comments/{id}', 'CommentController@destroy');
Route::delete('api/replies/{id}', 'ReplyController@destroy');
Route::delete('api/user/{id}', 'UserController@ban');
Route::delete('api/relationships/{id}', 'RelationshipController@destroy');
Route::post('api/search', 'SearchController@search_api');

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Reset Password
Route::get('/forgot-password', 'PasswordResetController@showForgotForm')->name('password.forgot');
Route::get('/change-password/{id}', 'PasswordResetController@showChangeForm')->name('password.change');
Route::post('/forgot-password', 'PasswordResetController@sendResetLinkEmail')->name('password.email');
Route::get('/reset-password/{token}', 'PasswordResetController@showResetForm')->name('password.reset');
Route::post('/reset-password', 'PasswordResetController@reset')->name('password.update');

// Notifications
Route::get('notifications/{id}', 'UserController@showNotifications')->name('notifications.show');
Route::get('notifications/mark-all-as-read/{id}', 'UserController@marknotificationsasread')->name('notifications.markAllAsRead');
