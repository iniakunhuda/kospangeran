<?php

use App\Http\Controllers\Api\AreaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KamarController;
use App\Http\Controllers\Api\PenyewaController;
use App\Http\Controllers\Api\RekeningController;
use App\Http\Controllers\Api\RiwayatBayarController;
use App\Http\Controllers\Api\RiwayatKamarController;
use App\Http\Controllers\Api\SewaController;
use App\Http\Controllers\Api\TagihanController;
use App\Http\Controllers\Api\TipeKamarController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(
    [
        'middleware' => 'auth:api',
    ],
    function () {

        //-- Area
        Route::post(
            'area/fasilitas/{area_id}',
            [AreaController::class, 'storeFasilitas']
        );
        Route::put(
            'area/fasilitas/{area_id}/{id}',
            [AreaController::class, 'updateFasilitas']
        );
        Route::delete('area/fasilitas/{area_id}/{id}', [AreaController::class, 'destroyFasilitas']);
        Route::apiResource('area', AreaController::class);

        //-- Tipe Kamar
        Route::apiResource('tipekamar', TipeKamarController::class);
        //-- Kamar
        Route::get(
            'kamar/perbaikan/{kamar_id}',
            [KamarController::class, 'indexRenovation']
        );
        Route::post(
            'kamar/perbaikan/{kamar_id}',
            [KamarController::class, 'storeRenovation']
        );
        Route::get(
            'kamar/riwayat_penyewa/{kamar_id}',
            [KamarController::class, 'historyPenyewa']
        );
        Route::apiResource('kamar', KamarController::class);

        //-- Penyewa
        Route::get(
            'penyewa/{penyewa_id}/tagihan/belum_dibayar',
            [PenyewaController::class, 'getInvoiceUnpaid']
        );
        Route::apiResource('penyewa', PenyewaController::class);

        //-- Rekening
        Route::apiResource('rekening', RekeningController::class);


        //-- Sewa
        Route::post(
            'sewa/kamar_baru',
            [SewaController::class, 'rentNewRoom']
        );
        Route::post(
            'sewa/pindah_kamar',
            [SewaController::class, 'rentMoveRoom']
        );
        Route::post(
            'sewa/berhenti_sewa',
            [SewaController::class, 'stopRentRoom']
        );

        //-- Tagihan
        Route::get('tagihan', [TagihanController::class, 'index']);
        Route::post('tagihan/bayar', [TagihanController::class, 'payInvoice']);

        //-- Riwayat Bayar
        Route::apiResource('riwayat_bayar', RiwayatBayarController::class);


        //-- Riwayat Kamar (index aja)
        Route::get('riwayat_kamar', [RiwayatKamarController::class, 'index']);
    }
);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post(
        'login',
        [AuthController::class, 'login']
    );
    Route::post('logout', [
        AuthController::class,
        'logout'
    ]);
    Route::post(
        'refresh',
        [AuthController::class, 'refresh']
    );
});
