<?php

use Illuminate\Support\Facades\Auth;
use UniSharp\LaravelFilemanager\Lfm;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BabController;
use App\Http\Controllers\BpmController;
use App\Http\Controllers\SopController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NormController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\SubBabController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CkeditorController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\SlipGajiController;
use App\Http\Controllers\KetentuanController;
use App\Http\Controllers\BpmDivisionController;
use App\Http\Controllers\BukuPedomanController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\KatalogAssetController;
use App\Http\Controllers\NormCategoryController;
use App\Http\Controllers\ReadinessJenisController;
use App\Http\Controllers\ReadinessBagianController;
use App\Http\Controllers\ReadinessMatrixController;
use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\ReadinessKategoriController;
use App\Http\Controllers\ReadinessValidatorController;
use App\Http\Controllers\BukuPedomanDivisionController;
use App\Http\Controllers\ReadinessKompetensiController;
use App\Http\Controllers\ReadinessMatrixAtasanController;

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

Route::get('/', DashboardRedirectController::class);

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::group(['middleware' => ['auth']], function () {
    // \UniSharp\LaravelFilemanager\Lfm::routes();
    Route::resource('user', UserController::class)->except(['destroy']);

    Route::get("sop-list", [SopController::class, 'getList'])->name('sop_list.index');
    // Route::get("sop-list/{slug?}", [SopController::class, 'getSop'])->name('get_sop.index')->where('slug', '(.*)');
    Route::get("sop-list/{slug}", [SopController::class, 'getSop'])->name('get_sop.index');
    Route::get("sop-search", [SopController::class, 'getSearch'])->name('sop_search.index');

    Route::post("readsop", [SopController::class, 'readSop']);

    Route::resource('readinessmatrix', ReadinessMatrixController::class);
    Route::get('readinessmatrix-data', [ReadinessMatrixController::class, 'getData']);
    Route::post('readinessmatrix-delete/{id}', [ReadinessMatrixController::class, 'delete'])->name('readinessmatrix.delete');

    Route::resource('readinessmatrixatasan', ReadinessMatrixAtasanController::class);
    Route::get('readinessmatrixatasan-data', [ReadinessMatrixAtasanController::class, 'getData']);
});

