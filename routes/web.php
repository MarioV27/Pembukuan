<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\cPemasukan;
use App\Http\Controllers\cPengeluaran;
use App\Http\Controllers\cPenyusutan;
use App\Http\Controllers\cHutang;
use App\Http\Controllers\cGaji;
use App\Http\Controllers\cAset;
use App\http\Controllers\cKategori;
use App\Http\Controllers\cKaryawan;
use App\Http\Controllers\cPiutang_Pemasukan;
use App\Http\Controllers\cPiutang_Pengeluaran;
use App\Http\Controllers\cKas;
use App\Http\Controllers\cWord;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//dashboard
Route::get('/', [Dashboard::class, 'dashboard']);
//pemasukan
Route::get('/Pemasukan', [cPemasukan::class, 'pemasukan']);
Route::get('/Pemasukan/form', [cPemasukan::class, 'view_form']);
Route::post('/Pemasukan/form', [cPemasukan::class, 'post_form']);
Route::get('/Pemasukan/edit/{id}', [cPemasukan::class, 'view_edit']);
Route::patch('/Pemasukan/edit/{id}', [cPemasukan::class, 'edit_form']);
Route::delete('/Pemasukan/hapus/{id}', [cPemasukan::class, 'delete_form']);
//pengeluaran
Route::get('/Pengeluaran', [cPengeluaran::class, 'pengeluaran']);
Route::get('/Pengeluaran/form', [cPengeluaran::class, 'view_form']);
Route::post('/Pengeluaran/form', [cPengeluaran::class, 'post_form']);
Route::get('/Pengeluaran/edit/{id}', [cPengeluaran::class, 'view_edit']);
Route::patch('/Pengeluaran/edit/{id}', [cPengeluaran::class, 'edit_form']);
Route::delete('/Pengeluaran/hapus/{id}', [cPengeluaran::class, 'delete_form']);
//penyusutan
Route::get('/Penyusutan', [cPenyusutan::class, 'penyusutan']);
Route::get('/Penyusutan/form', [cPenyusutan::class, 'view_form']);
Route::post('/Penyusutan/form', [cPenyusutan::class, 'post_form']);
Route::get('/Penyusutan/edit/{id}', [cPenyusutan::class, 'view_edit']);
Route::patch('/Penyusutan/edit/{id}', [cPenyusutan::class, 'edit_form']);
Route::delete('/Penyusutan/hapus/{id}', [cPenyusutan::class, 'delete_form']);
//hutang
Route::get('/Hutang', [cHutang::class, 'hutang']);
Route::get('/Hutang/form', [cHutang::class, 'view_form']);
Route::post('/Hutang/form', [cHutang::class, 'post_form']);
Route::get('/Hutang/edit/{id}', [cHutang::class, 'view_edit']);
Route::patch('/Hutang/edit/{id}', [cHutang::class, 'edit_form']);
Route::delete('/Hutang/hapus/{id}', [cHutang::class, 'delete_form']);
//gaji
Route::get('/Gaji', [cGaji::class, 'gaji']);
Route::get('/Gaji/form', [cGaji::class, 'view_form']);
Route::post('/Gaji/form', [cGaji::class, 'post_form']);
Route::get('/Gaji/edit/{id}', [cGaji::class, 'view_edit']);
Route::patch('/Gaji/edit/{id}', [cGaji::class, 'edit_form']);
Route::delete('/Gaji/hapus/{id}', [cGaji::class, 'delete_form']);
//aset
Route::get('/Aset', [cAset::class, 'aset']);
Route::get('/Aset/form', [cAset::class, 'view_form']);
Route::post('/Aset/form', [cAset::class, 'post_form']);
Route::get('/Aset/edit/{id}', [cAset::class, 'view_edit']);
Route::patch('/Aset/edit/{id}', [cAset::class, 'edit_form']);
Route::delete('/Aset/hapus/{id}', [cAset::class, 'delete_form']);
//karyawan
Route::get('/Karyawan', [cKaryawan::class, 'karyawan']);
Route::get('/Karyawan/form', [cKaryawan::class, 'view_form']);
Route::post('/Karyawan/form', [cKaryawan::class, 'post_form']);
Route::get('/Karyawan/edit/{id}', [cKaryawan::class, 'view_edit']);
Route::patch('/Karyawan/edit/{id}', [cKaryawan::class, 'edit_form']);
Route::delete('/Karyawan/hapus/{id}', [cKaryawan::class, 'delete_form']);
//piutang_pemasukan
Route::get('/Piutang_Pemasukan', [cPiutang_Pemasukan::class, 'piutang_pemasukan']);
Route::get('/Piutang_Pemasukan/form/{id}', [cPiutang_Pemasukan::class, 'view_form']);
Route::post('/Piutang_Pemasukan/form/{id}', [cPiutang_Pemasukan::class, 'post_form']);
Route::delete('/Piutang_Pemasukan/hapus/{id}', [cPiutang_Pemasukan::class, 'delete_form']);
Route::delete('/Piutang_Pemasukan/hapusarr/{selectedItems}', [cPiutang_Pemasukan::class, 'delete_formarr']);

//piutang_pengeluaran
Route::get('/Piutang_Pengeluaran', [cPiutang_Pengeluaran::class, 'piutang_pengeluaran']);
Route::get('/Piutang_Pengeluaran/form/{id}', [cPiutang_Pengeluaran::class, 'view_form']);
Route::post('/Piutang_Pengeluaran/form/{id}', [cPiutang_Pengeluaran::class, 'post_form']);
Route::delete('/Piutang_Pengeluaran/hapus/{id}', [cPiutang_Pengeluaran::class, 'delete_form']);
Route::delete('/Piutang_Pengeluaran/hapusarr/{selectedItems}', [cPiutang_Pengeluaran::class, 'delete_formarr']);
//kas
Route::get('/Kas', [cKas::class, 'kas']);
Route::get('/kasKategori', [cKas::class, 'KasKategori']);
Route::post('/wordTahun', [cWord::class, 'wordTahun']);
Route::post('/word', [cWord::class, 'word']);
//kategori
Route::get('/Kategori', [cKategori::class, 'kategori']);
Route::get('/Kategori/form', [cKategori::class, 'view_form']);
Route::post('/Kategori/form', [cKategori::class, 'post_form']);
Route::get('/Kategori/edit/{id}', [cKategori::class, 'view_edit']);
Route::patch('/Kategori/edit/{id}', [cKategori::class, 'edit_form']);
Route::delete('/Kategori/hapus/{id}', [cKategori::class, 'delete_form']);