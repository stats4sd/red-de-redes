<?php

namespace App\Exports\Download;

use Illuminate\Http\Request;
use App\Exports\Download\Met\MetDataExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DownloadWorkbookExport implements WithMultipleSheets
{
    // HTTP request
    protected $request;

    // constructor to set HTTP request object to private variable
    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    // determine to generate which excel sheet for extraction excel file
    public function sheets(): array
    {
        $sheets = [];

        // ***** Data dictionary ***** //
        // Question: Do we need a work sheet for data dictionary?
        //$sheets[] = new DataDictionaryExport();

        // ***** Met data ***** //
        $aggregation = $this->request->query('aggregation');

        if ($aggregation == 'raw_data') {
            $sheets[] = new MetDataExport($this->request);

        } else if ($aggregation == 'senamhi_daily') {
            // TODO
        } else if ($aggregation == 'senamhi_monthly') {
            // TODO
        } else if ($aggregation == 'daily_data') {
            // TODO
        } else if ($aggregation == 'tendays_data') {
            // TODO
        } else if ($aggregation == 'monthly_data') {
            // TODO
        } else if ($aggregation == 'yearly_data') {
            // TODO
        }


        // ***** Agronimic data, including plot level data and crop level data ***** //

        $plotLevelSuelos = $this->request->query('plotLevelSuelos');
        $plotLevelManejoDeLaParcela = $this->request->query('plotLevelManejoDeLaParcela');
        $cropLevelFenologia = $this->request->query('cropLevelFenologia');
        $cropLevelPlagas = $this->request->query('cropLevelPlagas');
        $cropLevelEnfermedades = $this->request->query('cropLevelEnfermedades');
        $cropLevelRendimientos = $this->request->query('cropLevelRendimientos');

        // plot level data
        if ($plotLevelSuelos) {
            // TODO
        }

        if ($plotLevelManejoDeLaParcela) {
            // TODO
        }

        // crop level data
        if ($cropLevelFenologia) {
            // TODO
        }

        if ($cropLevelPlagas) {
            // TODO
        }

        if ($cropLevelEnfermedades) {
            // TODO
        }

        if ($cropLevelRendimientos) {
            // TODO
        }

        return $sheets;
    }

}