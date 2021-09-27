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


use App\Http\Controllers\Admin\Met\MetDataCrudController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\QrController;

Route::get('', function () {
    return redirect('/home');
});

Auth::routes();

Route::resource('home', DataController::class)->middleware('auth');
Route::post('download', [DataController::class,'download']);


Route::post('show', [DataController::class,'show']);


//NEW Upload page
Route::view('data-upload', 'data-upload')->middleware('auth');
Route::post('files', [FileController::class,'store']);
Route::post('storeFile/{uploader_id}', [FileController::class,'storeFile']);
Route::post('cleanTable/{uploader_id}', [FileController::class,'cleanTable']);

Route::get('data/{id}/delete', [MetDataCrudController::class,'destroy']);

Route::post('files.store', [FileController::class,'store']);


Route::view('qr-codes', 'qr_code')->name('qr-codes');

Route::post('qr-newcodes', [QrController::class,'newCodes'])->name('qr-newcodes');
Route::get('qr-print', [QrController::class,'printView'])->name('qr-print');
