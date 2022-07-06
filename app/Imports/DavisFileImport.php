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
use Maatwebsite\Excel\Validators\ValidationException;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Throwable;

class DavisFileImport implements ToModel, WithEvents, WithCustomCsvSettings, WithHeadingRow, WithChunkReading, WithBatchInserts, ShouldQueue, WithStrictNullComparison, SkipsEmptyRows, WithValidation
{

    protected array $keyMap;
    protected File $fileRecord;
    protected User $user;
    private int $stationId;
    private string $upload_id;

    use RemembersRowNumber;

    /**
     * @throws \JsonException
     */
    public function __construct(File $fileRecord, User $user)
    {
        HeadingRowFormatter::default('none');

        // load in Keymap for Davis files
        $this->keyMap = json_decode(
            (
            file_get_contents(
                base_path('scripts/R/txt_column_list.json')
            )
            ), true, 512, JSON_THROW_ON_ERROR);

        $this->$fileRecord = $fileRecord;
        $this->upload_id = $fileRecord->upload_id;
        $this->file_id = $fileRecord->id;
        $this->stationId = $fileRecord->station_id;


    }

    /**
     * @throws Throwable
     */
    public function model(array $row)
    {
        cache()->forever("current_row_{$this->upload_id}", $this->getRowNumber());

        // rename $row array keys using column map:
        try {

            $newRow = collect($row)->mapWithKeys(function ($value, $key) {

                if(!isset($this->keyMap[$key])) {
                    return ['null' => $value];
                }

                $newKey = $this->keyMap[$key];

                // replace missing value entries with nulls:
                if ($value === 999 || collect(['-', '--', '---', '----', '-----', '------'])->contains($value)) {
                    $value = null;
                }

                return [
                    $newKey => $value
                ];
            });

            if(isset($newRow['null'])) {
                unset($newRow['null']);
            }

            // create merged date_time
            $newRow['fecha_hora'] = (Carbon::createFromFormat('d/m/y H:i', $newRow['fecha_hora'] . ' ' . $newRow['time']));

            $newRow['upload_id'] = $this->upload_id;
            $newRow['file_id'] = $this->file_id;
            $newRow['station_id'] = $this->stationId;

            $metDataItem = MetDataPreview::create($newRow->toArray());
        } catch (Throwable $exception) {
            dump($row);
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

            ImportFailed::class => function(ImportFailed $event) {
                MetDataImportFailed::dispatch($this->fileRecord, $event->getException(), $this->user);
            }];
    }


    public
    function getCsvSettings(): array
    {
        return [
            'delimiter' => "\t"
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
            'date' => ['required'],
            'time' => ['required'],
        ];
    }
}
