<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::post('/login', 'LoginController@login');
// Area
Route::get('kota', 'Master\AreaController@kotaList');
Route::get('kecamatan', 'Master\AreaController@kecamatanList');
Route::get('desa', 'Master\AreaController@desaList');
Route::resource('area', 'Master\AreaController');

// Master - Wilayah Kerja
Route::resource('wilayahkerja', 'Master\WilayahKerjaController');

// Master - Komoditas
Route::resource('komoditas', 'Master\KomoditasController');
Route::resource('komoditashtp', 'Master\KomoditasHtpController');

// Master - Penyuluh
Route::get('penyuluh/all', 'Master\PenyuluhController@getAll');
Route::get('penyuluh/wilayahkerja', 'Master\PenyuluhController@wilayahkerja');
Route::post('penyuluh/file', 'Master\PenyuluhController@file');
Route::post('penyuluh/download', 'Master\PenyuluhController@download');
Route::resource('penyuluh', 'Master\PenyuluhController');

// Master - Jenis Alsin
Route::resource('jenisalsin', 'Master\JenisAlsinController');

// Master - Nama OPT
Route::resource('namaopt', 'Master\NamaOptController');

// Master - Koordinator Wilayah
Route::get('korwil/byUser/{user_id}', 'Master\KoordinatorWilayahController@getByUser');
Route::resource('korwil', 'Master\KoordinatorWilayahController');

// Data - Baku Lahan
Route::post('bakulahan/file', 'Data\BakuLahanController@file');
Route::post('bakulahan/download', 'Data\BakuLahanController@download');
Route::post('bakulahan/vrf_all', 'Data\BakuLahanController@verifikasiAll');
Route::post('bakulahan/verifikasi/{id}', 'Data\BakuLahanController@verifikasi');
Route::resource('bakulahan', 'Data\BakuLahanController');

// Data - Baku Lahan detail (Rencana dan realisasi tanam)
Route::post('bakulahandetail/file', 'Data\BakuLahanDetailController@file');
Route::post('bakulahandetail/download', 'Data\BakuLahanDetailController@download');
Route::post('bakulahandetail/vrf_all', 'Data\BakuLahanDetailController@verifikasiAll');
Route::post('bakulahandetail/verifikasi/{id}', 'Data\BakuLahanDetailController@verifikasi');
Route::resource('bakulahandetail', 'Data\BakuLahanDetailController');

// Data - Opsin
Route::post('opsin/file', 'Data\OpsinController@file');
Route::post('opsin/download', 'Data\OpsinController@download');
Route::post('opsin/vrf_all', 'Data\OpsinController@verifikasiAll');
Route::post('opsin/verifikasi/{id}', 'Data\OpsinController@verifikasi');
Route::resource('opsin', 'Data\OpsinController');

// Data - Opsin detail
Route::post('opsindetail/file', 'Data\OpsinDetailController@file');
Route::post('opsindetail/download', 'Data\OpsinDetailController@download');
Route::post('opsindetail/vrf_all', 'Data\OpsinDetailController@verifikasiAll');
Route::post('opsindetail/verifikasi/{id}', 'Data\OpsinDetailController@verifikasi');
Route::resource('opsindetail', 'Data\OpsinDetailController');

// Data - Data Luas
Route::post('dataluas/file', 'Data\DataLuasController@file');
Route::post('dataluas/download', 'Data\DataLuasController@download');
Route::post('dataluas/vrf_all', 'Data\DataLuasController@verifikasiAll');
Route::post('dataluas/verifikasi/{id}', 'Data\DataLuasController@verifikasi');
Route::resource('dataluas', 'Data\DataLuasController');

// Data - Harga Tingkat Petani
Route::post('htp/file', 'Data\HtpController@file');
Route::post('htp/download', 'Data\HtpController@download');
Route::post('htp/vrf_all', 'Data\HtpController@verifikasiAll');
Route::post('htp/verifikasi/{id}', 'Data\HtpController@verifikasi');
Route::resource('htp', 'Data\HtpController');

// User
Route::resource('user', 'User\UserController');

// Level
Route::get('acl', 'User\RoleController@acl');
Route::resource('level', 'User\RoleController');

// Config
Route::resource('config', 'ConfigController');

// Config
Route::resource('download', 'DownloadController');

// pengumuman
Route::post('pengumuman/file', 'PengumumanController@file');
Route::resource('pengumuman', 'PengumumanController');

// Dashboard
Route::get('dashboard/penyuluh', 'DashboardController@statistikPenyuluh');
Route::get('dashboard/sawahladang', 'DashboardController@statistikSawahLadang');

// Laporan - Baku Lahan
Route::get('laporan/bakulahan', 'Laporan\BakulahanController@index');
Route::post('laporan/bakulahan/download', 'Laporan\BakulahanController@download');

