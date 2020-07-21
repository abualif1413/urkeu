<?php

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
Route::get('/DataRekanan', 'DataRekananController@index');
Route::post('/DataRekanan/Submit', 'DataRekananController@submit');
Route::get('/DataRekanan/GoEdit', 'DataRekananController@goEdit');
Route::get('/DataRekanan/GoDelete/{id_data_rekanan}', 'DataRekananController@goDelete');
Route::get('/DataPICRekanan', 'DataPICRekananController@index');
Route::post('/DataPICRekanan/Submit', 'DataPICRekananController@submit');
Route::get('/DataPICRekanan/ShowPIC/{idDataRekanan}', 'DataPICRekananController@showPicRekanan');
Route::get('/DataPICRekanan/GoEditPicRekanan/{idDataRekananPIC}', 'DataPICRekananController@goEditPicRekanan');
Route::post('/DataPICRekanan/UploadBerkas', 'DataPICRekananController@uploadBerkas');
Route::get('/DataPICRekanan/LoadFileBerkas/{idDataRekananPIC}', 'DataPICRekananController@loadFileBerkas');
Route::get('/DataPICRekanan/HapusFileBerkas/{idDataRekananPICFileBerkas}', 'DataPICRekananController@hapusFileBerkas');
Route::get('/DataPICRekanan/HapusPICRekanan/{idDataRekananPIC}', 'DataPICRekananController@hapusPICRekanan');

Route::get('/AmbilDataPegawai', 'AmbilDataPegawai@index');
Route::get('/AmbilDataPegawai/GetAllJabatan/{id_pegawai}', 'AmbilDataPegawai@getAllJabatan');
Route::get('/AmbilDataPegawai/SearchPegawai', 'AmbilDataPegawai@searchPegawai');


Route::get('/RiwayatPegawai', 'RiwayatPegawaiController@index');
Route::get('/RiwayatPegawai/IsiFormDataTerakhir', 'RiwayatPegawaiController@isiFormDataTerakhir');
Route::get('/RiwayatPegawai/LoadGolongan', 'RiwayatPegawaiController@loadGolongan');
Route::get('/RiwayatPegawai/LoadPangkat', 'RiwayatPegawaiController@loadPangkat');
Route::post('/RiwayatPegawai/Add', 'RiwayatPegawaiController@add');
Route::get('/RiwayatPegawai/Hapus', 'RiwayatPegawaiController@hapus');
Route::get('/RiwayatPegawai/GetRiwayat/{id_pegawai}', 'RiwayatPegawaiController@getRiwayat');

Route::post('/Testing', 'DataPICRekananController@testing');

