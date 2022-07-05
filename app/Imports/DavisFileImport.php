<?php

namespace App\Imports;

use App\Models\Met\File;
use App\Models\Met\MetData;
use App\Models\Met\MetDataPreview;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class DavisFileImport implements ToModel, WithEvents, WithCustomCsvSettings, WithHeadingRow, WithChunkReading, WithBatchInserts, WithStrictNullComparison
{

    protected array $keyMap;
    private string $uploaderId;
    private int $stationId;

    use RemembersRowNumber, RegistersEventListeners;

    /**
     * @throws \JsonException
     */
    public function __construct(File $fileRecord)
    {
        HeadingRowFormatter::default('none');

        // load in Keymap for Davis files
        $this->keyMap = json_decode(
            (
            file_get_contents(
                base_path('scripts/R/txt_column_list.json')
            )
            ), true, 512, JSON_THROW_ON_ERROR);

        $this->upload_id = $fileRecord->upload_id;
        $this->stationId = $fileRecord->station_id;


    }

    public function model(array $row)
    {
        // rename $row array keys using column map:
        try {

            $newRow = collect($row)->mapWithKeys(function ($value, $key) {

                $newKey = $this->keyMap[$key];

                // replace missing value entries with nulls:
                if ($value === 999 || collect(['-', '--', '---', '----', '-----', '------'])->contains($value)) {
                    $value = null;
                }

                return [
                    $newKey => $value
                ];
            });

            // create merged date_time
            $newRow['fecha_hora'] = (Carbon::createFromFormat('d/m/y H:i', $newRow['fecha_hora'] . ' ' . $newRow['time']));

            $newRow['upload_id'] = $this->upload_id;
            $newRow['station_id'] = $this->stationId;

            $metDataItem = MetDataPreview::create($newRow->toArray());
        } catch (\Throwable $exception) {
            dump($row);
            dump($this->getRowNumber());
            throw $exception;
        }

    }



    public function getCsvSettings(): array
    {
        return [
            'delimiter' => "\t"
        ];
    }

    public function batchSize(): int
    {
        return 5000;
    }

    public function chunkSize(): int
    {
        return 5000;
    }
}