// Laporan - Rencana Tanam
Route::get('laporan/rencana_tanam', 'Laporan\RencanaTanamController@index');
Route::post('laporan/rencana_tanam/download', 'Laporan\RencanaTanamController@download');
Route::get('laporan/rencana_tanam/detail', 'Laporan\RencanaTanamController@detail');
Route::post('laporan/rencana_tanam/detail/download', 'Laporan\RencanaTanamController@detailDownload');

// Laporan - Realisasi Tanam
Route::get('laporan/realisasi_tanam', 'Laporan\RealisasiTanamController@index');
Route::post('laporan/realisasi_tanam/download', 'Laporan\RealisasiTanamController@download');
Route::get('laporan/realisasi_tanam/detail', 'Laporan\RealisasiTanamController@detail');
Route::post('laporan/realisasi_tanam/detail/download', 'Laporan\RealisasiTanamController@detailDownload');

// Laporan - Harga Tingkat Petani
Route::get('laporan/htp', 'Laporan\HtpController@index');
Route::post('laporan/htp/download', 'Laporan\HtpController@download');
Route::get('laporan/htp/detail', 'Laporan\HtpController@detail');
Route::post('laporan/htp/detail/download', 'Laporan\HtpController@detailDownload');
Route::get('laporan/htp/area', 'Laporan\HtpController@area');
Route::post('laporan/htp/area/download', 'Laporan\HtpController@areaDownload');
Route::get('laporan/htp/area/detail', 'Laporan\HtpController@areaDetail');
Route::post('laporan/htp/area/detail/download', 'Laporan\HtpController@areaDetailDownload');

// Laporan - Luas Tanam
Route::get('laporan/luas_tanam', 'Laporan\LuasTanamController@index');
Route::post('laporan/luas_tanam/download', 'Laporan\LuasTanamController@download');
Route::get('laporan/luas_tanam/detail', 'Laporan\LuasTanamController@detail');
Route::post('laporan/luas_tanam/detail/download', 'Laporan\LuasTanamController@detailDownload');
Route::get('laporan/luas_tanam/saatini', 'Laporan\LuasTanamController@saatini');
Route::post('laporan/luas_tanam/saatini/download', 'Laporan\LuasTanamController@saatiniDownload');

// Laporan - Tambah Tanam
Route::get('laporan/tambah_tanam', 'Laporan\TambahTanamController@index');
Route::post('laporan/tambah_tanam/download', 'Laporan\TambahTanamController@download');
Route::get('laporan/tambah_tanam/detail', 'Laporan\TambahTanamController@detail');
Route::post('laporan/tambah_tanam/detail/download', 'Laporan\TambahTanamController@detailDownload');

// Laporan - Luas Panen
Route::get('laporan/luas_panen', 'Laporan\LuasPanenController@index');
Route::post('laporan/luas_panen/download', 'Laporan\LuasPanenController@download');
Route::get('laporan/luas_panen/detail', 'Laporan\LuasPanenController@detail');
Route::post('laporan/luas_panen/detail/download', 'Laporan\LuasPanenController@detailDownload');

// Laporan - Produksi
Route::get('laporan/produksi', 'Laporan\ProduksiController@index');
Route::post('laporan/produksi/download', 'Laporan\ProduksiController@download');
Route::get('laporan/produksi/detail', 'Laporan\ProduksiController@detail');
Route::post('laporan/produksi/detail/download', 'Laporan\ProduksiController@detailDownload');

// Laporan - Produktivitas
Route::get('laporan/produktivitas', 'Laporan\ProduktivitasController@index');
Route::post('laporan/produktivitas/download', 'Laporan\ProduktivitasController@download');
Route::get('laporan/produktivitas/detail', 'Laporan\ProduktivitasController@detail');
Route::post('laporan/produktivitas/detail/download', 'Laporan\ProduktivitasController@detailDownload');

// Laporan - Rekap Input Penyuluh
Route::get('laporan/rekap_input_penyuluh', 'Laporan\PenyuluhController@rekapInput');
Route::post('laporan/rekap_input_penyuluh/download', 'Laporan\PenyuluhController@rekapInputDownload');

// Laporan - Rekap User Penyuluh
Route::get('laporan/rekap_user_penyuluh', 'Laporan\PenyuluhController@rekapUser');
Route::post('laporan/rekap_user_penyuluh/download', 'Laporan\PenyuluhController@rekapUserDownload');


// Laporan - Optimalisasi Alsin
Route::get('laporan/opsin', 'Laporan\OpsinController@index');
Route::post('laporan/opsin/download', 'Laporan\OpsinController@download');
Route::get('laporan/opsin/realisasi', 'Laporan\OpsinController@realisasi');
Route::post('laporan/opsin/realisasi/download', 'Laporan\OpsinController@realisasiDownload');
Route::get('laporan/opsin/realisasi/detail', 'Laporan\OpsinController@realisasiDetail');
Route::post('laporan/opsin/realisasi/detail/download', 'Laporan\OpsinController@realisasiDetailDownload');