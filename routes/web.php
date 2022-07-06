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


use App\Http\Controllers\QrController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\Admin\Met\MetDataCrudController;
use App\Http\Controllers\DataProductController;
use App\Imports\PreProcessDavisHeaders;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

Route::get('', function () {
    return redirect('/login');
});

Auth::routes();

// Introduction page
Route::view('intro', 'intro')->name('intro');

// Routes for orignal data download page written in Vue 2
Route::resource('home', 'DataController')->middleware('auth');
Route::post('download', [DataController::class,'download']);

Route::get('uploadsuccess', function () {
    return view('uploadsuccess');
})->middleware('auth');

Route::resource('stations', 'StationController');

Route::post('show', [DataController::class,'show']);

Route::get('/data-download', function () {
    return view('data_download');
});
Route::post('/data-download/download', [DataProductController::class, 'index']);


//NEW Upload page
Route::view('data-upload', 'dataupload')->middleware('auth');
Route::post('files', [FileController::class,'store']);
Route::post('storeFile/{uploader_id}', [FileController::class,'storeFile']);
Route::post('cleanTable/{uploader_id}', [FileController::class,'cleanTable']);

Route::get('data/{id}/delete', [MetDataCrudController::class,'destroy']);

Route::post('files.store', [FileController::class,'store']);


Route::view('qr-codes', 'qr_code')->name('qr-codes');

Route::post('qr-newcodes', [QrController::class,'newCodes'])->name('qr-newcodes');
Route::get('qr-print', [QrController::class,'printView'])->name('qr-print');




Route::get('test', function(){

    $processor = (new PreProcessDavisHeaders);

    $fileWithMergedHeaders = $processor(base_path('tests/Files/large-davis.txt'));


   \Maatwebsite\Excel\Facades\Excel::import(
       new \App\Imports\DavisFileImport('large-test', 1),
       $fileWithMergedHeaders,
   null,
   \Maatwebsite\Excel\Excel::TSV);

   ddd('ok');
});


Route::get('test-event', function() {
    \App\Events\HelloWorld::dispatch(Auth::user());
});

Route::get('import-status/{upload_id}', [FileController::class, 'status']);
