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
    // return view('welcome');
    return redirect()->route('dashboard');
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

	Route::get("data-diri", "HomeController@datadiri")->name('data-diri.index');

	Route::get('absensi-karyawan','AbsensiController@index')->name('absensi.index');
	Route::get('absensi-karyawan-data', "AbsensiController@getData");
	Route::get('absensi-karyawan-data/{sdate}/{edate}', 'AbsensiController@getDataSearch');

	Route::get("slipgaji", "SlipGajiController@index")->name('slipgaji.index');
	Route::post("slipgaji", "SlipGajiController@store")->name('slipgaji.store');

	Route::resource('user','UserController')->except(['destroy']);

	Route::get("sop-list", "SopController@getList")->name('sop_list.index');
	// Route::get("sop-list/{slug?}", "SopController@getSop")->name('get_sop.index')->where('slug', '(.*)');
	Route::get("sop-list/{slug}", "SopController@getSop")->name('get_sop.index');
	
	Route::get("sop-search", "SopController@getSearch")->name('sop_search.index');
	Route::post("readsop", "SopController@readSop");

	Route::get("bpm-list", "BpmController@getList")->name('bpm_list.index');
	Route::get("bpm-list/{slug}", "BpmController@getBpm")->name('get_bpm.index');
	Route::get("bpm-search", "BpmController@getSearch")->name('bpm_search.index');

	Route::get("bukusaku-list", "SubBabController@getListBukuSaku")->name('bukusaku_list.index');
	Route::get("bukusaku-list/{slug}", "SubBabController@getBukuSaku")->name('get_bukusaku.index');

	Route::get("bukupedoman-list", "BukuPedomanController@getList")->name('bukupedoman_list.index');
	Route::get("bukupedoman-list/{slug}", "BukuPedomanController@getBukuPedoman")->name('get_bukupedoman.index');
	Route::get("bukupedoman-search", "BukuPedomanController@getSearch")->name('bukupedoman_search.index');

	Route::get("item-list", "ItemController@getList")->name('item_list.index');
	Route::get("item-list/{slug}", "ItemController@getItem")->name('get_item.index');
	Route::get("item-search", "ItemController@getSearch")->name('item_search.index');

	Route::get("pengajuanformcuti", "CutiController@pengajuan")->name('formcuti.pengajuan');
    Route::post("formcuti-pengajuanpost", "CutiController@pengajuanPost")->name('formcuti.pengajuanpost');

    Route::get("formcuti", "CutiController@index")->name('formcuti.index');
	Route::get("formcuti-data", "CutiController@getData");
});

Route::group(['middleware' => ['admin']], function() {
	Route::get("user-data", "UserController@getData");
    Route::post('/user-delete/{id}', 'UserController@delete')->name('user.delete');

    Route::resource('category','CategoryController')->except(['destroy']);
	Route::get("category-data", "CategoryController@getData");
	Route::post('/category-delete/{id}', 'CategoryController@delete')->name('category.delete');

	Route::resource('tag','TagController')->except(['destroy']);
	Route::get("tag-data", "TagController@getData");
	Route::post('/tag-delete/{id}', 'TagController@delete')->name('tag.delete');	

	Route::resource('type','TypeController')->except(['destroy']);
	Route::get("type-data", "TypeController@getData");
	Route::post('/type-delete/{id}', 'TypeController@delete')->name('type.delete');

	Route::resource('jabatan','JabatanController')->except(['destroy']);
	Route::get("jabatan-data", "JabatanController@getData");
	Route::post('/jabatan-delete/{id}', 'JabatanController@delete')->name('jabatan.delete');

    Route::resource('blog','BlogController')->except(['destroy']);
	Route::get("blog-data", "BlogController@getData");
	Route::post('/blog-delete/{id}', 'BlogController@delete')->name('blog.delete');

	Route::get("social-media", "SocialMediaController@index")->name('socialmedia.index');
	Route::post("social-media", "SocialMediaController@store")->name('socialmedia.store');

	Route::resource('blog','BlogController')->except(['destroy']);
	Route::get("blog-data", "BlogController@getData");
	Route::post('/blog-delete/{id}', 'BlogController@delete')->name('blog.delete');

	Route::resource('sop','SopController')->except(['destroy']);
	Route::get("sop-data", "SopController@getData");
	Route::post('/sop-delete/{id}', 'SopController@delete')->name('sop.delete');
	Route::get("sop_history/{id}", "SopController@history")->name('get_sop.history');
	Route::get("sop_history", "SopController@historyAll")->name('sop.history');
	Route::get("sop-history-print", "SopController@printHistoryAll")->name('sop.history_print');
	Route::get("sop_notification", "SopController@notification")->name('sop.notification');

	Route::resource('bpm','BpmController')->except(['destroy']);
	Route::get("bpm-data", "BpmController@getData");
	Route::post('/bpm-delete/{id}', 'BpmController@delete')->name('bpm.delete');

	Route::resource('bpmdivision','BpmDivisionController')->except(['destroy']);
	Route::get("bpmdivision-data", "BpmDivisionController@getData");
	Route::post('/bpmdivision-delete/{id}', 'BpmDivisionController@delete')->name('bpmdivision.delete');

	Route::resource('bukupedoman','BukuPedomanController')->except(['destroy']);
	Route::get("bukupedoman-data", "BukuPedomanController@getData");
	Route::post('/bukupedoman-delete/{id}', 'BukuPedomanController@delete')->name('bukupedoman.delete');

	Route::resource('bukupedomandivision','BukuPedomanDivisionController')->except(['destroy']);
	Route::get("bukupedomandivision-data", "BukuPedomanDivisionController@getData");
	Route::post('/bukupedomandivision-delete/{id}', 'BukuPedomanDivisionController@delete')->name('bukupedomandivision.delete');

	Route::resource('item','ItemController')->except(['destroy']);
	Route::get("item-data", "ItemController@getData");
	Route::post('/item-delete/{id}', 'ItemController@delete')->name('item.delete');

	Route::post('ckeditor/upload', 'CkeditorController@upload')->name('ckeditor.upload');

	Route::resource('type','TypeController')->except(['destroy']);
	Route::get("type-data", "TypeController@getData");
	Route::post('/type-delete/{id}', 'TypeController@delete')->name('type.delete');

	Route::resource('bab','BabController')->except(['destroy']);
	Route::get("bab-data", "BabController@getData");
	Route::post('/bab-delete/{id}', 'BabController@delete')->name('bab.delete');

	Route::resource('subbab','SubBabController')->except(['destroy']);
	Route::get("subbab-data", "SubBabController@getData");
	Route::post('/subbab-delete/{id}', 'SubBabController@delete')->name('subbab.delete');
});