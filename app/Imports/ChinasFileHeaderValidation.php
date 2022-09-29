<?php

namespace App\Imports;

use App\Events\MetDataImportFailed;
use App\Models\Met\File;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class ChinasFileHeaderValidation implements ToModel, WithValidation, WithHeadingRow, SkipsEmptyRows, WithStrictNullComparison, WithCustomCsvSettings
{


    /**
     * @throws \JsonException
     */
    public function __construct()
    {
    }

    public function model(array $row)
    {
        // this file will not import any data. It is only used to validate the column headers for a specially prepared file containing a single entry.
    }

    public function rules(): array
    {
        return [
            // use handled header column name here
            'fecha_hora' => 'required',
        ];
    }

        public
    function getCsvSettings(): array
    {
        return [
            'delimiter' => ","
        ];
    }


}
