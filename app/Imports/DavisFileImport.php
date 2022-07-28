<?php

namespace App\Imports;

use App\Events\MetDataImportFailed;
use App\Models\Met\File;
use App\Models\Met\MetData;
use App\Models\Met\MetDataPreview;
use App\Models\User;
use App\Services\UnitConversionService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
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

class DavisFileImport implements ToModel, WithEvents, WithCustomCsvSettings, WithHeadingRow, WithChunkReading, WithBatchInserts, ShouldQueue, WithStrictNullComparison, SkipsEmptyRows, WithValidation
{

    protected array $keyMap;
    //protected File $fileRecord;
    protected User $user;
    private int $stationId;
    private string $upload_id;
    private mixed $file_id;
    private UnitConversionService $unitConvertor;
    private Collection $neededConversions;

    use RemembersRowNumber;

    /**
     * @throws \JsonException
     */
    public function __construct(File $fileRecord, Collection $neededConversions, User $user)
    {
        HeadingRowFormatter::default('none');

        // load in Keymap for Davis files
        $this->keyMap = json_decode(
            (
            file_get_contents(
                base_path('scripts/R/txt_column_list.json')
            )
            ), true, 512, JSON_THROW_ON_ERROR);

        //$this->fileRecord = $fileRecord;
        $this->upload_id = $fileRecord->upload_id;
        $this->file_id = $fileRecord->id;
        $this->stationId = $fileRecord->station_id;
        $this->user = $user;

        $this->neededConversions = $neededConversions;
        $this->unitConvertor = new UnitConversionService();


    }

    /**
     * @throws Throwable
     */
    public function model(array $row)
    {
        cache()->forever("current_row_{$this->upload_id}", $this->getRowNumber());

        try {

            // if Date and Time do not exist; fail entire import
            if (!isset($row['date'], $row['time'])) {
                throw new Exception('Cannot find date or time columns. Please check the formatting of your file.');
            }

            // rename $row array keys using column map:
            $newRow = collect($row)->mapWithKeys(function ($value, $key) {

                if (!isset($this->keyMap[$key])) {
                    return ['null' => $value];
                }

                $newKey = $this->keyMap[$key];

                // replace missing value entries with nulls:
                if ($value === 999 || collect(['-', '--', '---', '----', '-----', '------'])->contains($value)) {
                    $value = null;
                }

                // convert the value if required
                return $this->handleUnitConversions($newKey, $value);

            });

            if (isset($newRow['null'])) {
                unset($newRow['null']);
            }

            // create merged date_time
            $newRow['fecha_hora'] = (Carbon::createFromFormat('d/m/y H:i', $newRow['fecha_hora'] . ' ' . $newRow['time']));

            $newRow['upload_id'] = $this->upload_id;
            $newRow['file_id'] = $this->file_id;
            $newRow['station_id'] = $this->stationId;

            return new MetDataPreview($newRow->toArray());
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
        ];
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
        return 300;
    }

    public
    function chunkSize(): int
    {
        return 300;
    }

    public function rules(): array
    {
        return [
            'date' => ['required'],
            'time' => ['required'],
        ];
    }

    public function handleUnitConversions($key, $value): array
    {
        if ($this->neededConversions->contains('farenheitToCelcius') && $this->unitConvertor::getTempColumns()->contains($key)) {
            return [
                $key => $this->unitConvertor::farenheitToCelcius($value)
            ];
        }

        if ($this->neededConversions->contains('inhgToHpa') && $this->unitConvertor::getPressureColumns()->contains($key)) {
            return [
                $key => $this->unitConvertor::inhgToHpa($value)
            ];
        }

        if ($this->neededConversions->contains('mmhgToHpa') && $this->unitConvertor::getPressureColumns()->contains($key)) {
            return [
                $key => $this->unitConvertor::mmhgToHpa($value)
            ];
        }

        if ($this->neededConversions->contains('kmhToMs') && $this->unitConvertor::getWindSpeedColumns()->contains($key)) {
            return [
                $key => $this->unitConvertor::kmhToMs($value)
            ];
        }

        if ($this->neededConversions->contains('mphToMs') && $this->unitConvertor::getWindSpeedColumns()->contains($key)) {
            return [
                $key => $this->unitConvertor::mphToMs($value)
            ];
        }

        if ($this->neededConversions->contains('inchToMm') && $this->unitConvertor::getRainfallColumns()->contains($key)) {
            return [
                $key => $this->unitConvertor::inchToMm($value)
            ];
        }

        return [
            $key => $value
        ];
    }

}
