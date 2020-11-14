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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes([
 	'register' => false, // Registration Routes...
  	'reset' => false, // Password Reset Routes...
  	'verify' => false, // Email Verification Routes...
]);

Route::group(['middleware' => ['auth']], function() {
	// \UniSharp\LaravelFilemanager\Lfm::routes();
	Route::get('/dashboard', 'HomeController@index')->name('dashboard');

	Route::get("content", "ContentController@index")->name('content.index');
	Route::post("content", "ContentController@store")->name('content.store');

	Route::get("social-media", "SocialMediaController@index")->name('socialmedia.index');
	Route::post("social-media", "SocialMediaController@store")->name('socialmedia.store');

	Route::get("slipgaji", "SlipGajiController@index")->name('slipgaji.index');
	Route::post("slipgaji", "SlipGajiController@store")->name('slipgaji.store');

	Route::resource('blog','BlogController')->except(['destroy']);
	Route::get("blog-data", "BlogController@getData");
	Route::post('/blog-delete/{id}', 'BlogController@delete')->name('blog.delete');

	Route::post('ckeditor/upload', 'CkeditorController@upload')->name('ckeditor.upload');
	Route::resource('user','UserController')->except(['destroy']);
});

Route::group(['middleware' => ['admin']], function() {
	Route::get("user-data", "UserController@getData");
    Route::post('/user-delete/{id}', 'UserController@delete')->name('user.delete');
});