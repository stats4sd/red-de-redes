<?php

namespace App\Imports;

use App\Events\MetDataImportFailed;
use App\Models\Met\File;
use App\Models\Met\MetData;
use App\Models\Met\MetDataPreview;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Validators\ValidationException;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Throwable;

class ChinasFileImport implements ToModel, WithEvents, WithCustomCsvSettings, WithHeadingRow, WithChunkReading, WithBatchInserts, ShouldQueue, WithStrictNullComparison, SkipsEmptyRows, WithValidation
{

    protected array $keyMap;
    //protected File $fileRecord;
    protected User $user;
    private int $stationId;
    private string $upload_id;
    private mixed $file_id;

    use RemembersRowNumber;

    /**
     * @throws \JsonException
     */
    public function __construct(File $fileRecord, User $user)
    {

        // load in Keymap for Davis files
        $this->keyMap = json_decode(
            (
            file_get_contents(
                base_path('scripts/R/csv_column_list.json')
            )
            ), true, 512, JSON_THROW_ON_ERROR);

        //$this->fileRecord = $fileRecord;
        $this->upload_id = $fileRecord->upload_id;
        $this->file_id = $fileRecord->id;
        $this->stationId = $fileRecord->station_id;
        $this->user = $user;
    }

    /**
     * @throws Throwable
     */
    public function model(array $row)
    {
        cache()->forever("current_row_{$this->upload_id}", $this->getRowNumber());

        try {

            // if Date time do not exist; fail entire import
            if (!isset($row['fecha_hora'])) {
                throw new Exception('Cannot find date time column. Please check the formatting of your file.');
            }

            // rename $row array keys using column map:
            $newRow = collect($row)->mapWithKeys(function ($value, $key) {

                if (!isset($this->keyMap[$key])) {
                    return ['null' => $value];
                }

                $newKey = $this->keyMap[$key];

                // replace missing value entries with nulls:
                // 'missing' values are represented either by '999' or by some combination of spaces, dashes and dots.
                if ($value === 999 || Str::of($value)->test('/^[\-\,\.\s]+$/')) {
                    $value = null;
                }

                return [
                    $newKey => $value
                ];
            });

            if (isset($newRow['null'])) {
                unset($newRow['null']);
            }

            // create merged date_time
            $newRow['fecha_hora'] = (Carbon::createFromFormat('d/m/Y H:i:s', trim($newRow['fecha_hora'])));

            $newRow['upload_id'] = $this->upload_id;
            $newRow['file_id'] = $this->file_id;
            $newRow['station_id'] = $this->stationId;

            // also import chinas station type specific columns into davis specific columns for generating summary met data
            $newRow['hi_temp'] = $newRow['temperatura_externa'];
            $newRow['low_temp'] = $newRow['temperatura_externa'];
            $newRow['hi_speed'] = $newRow['velocidad_viento'];

            // Question: which rainfall column for chinas should be used as davis rainfall column "rain"?
            $newRow['rain'] = $newRow['lluvia_hora'];

            $metDataItem = MetDataPreview::create($newRow->toArray());
        } catch (Throwable $exception) {
            dump($row);
            dump($newRow);
            dump($this->keyMap);
            dump($this->getRowNumber());

            throw $exception;
        }

    }

    /**
     * @throws Exception
     */
    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {

                cache()->forever("start_date_{$this->upload_id}", now()->unix());
            },

            AfterImport::class => function (AfterImport $event) {
                cache(["end_date_{$this->upload_id}" => now()], now()->addMinute());
                cache()->forget("total_rows_{$this->upload_id}");
                cache()->forget("start_date_{$this->upload_id}");
                cache()->forget("current_row_{$this->upload_id}");
            },
        ];
    }

    public
    function getCsvSettings(): array
    {
        return [
            'delimiter' => ","
        ];
    }

    public
    function batchSize(): int
    {
        return 1000;
    }

    public
    function chunkSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return [
            // use mapped database column name here
            'fecha_hora' => 'required',
        ];
    }

}
