<?php

namespace App\Exports\Download\Met;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Met\Station;

class MetadataDataExport implements WithTitle, WithHeadings, FromCollection
{
    use Exportable;

    // HTTP request
    protected $query;

    // fields to be extracted
    protected $fields = [];

    // constructor to set HTTP request object to private variable
    public function __construct(array $query = null)
    {
        $this->query = $query;

        $this->fields = [
            'criteria',
            'value',
        ];
    }

    public function collection()
    {
        // prepare aggregation for display
        $aggregation = $this->query['aggregation'];

        if ($aggregation == 'daily_data') {
            $aggregationForDisplay = 'Daily';
        } else if ($aggregation == 'tendays_data') {
            $aggregationForDisplay = 'Tendays';
        } else if ($aggregation == 'monthly_data') {
            $aggregationForDisplay = 'Monthly';
        } else if ($aggregation == 'yearly_data') {
            $aggregationForDisplay = 'Yearly';
        }


        // prepare met station id for display in CSV format
        $stationIds = $this->query['stations'];

        $stationIdsForDisplay = '';
        foreach ($stationIds as $stationId) {
            $stationIdsForDisplay = $stationIdsForDisplay . $stationId . ',';
        }
        $stationIdsForDisplay = rtrim($stationIdsForDisplay, ',');


        // prepare met station label for display in CSV format
        $stations = Station::select(['label'])->whereIn('id', $stationIds)->orderBy('id')->get()->all();

        $stationsForDisplay = '';
        foreach ($stations as $station) {
            $stationsForDisplay = $stationsForDisplay . $station->label . ',';
        }
        $stationsForDisplay = rtrim($stationsForDisplay, ',');


        return new Collection([
            ['Met Station ID', $stationIdsForDisplay],
            ['Met Station', $stationsForDisplay],
            ['Aggregation', $aggregationForDisplay], 
            ['From', $this->query['fromYear']],
            ['To', $this->query['toYear']],
        ]);
    }

    public function title(): string
    {
        return 'Metadata';
    }

    public function headings(): array
    {
        return $this->fields;
    }

}
