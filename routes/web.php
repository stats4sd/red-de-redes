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
use Symfony\Component\Process\Process;
use App\Http\Controllers\DataController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\Admin\Met\MetDataCrudController;
use Symfony\Component\Process\Exception\ProcessFailedException;

Route::get('', function () {
    return redirect('/home');
});

Auth::routes();

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




Route::get('rtest', function(){
    $process = new Process(['/Program Files/R/R-3.6.1/bin/Rscript.exe', 'updated_test.R']);
        $process->setWorkingDirectory(base_path('scripts/R'));

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        ddd('ok');
});