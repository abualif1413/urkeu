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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/PengeluaranDana/TambahDetail', 'Api\PengeluaranDanaController@tambahDetail');
Route::get('/PengeluaranDana/GetDetailTemp/{user_id}', 'Api\PengeluaranDanaController@getDetailTemp');
Route::get('/PengeluaranDana/hapusDetail/{id_detail}', 'Api\PengeluaranDanaController@hapusDetail');
Route::get('/PengeluaranDana/GetRekananPIC/{id_data_rekanan_pic}', 'Api\PengeluaranDanaController@getRekananPIC');

Route::post('/PengeluaranDana/TambahDetailNormatif', 'Api\PengeluaranDanaController@tambahDetailNormatif');
Route::get('/PengeluaranDana/GetDetailNormatifTemp/{user_id}', 'Api\PengeluaranDanaController@getDetailNormatifTemp');
Route::get('/PengeluaranDana/hapusDetailNormatif/{id_detail_normatif}', 'Api\PengeluaranDanaController@hapusDetailNormatif');
