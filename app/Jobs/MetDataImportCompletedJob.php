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
        $metDataPreview = MetDataPreview::where('upload_id', '=', $this->fileRecord->upload_id)->orderBy('id')->paginate(10);

        $metDataPreviewCount = MetDataPreview::where('upload_id', $this->fileRecord->upload_id)->count('id');

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

        $maxDailyRain = MetDataPreview::where('upload_id', $this->fileRecord->upload_id)->groupByRaw('LEFT(`fecha_hora`, 10)')->sum('rain');

        if ($numberNotExistedRecords === $metDataPreviewCount) {
            $scenario = 1;
            $adviceMessage = "All " . $metDataPreviewCount . " record(s) are new records. Please kindly confirm to upload this data file.";
        } else if ($numberExistedRecords === $metDataPreviewCount) {
            $scenario = 2;
            $adviceMessage = "All " . $numberExistedRecords . " record(s) are already existed in system. Please kindly cancel this upload.";
        } else {
            $scenario = 3;
            $adviceMessage = $numberExistedRecords . " out of " . $metDataPreviewCount . " records are already existed in system. Please kindly tick below checkbox to confirm uploading non existed records or cancel this upload to further check data file correctness.";
        }

        MetDataImportCompleted::broadcast(
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
