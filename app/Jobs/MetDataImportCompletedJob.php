<?php

namespace App\Jobs;

use App\Events\MetDataImportCompleted;
use App\Models\Met\File;
use App\Models\Met\MetDataPreview;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class MetDataImportCompletedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public File $fileRecord, public ?User $user)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // Check that the import was finished (check count of records in db vs in file)

        $metDataPreviewCount = MetDataPreview::where('upload_id', $this->fileRecord->upload_id)->count('id');

        if ($metDataPreviewCount < $this->fileRecord->total_records_count) {
            MetDataImportCompleted::broadcast(
                false,
                [
                    'error' => 'Hubo errores al cargar el archivo.',

                ],
                $this->user,
            );

            return;
        }

        $metDataPreview = MetDataPreview::where('upload_id', '=', $this->fileRecord->upload_id)->orderBy('id')->paginate(10);


        // check number of records already existed in database
        $sqlExistedRecords = " SELECT COUNT(*) as number_of_records";
        $sqlExistedRecords .= " FROM met_data ta, met_data_preview tb";
        $sqlExistedRecords .= " WHERE tb.upload_id = '" . $this->fileRecord->upload_id . "'";
        $sqlExistedRecords .= " AND ta.fecha_hora = tb.fecha_hora";
        $sqlExistedRecords .= " AND ta.station_id = tb.station_id;";

        // execute custom SELECT SQL
        $existedRecordsResults = DB::select($sqlExistedRecords);
        $numberExistedRecords = $existedRecordsResults[0]->number_of_records;

        // number of not existed records = number of uploaded records - number of existed records
        $numberNotExistedRecords = $metDataPreviewCount - $numberExistedRecords;

        // update file record
        $this->fileRecord->update([
            'new_records_count' => $numberNotExistedRecords,
            'duplicate_records_count' => $numberExistedRecords,
        ]);

        $maxTemp = MetDataPreview::where('upload_id', $this->fileRecord->upload_id)->max('hi_temp');
        $minTemp = MetDataPreview::where('upload_id', $this->fileRecord->upload_id)->min('low_temp');

        $maxDailyRain = MetDataPreview::where('upload_id', $this->fileRecord->upload_id)
            ->selectRaw('sum(rain) as aggregate')
            ->groupByRaw('LEFT(`fecha_hora`, 10)')->get()->max()['aggregate'];

        if ($numberNotExistedRecords === $metDataPreviewCount) {
            $scenario = 1;
            $adviceMessage = "Todos los registros " . $metDataPreviewCount . " son registros nuevos. Confirme para cargar este archivo de datos.";
        } else if ($numberExistedRecords === $metDataPreviewCount) {
            $scenario = 2;
            $adviceMessage = "Todos los registros " . $numberExistedRecords . " ya existen en el sistema. Cancele esta carga.";
        } else {
            $scenario = 3;
            $adviceMessage = $numberExistedRecords . " fuera de " . $metDataPreviewCount. " los registros ya existen en el sistema. Si esto es lo esperado, marque la casilla de verificación a continuación para confirmar la carga o cancele esta carga para verificar el archivo de datos y los datos existentes en la plataforma.";
        }

        MetDataImportCompleted::broadcast(
            true,
            [
                'met_data_preview' => $metDataPreview,
                'number_uploaded_records' => $metDataPreviewCount,
                'number_existed_records' => $numberExistedRecords,
                'number_not_existed_records' => $numberNotExistedRecords,
                'scenario' => $scenario,
                'adviceMessage' => $adviceMessage,
                'error_data' => null,
                'min_temp' => $minTemp,
                'max_temp' => $maxTemp,
                'max_daily_rain' => $maxDailyRain,
            ],
            $this->user,
        );
    }
}
