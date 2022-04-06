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
            'criterios',
            'valor',
        ];
    }

    public function collection()
    {
        // prepare aggregation for display
        $aggregation = $this->query['aggregation'];

        if ($aggregation == 'daily_data') {
            $aggregationForDisplay = 'Diario';
        } else if ($aggregation == 'tendays_data') {
            $aggregationForDisplay = 'Diez días';
        } else if ($aggregation == 'monthly_data') {
            $aggregationForDisplay = 'Mensual';
        } else if ($aggregation == 'yearly_data') {
            $aggregationForDisplay = 'Anual';
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
            ['ID de la estación', $stationIdsForDisplay],
            ['Estación', $stationsForDisplay],
            ['Agregación', $aggregationForDisplay], 
            ['Año Inicial', $this->query['fromYear']],
            ['Año Final', $this->query['toYear']],
        ]);
    }

    public function title(): string
    {
        return 'Metadatos';
    }

    public function headings(): array
    {
        return $this->fields;
    }

}
