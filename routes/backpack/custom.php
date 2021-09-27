<?php

use App\Http\Controllers\Admin\Agronomic\CultivoCrudController;
use App\Http\Controllers\Admin\Agronomic\EnfermedadCrudController;
use App\Http\Controllers\Admin\Agronomic\FenologiaCrudController;
use App\Http\Controllers\Admin\Agronomic\ManejoParcelaCrudController;
use App\Http\Controllers\Admin\Agronomic\MuestraSueloCrudController;
use App\Http\Controllers\Admin\Agronomic\ParcelaCrudController;
use App\Http\Controllers\Admin\Agronomic\PlagaCrudController;
use App\Http\Controllers\Admin\Agronomic\RendimientoCrudController;
use App\Http\Controllers\Admin\Agronomic\SueloCrudController;
use App\Http\Controllers\Admin\ComunidadCrudController;
use App\Http\Controllers\Admin\DataMapCrudController;
use App\Http\Controllers\Admin\DepartamentoCrudController;
use App\Http\Controllers\Admin\LkpCultivoCrudController;
use App\Http\Controllers\Admin\LkpVariedadCrudController;
use App\Http\Controllers\Admin\Met\DailyCrudController;
use App\Http\Controllers\Admin\Met\DailyDataPreviewCrudController;
use App\Http\Controllers\Admin\Met\MetDataCrudController;
use App\Http\Controllers\Admin\Met\MonthlyCrudController;
use App\Http\Controllers\Admin\Met\ObservationCrudController;
use App\Http\Controllers\Admin\Met\TenDaysCrudController;
use App\Http\Controllers\Admin\Met\YearlyCrudController;
use App\Http\Controllers\Admin\MunicipioCrudController;
use App\Http\Controllers\Admin\RegionCrudController;
use App\Http\Controllers\Admin\StationCrudController;
use App\Http\Controllers\Admin\SubmissionCrudController;
use App\Http\Controllers\Admin\UserCrudController;
use App\Http\Controllers\Admin\XlsformCrudController;
use App\Http\Controllers\DashboardController;

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')]
], function () { // custom admin routes

    // General Admin + Platform Management
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::post('dashboard/charts', [DashboardController::class, 'charts']);
    Route::crud('user', UserCrudController::class);

    // Met Data
    Route::crud('data', MetDataCrudController::class);
    Route::crud('daily', DailyCrudController::class);
    Route::crud('tenDays', TenDaysCrudController::class);
    Route::crud('monthly', MonthlyCrudController::class);
    Route::crud('yearly', YearlyCrudController::class);

    Route::post('daily/download', [DailyCrudController::class, 'download']);
    Route::post('data/deleteByFilters', [MetDataCrudController::class, 'deleteByFilters']);
    Route::post('data/download', [MetDataCrudController::class, 'download']);
    Route::post('tenDays/download', [TenDaysCrudController::class, 'download']);
    Route::post('monthly/download', [MonthlyCrudController::class, 'download']);
    Route::post('yearly/download', [YearlyCrudController::class, 'download']);
    Route::crud('dailydatapreview', DailyDataPreviewCrudController::class);

    Route::crud('observation', ObservationCrudController::class);


    Route::crud('station', StationCrudController::class);




    Route::crud('region', RegionCrudController::class);
    Route::crud('departamento', DepartamentoCrudController::class);
    Route::crud('municipio', MunicipioCrudController::class);
    Route::crud('comunidad', ComunidadCrudController::class);

    Route::crud('cultivo', CultivoCrudController::class);
    Route::crud('fenologia', FenologiaCrudController::class);
    Route::crud('parcela', ParcelaCrudController::class);
    Route::crud('suelo', SueloCrudController::class);
    Route::crud('manejo-parcela', ManejoParcelaCrudController::class);
    Route::crud('plaga', PlagaCrudController::class);
    Route::crud('enfermedad', EnfermedadCrudController::class);
    Route::crud('rendimiento', RendimientoCrudController::class);
    Route::crud('submission', SubmissionCrudController::class);

    Route::crud('xlsform', XlsformCrudController::class);
    Route::crud('datamap', DataMapCrudController::class);
    Route::post('xlsform/{xlsform}/deploytokobo', [XlsformCrudController::class,'deployToKobo']);
    Route::post('xlsform/{xlsform}/syncdata', [XlsformCrudController::class,'syncData']);
    Route::post('xlsform/{xlsform}/archive', [XlsformCrudController::class,'archiveOnKobo']);
    Route::post('xlsform/{xlsform}/csvgenerate', [XlsformCrudController::class,'regenerateCsvFileAttachments']);


    Route::crud('lkpcultivo', LkpCultivoCrudController::class);
    Route::crud('lkpvariedad', LkpVariedadCrudController::class);
    Route::crud('muestrasuelo', MuestraSueloCrudController::class);


}); // this should be the absolute last line of this file
