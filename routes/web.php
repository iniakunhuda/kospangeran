<?php

use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\FasilitasAreaController;
use App\Http\Controllers\Admin\KamarController;
use App\Http\Controllers\Admin\MasterRekeningController;
use App\Http\Controllers\Admin\TagihanController;
use App\Http\Controllers\Admin\PenyewaController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RiwayatKamarController;
use App\Http\Controllers\Admin\SewaController;
use App\Http\Controllers\Admin\TipeKamarController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes(['register' => false, 'reset' => false]);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {
    Route::resource('area', AreaController::class);
    Route::resource('tipe_kamar', TipeKamarController::class);
    Route::resource('riwayat_kamar', RiwayatKamarController::class);
    Route::resource('penyewa', PenyewaController::class);

    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('edit', [ProfileController::class, 'update'])->name('update');
    });

    Route::get('kamar/{id}/riwayat_penyewa', [KamarController::class, 'riwayatPenyewaIndex'])->name('kamar.riwayat_penyewa');
    Route::resource('kamar', KamarController::class);


    Route::group(['prefix' => 'sewa', 'as' => 'sewa.'], function () {
        Route::get('sewa_baru/create', [
            SewaController::class, 'createSewaBaru'
        ])->name('baru.create');
        Route::post('sewa_baru/create', [
            SewaController::class, 'storeSewaBaru'
        ])->name('baru.store');
        Route::get('sewa_pindah/create/{sewa_id}', [
            SewaController::class, 'createSewaPindah'
        ])->name('pindah.create');
        Route::post('sewa_pindah/create/{sewa_id}', [
            SewaController::class, 'storeSewaPindah'
        ])->name('pindah.store');
        Route::get('sewa_berhenti/create/{sewa_id}', [
            SewaController::class, 'createSewaBerhenti'
        ])->name('berhenti.create');
        Route::post('sewa_berhenti/create/{sewa_id}', [
            SewaController::class, 'storeSewaBerhenti'
        ])->name('berhenti.store');
    });


    Route::group(['prefix' => 'fasilitas_area', 'as' => 'fasilitas_area.'], function () {
        Route::get('{area_id}/create', [
            FasilitasAreaController::class, 'create'
        ])->name('create');

        Route::get('{area_id}/{fasilitas_id}/edit', [
            FasilitasAreaController::class, 'edit'
        ])->name('edit');

        Route::post('{area_id}', [
            FasilitasAreaController::class, 'store'
        ])->name('store');

        Route::put('{area_id}/{fasilitas_id}', [
            FasilitasAreaController::class, 'update'
        ])->name('update');

        Route::delete('{area_id}/{fasilitas_id}', [
            FasilitasAreaController::class, 'destroy'
        ])->name('destroy');
    });

    Route::group(['prefix' => 'tagihan', 'as' => 'tagihan.'], function () {

        Route::post('generate_auto', [
            TagihanController::class, 'generateTagihanCreate'
        ])->name('generate.run');
        Route::get('generate_index', [
            TagihanController::class, 'generateTagihanIndex'
        ])->name('generate.index');

        Route::get('belum_bayar', [
            TagihanController::class, 'belumBayarIndex'
        ])->name('belumbayar.index');
        Route::delete('belum_bayar/{id}', [
            TagihanController::class, 'belumBayarDestroy'
        ])->name('belumbayar.destroy');

        Route::get('bayar', [
            TagihanController::class, 'bayarCreate'
        ])->name('bayar.create');
        Route::post('bayar', [
            TagihanController::class, 'bayarStore'
        ])->name('bayar.store');


        Route::get('riwayat', [
            TagihanController::class, 'riwayatIndex'
        ])->name('riwayat.index');
        Route::get('riwayat/{id}', [
            TagihanController::class, 'riwayatShow'
        ])->name('riwayat.show');
    });


    Route::group(['prefix' => 'master', 'as' => 'master.'], function () {
        Route::resource('rekening', MasterRekeningController::class);
    });


    Route::get('api/area/fetch', [AreaController::class, 'apiFetch'])->name('api.area.fetch');
    Route::get('api/tipe_kamar/fetch', [TipeKamarController::class, 'apiFetch'])->name('api.tipe_kamar.fetch');
    Route::get('api/penyewa/fetch', [PenyewaController::class, 'apiFetch'])->name('api.penyewa.fetch');
    Route::get('api/kamar/fetch', [KamarController::class, 'apiFetch'])->name('api.kamar.fetch');
});
