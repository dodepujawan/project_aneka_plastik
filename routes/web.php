<?php

use App\Http\Controllers\CabangController;
use App\Http\Controllers\HargaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\MobileController;
use App\Http\Controllers\PajakController;
use App\Http\Controllers\QrScannerController;
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
    return redirect()->route('login');
});

Route::prefix('login')->group(function () {
    Route::get('/', [LoginController::class, 'login'])->name('login');
    Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin')->middleware('web');
    Route::get('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout')->middleware('auth');
});

Route::prefix('register')->middleware('auth')->group(function () {
    Route::get('/', [RegisterController::class, 'register'])->name('register');
    Route::post('actionregister', [RegisterController::class, 'actionregister'])->name('actionregister');
    Route::get('editregister', [RegisterController::class, 'editregister'])->name('editregister');
    Route::post('updateregister', [RegisterController::class, 'updateregister'])->name('updateregister');
    Route::get('listregister', [RegisterController::class, 'listregister'])->name('listregister');
    Route::get('filter_register', [RegisterController::class, 'filter_register'])->name('filter_register');
    Route::get('edit_list_register/{id}', [RegisterController::class, 'edit_list_register'])->name('edit_list_register');
    Route::post('update_list_register', [RegisterController::class, 'update_list_register'])->name('update_list_register');
    Route::delete('delete_list_register/{id}', [RegisterController::class, 'delete_list_register'])->name('delete_list_register');
    Route::get('/generate-user-id', [RegisterController::class, 'generate_user_id'])->name('generate_user_id');
});

Route::prefix('transaksi')->middleware('auth')->group(function () {
    Route::get('/', [TransaksiController::class, 'index'])->name('index_transaksi');
    Route::get('/api/users', [TransaksiController::class, 'get_users'])->name('get_users');
    Route::get('/api/barangs', [TransaksiController::class, 'get_barangs'])->name('get_barangs');
    Route::post('save/products', [TransaksiController::class, 'save_products'])->name('save_products');
    Route::get('/api/barangs/satuan', [TransaksiController::class, 'get_barang_satuan'])->name('get_barang_satuan');
    Route::get('/api/barangs/selected', [TransaksiController::class, 'get_barang_selected'])->name('get_barang_selected');
    // Show Edit Transaksi
    Route::get('/edit/show', [TransaksiController::class, 'index_edit'])->name('index_edit_transaksi');
    Route::get('/api/edit_transaksi', [TransaksiController::class, 'get_edit_transaksi_data'])->name('get_edit_transaksi_data');
    Route::get('/api/edit_transaksi/admin', [TransaksiController::class, 'get_edit_transaksi_data_admin'])->middleware('role:admin,staff')->name('get_edit_transaksi_data_admin');
    Route::get('/api/edit_transaksi/to_table', [TransaksiController::class, 'get_edit_transaksi_to_table'])->name('get_edit_transaksi_to_table');
    Route::post('update/products', [TransaksiController::class, 'update_products'])->name('update_products');
    Route::post('delete/products', [TransaksiController::class, 'delete_products'])->name('delete_products');
    // Show Approved Transaksi
    Route::get('/approved', [TransaksiController::class, 'approved'])->name('approved_transaksi');
    Route::get('/api/filter_approved_invoice', [TransaksiController::class, 'filter_approved_invoice'])->name('filter_approved_invoice');
    Route::get('/api/po_approved', [TransaksiController::class, 'get_po_approved_det'])->name('get_po_approved_det');
    Route::post('save/products_approved', [TransaksiController::class, 'save_products_approved'])->name('save_products_approved');
    Route::get('/success', [TransaksiController::class, 'success_transaksi'])->name('success_transaksi');
    Route::get('/api/filter_success_invoice', [TransaksiController::class, 'filter_success_invoice'])->name('filter_success_invoice');
    Route::get('/api/po_success', [TransaksiController::class, 'get_po_success_det'])->name('get_po_success_det');
});


Route::prefix('home')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index')->middleware('auth');
});

Route::prefix('cabang')->middleware('auth')->group(function () {
    Route::get('/', [CabangController::class, 'index'])->name('index_cabang');
    Route::get('/cabang_id', [CabangController::class, 'generate_cabang_id'])->name('generate_cabang_id');
    Route::post('/store', [CabangController::class, 'store'])->name('store_cabang');
    Route::get('/api/cabang', [CabangController::class, 'getcabang'])->name('get_cabang_api');
    Route::get('/api/cabang/datatables', [CabangController::class, 'get_cabang_datatables'])->name('get_cabang_api_datatables');
    Route::get('/index/list_cabang', [CabangController::class, 'index_list_cabang'])->name('index_list_cabang');
    Route::get('edit_list_cabang/{id}', [CabangController::class, 'edit_list_cabang'])->name('edit_list_cabang');
    Route::post('/update_cabang', [CabangController::class, 'update_cabang'])->name('update_cabang');
    Route::delete('delete_list_cabang/{id}', [CabangController::class, 'delete_list_cabang'])->name('delete_list_cabang');
});

Route::prefix('pdf')->group(function () {
    Route::get('/generate-pdf/{invoice_number}', [PDFController::class, 'generate_pdf'])->name('generate_pdf');
    Route::get('/generate-pdf/approved/{invoice_number}', [PDFController::class, 'generate_pdf_approved'])->name('generate_pdf_approved');
    Route::post('/generate-list-harga-pdf', [PDFController::class, 'generate_list_harga_pdf'])->name('generate_list_harga_pdf');
    Route::get('/generate-list-harga-pdf-node', [PDFController::class, 'generate_list_harga_pdf_node'])->name('generate_list_harga_pdf_node');
});

Route::prefix('harga')->middleware('auth')->group(function () {
    Route::get('/list-harga', [HargaController::class, 'list_harga'])->name('list_harga');
    Route::get('/api/filter_list_harga', [HargaController::class, 'filter_list_harga'])->name('filter_list_harga');
});

Route::prefix('mobile')->middleware('auth')->group(function () {
    Route::get('/index', [MobileController::class, 'index'])->name('index_mobile');
    Route::get('/edit/show', [MobileController::class, 'index_edit_transaksi_mobile'])->name('index_edit_transaksi_mobile');
});

Route::prefix('pajak')->middleware('auth')->group(function () {
    Route::get('/get-pajak', [PajakController::class, 'get_pajak'])->name('get_pajak');
    Route::post('/edit/show', [PajakController::class, 'update_pajak'])->name('update_pajak');
});

Route::prefix('qris')->middleware('auth')->group(function () {
    Route::get('/index', [QrScannerController::class, 'index_qris'])->name('index_qris');
    Route::post('/user/qris', [QrScannerController::class, 'cek_user_qr'])->name('cek_user_qr');
    Route::post('/simpan-kode-qris', [QrScannerController::class, 'simpan_kode_qris'])->name('simpan_kode_qris');
    Route::get('/qris/list', [QrScannerController::class, 'qris_list'])->name('qris_list');
    Route::delete('/qris/delete/{id}', [QrScannerController::class, 'delete_qris'])->name('delete_qris');
});

Route::post('/broadcasting/auth', function () {
    return Auth::user();
 });

// Route::post('/broadcasting/auth', function (Illuminate\Http\Request $request) {
//     return Auth::user() ? Broadcast::auth($request) : abort(403);
// });


// admin123
// github : project_aneka_plastik