Route::group(['middleware' => ['karyawan']], function () {
    // \UniSharp\LaravelFilemanager\Lfm::routes();

    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::get("content", [ContentController::class, 'index'])->name('content.index');
    Route::post("content", [ContentController::class, 'store'])->name('content.store');

    Route::get("data-diri", [HomeController::class, 'datadiri'])->name('data-diri.index');

    Route::get('absensi-karyawan', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('absensi-karyawan-data', [AbsensiController::class, 'getData']);
    Route::get('absensi-karyawan-data/{sdate}/{edate}', [AbsensiController::class, 'getDataSearch']);

    Route::get("slipgaji", [SlipGajiController::class, 'index'])->name('slipgaji.index');
    Route::post("slipgaji", [SlipGajiController::class, 'store'])->name('slipgaji.store');

    Route::get("bpm-list", [BpmController::class, 'getList'])->name('bpm_list.index');
    Route::get("bpm-list/{slug}", [BpmController::class, 'getBpm'])->name('get_bpm.index');
    Route::get("bpm-search", [BpmController::class, 'getSearch'])->name('bpm_search.index');

    Route::get("bukusaku-list", [SubBabController::class, 'getListBukuSaku'])->name('bukusaku_list.index');
    Route::get("bukusaku-list/{slug}", [SubBabController::class, 'getBukuSaku'])->name('get_bukusaku.index');

    Route::get("norm-list", [NormController::class, 'getListNorm'])->name('norm_list.index');
    Route::get("norm-list/{slug}", [NormController::class, 'getNorm'])->name('get_norm.index');
    Route::get("norm-search", [NormController::class, 'getSearch'])->name('norm_search.index');

    Route::get("asset-list", [KatalogAssetController::class, 'getListAsset'])->name('asset_list.index');
    Route::get("asset-list/{id}", [KatalogAssetController::class, 'getAsset'])->name('get_asset.index');
    Route::get("asset-search", [KatalogAssetController::class, 'getSearch'])->name('asset_search.index');

    Route::get("ketentuan-list", [KetentuanController::class, 'getListKetentuan'])->name('ketentuan_list.index');
    Route::get("ketentuan-list/{slug}", [KetentuanController::class, 'getKetentuan'])->name('get_ketentuan.index');
    Route::get("ketentuan-search", [KetentuanController::class, 'getSearch'])->name('ketentuan_search.index');

    Route::get("bukupedoman-list", [BukuPedomanController::class, 'getList'])->name('bukupedoman_list.index');
    Route::get("bukupedoman-list/{slug}", [BukuPedomanController::class, 'getBukuPedoman'])->name('get_bukupedoman.index');
    Route::get("bukupedoman-search", [BukuPedomanController::class, 'getSearch'])->name('bukupedoman_search.index');

    Route::get("item-list", [ItemController::class, 'getList'])->name('item_list.index');
    Route::get("item-list/{slug}", [ItemController::class, 'getItem'])->name('get_item.index');
    Route::get("item-search", [ItemController::class, 'getSearch'])->name('item_search.index');

    Route::get("presensi", [PresensiController::class, 'index'])->name('presensi.index');
    Route::post("presensi", [PresensiController::class, 'store'])->name('presensi.store');
    Route::get("presensi-table", [PresensiController::class, 'table'])->name('presensi.table');
    Route::get("presensi-data", [PresensiController::class, 'getData']);

    Route::get("pengajuanformcuti", [CutiController::class, 'pengajuan'])->name('formcuti.pengajuan');
    Route::post("formcuti-pengajuanpost", [CutiController::class, 'pengajuanPost'])->name('formcuti.pengajuanpost');
    Route::get("formcuti", [CutiController::class, 'index'])->name('formcuti.index');
    Route::get("formcuti-data", [CutiController::class, 'getData']);

    Route::get("pengajuanfeedback", [FeedbackController::class, 'pengajuan'])->name('feedback.pengajuan');
    Route::post("feedback-pengajuanpost", [FeedbackController::class, 'pengajuanPost'])->name('feedback.pengajuanpost');
    Route::get("feedback", [FeedbackController::class, 'index'])->name('feedback.index');
    Route::get("feedback-data", [FeedbackController::class, 'getData']);

    // Route::resource('ticket','TicketController')->except(['destroy']);
    Route::get("ticket-list", [TicketController::class, 'index'])->name('ticket.index');
    Route::get("ticket/{id}", [TicketController::class, 'show'])->name('ticket.show');
    Route::get("ticketedit/{id}", [TicketController::class, 'edit'])->name('ticket.edit');
    Route::post("ticketupdate/{id}", [TicketController::class, 'update'])->name('ticket.update');
    Route::post('/ticket-delete/{id}', [TicketController::class, 'delete'])->name('ticket.delete');
    Route::get("ticket-data", [TicketController::class, 'getData']);
    Route::get("pengajuanticket", [TicketController::class, 'pengajuan'])->name('ticket.pengajuan');
    Route::post("ticket-pengajuanpost", [TicketController::class, 'pengajuanPost'])->name('ticket.pengajuanpost');

    Route::get("task-ticket", [TicketController::class, 'indexTask'])->name('task-ticket.index');
    Route::get("task-ticket/{id}", [TicketController::class, 'showTask'])->name('task-ticket.show');
    Route::get("task-ticket-data", [TicketController::class, 'getDataTask']);
    Route::get("taskticketedit/{id}", [TicketController::class, 'editTask'])->name('task-ticket.edit');
    Route::post("taskticketupdate/{id}", [TicketController::class, 'updateTask'])->name('task-ticket.update');
});

Route::group(['middleware' => ['ga']], function () {
    Route::resource('asset', KatalogAssetController::class)->except(['destroy']);
    Route::get("asset-excel", [KatalogAssetController::class, 'excel'])->name('asset.excel');
    Route::get("asset-data", [KatalogAssetController::class, 'getData']);
    Route::post('/asset-delete/{id}', [KatalogAssetController::class, 'delete'])->name('asset.delete');

    Route::resource('brand', BrandController::class)->except(['destroy']);
    Route::get("brand-data", [BrandController::class, 'getData']);
    Route::post('/brand-delete/{id}', [BrandController::class, 'delete'])->name('brand.delete');
});

Route::group(['middleware' => ['admin']], function () {
    Route::get("manajementicket", [TicketController::class, 'manajemenTicket'])->name('manajementicket.index');
    Route::get("manajementicket-data", [TicketController::class, 'getDataManajemenTicket']);

    Route::get("manajementicketdepart", [TicketController::class, 'manajemenTicketDepart'])->name('manajementicketdepart.index');
    Route::get("manajementicketdepart-data", [TicketController::class, 'getDataManajemenTicketDepart']);

    Route::get('laporanfeedback', [FeedbackController::class, 'indexLaporan'])->name('feedbacklaporan.index');
    Route::get('laporanfeedback-search', [FeedbackController::class, 'indexSearchLaporan'])->name('feedbacklaporan.search');
    Route::post('/laporanfeedback-delete/{id}', [FeedbackController::class, 'delete'])->name('laporanfeedback.delete');

    Route::get("user-data", [UserController::class, 'getData']);
    Route::post('/user-delete/{id}', [UserController::class, 'delete'])->name('user.delete');

    Route::resource('category', CategoryController::class)->except(['destroy']);
    Route::get("category-data", [CategoryController::class, 'getData']);
    Route::post('/category-delete/{id}', [CategoryController::class, 'delete'])->name('category.delete');

    Route::resource('tag', TagController::class)->except(['destroy']);
    Route::get("tag-data", [TagController::class, 'getData']);
    Route::post('/tag-delete/{id}', [TagController::class, 'delete'])->name('tag.delete');

    Route::resource('normcategory', NormCategoryController::class)->except(['destroy']);
    Route::get("normcategory-data", [NormCategoryController::class, 'getData']);
    Route::post('/normcategory-delete/{id}', [NormCategoryController::class, 'delete'])->name('normcategory.delete');

    Route::resource('type', TypeController::class)->except(['destroy']);
    Route::get("type-data", [TypeController::class, 'getData']);
    Route::post('/type-delete/{id}', [TypeController::class, 'delete'])->name('type.delete');

    Route::resource('jabatan', JabatanController::class)->except(['destroy']);
    Route::get("jabatan-data", [JabatanController::class, 'getData']);
    Route::post('/jabatan-delete/{id}', [JabatanController::class, 'delete'])->name('jabatan.delete');

    Route::resource('blog', BlogController::class)->except(['destroy']);
    Route::get("blog-data", [BlogController::class, 'getData']);
    Route::post('/blog-delete/{id}', [BlogController::class, 'delete'])->name('blog.delete');

    Route::get("social-media", [SocialMediaController::class, 'index'])->name('socialmedia.index');
    Route::post("social-media", [SocialMediaController::class, 'store'])->name('socialmedia.store');

    Route::resource('blog', BlogController::class)->except(['destroy']);
    Route::get("blog-data", [BlogController::class, 'getData']);
    Route::post('/blog-delete/{id}', [BlogController::class, 'delete'])->name('blog.delete');

    Route::resource('sop', SopController::class)->except(['destroy']);
    Route::get("sop-data", [SopController::class, 'getData']);
    Route::post('/sop-delete/{id}', [SopController::class, 'delete'])->name('sop.delete');
    Route::get("sop_history/{id}", [SopController::class, 'history'])->name('get_sop.history');
    Route::get("sop_history", [SopController::class, 'historyAll'])->name('sop.history');
    Route::get("sop-history-print", [SopController::class, 'printHistoryAll'])->name('sop.history_print');
    Route::get("sop_notification", [SopController::class, 'notification'])->name('sop.notification');

    Route::resource('bpm', BpmController::class)->except(['destroy']);
    Route::get("bpm-data", [BpmController::class, 'getData']);
    Route::post('/bpm-delete/{id}', [BpmController::class, 'delete'])->name('bpm.delete');

    Route::resource('bpmdivision', BpmDivisionController::class)->except(['destroy']);
    Route::get("bpmdivision-data", [BpmDivisionController::class, 'getData']);
    Route::post('/bpmdivision-delete/{id}', [BpmDivisionController::class, 'delete'])->name('bpmdivision.delete');

    Route::resource('bukupedoman', BukuPedomanController::class)->except(['destroy']);
    Route::get("bukupedoman-data", [BukuPedomanController::class, 'getData']);
    Route::post('/bukupedoman-delete/{id}', [BukuPedomanController::class, 'delete'])->name('bukupedoman.delete');

    Route::resource('norm', NormController::class)->except(['destroy']);
    Route::get("norm-data", [NormController::class, 'getData']);
    Route::post('/norm-delete/{id}', [NormController::class, 'delete'])->name('norm.delete');

    Route::resource('ketentuan', KetentuanController::class)->except(['destroy']);
    Route::get("ketentuan-data", [KetentuanController::class, 'getData']);
    Route::post('/ketentuan-delete/{id}', [KetentuanController::class, 'delete'])->name('ketentuan.delete');

    Route::resource('bukupedomandivision', BukuPedomanDivisionController::class)->except(['destroy']);
    Route::get("bukupedomandivision-data", [BukuPedomanDivisionController::class, 'getData']);
    Route::post('/bukupedomandivision-delete/{id}', [BukuPedomanDivisionController::class, 'delete'])->name('bukupedomandivision.delete');

    Route::resource('item', ItemController::class)->except(['destroy']);
    Route::get("item-data", [ItemController::class, 'getData']);
    Route::post('/item-delete/{id}', [ItemController::class, 'delete'])->name('item.delete');

    Route::post('ckeditor/upload', [CkeditorController::class, 'upload'])->name('ckeditor.upload');

    Route::resource('type', TypeController::class)->except(['destroy']);
    Route::get("type-data", [TypeController::class, 'getData']);
    Route::post('/type-delete/{id}', [TypeController::class, 'delete'])->name('type.delete');

    Route::resource('bab', BabController::class)->except(['destroy']);
    Route::get("bab-data", [BabController::class, 'getData']);
    Route::post('/bab-delete/{id}', [BabController::class, 'delete'])->name('bab.delete');

    Route::resource('subbab', SubBabController::class)->except(['destroy']);
    Route::get("subbab-data", [SubBabController::class, 'getData']);
    Route::post('/subbab-delete/{id}', [SubBabController::class, 'delete'])->name('subbab.delete');

    Route::resource('readinesskategori', ReadinessKategoriController::class);
    Route::get('readinesskategori-data', [ReadinessKategoriController::class, 'getData']);
    Route::post('readinesskategori-delete/{id}', [ReadinessKategoriController::class, 'delete'])->name('readinesskategori.delete');

    Route::resource('readinessjenis', ReadinessJenisController::class);
    Route::get('readinessjenis-data', [ReadinessJenisController::class, 'getData']);
    Route::post('readinessjenis-delete/{id}', [ReadinessJenisController::class, 'delete'])->name('readinessjenis.delete');

    Route::resource('readinessbagian', ReadinessBagianController::class);
    Route::get('readinessbagian-data', [ReadinessBagianController::class, 'getData']);
    Route::post('readinessbagian-delete/{id}', [ReadinessBagianController::class, 'delete'])->name('readinessbagian.delete');

    Route::resource('readinesskompetensi', ReadinessKompetensiController::class);
    Route::get('readinesskompetensi-data', [ReadinessKompetensiController::class, 'getData']);
    Route::post('readinesskompetensi-delete/{id}', [ReadinessKompetensiController::class, 'delete'])->name('readinesskompetensi.delete');

    Route::resource('readinessvalidator', ReadinessValidatorController::class);
    Route::get('readinessvalidator-data', [ReadinessValidatorController::class, 'getData']);
    Route::post('readinessvalidator-status', [ReadinessValidatorController::class, 'setReadinessStatus']);
});
