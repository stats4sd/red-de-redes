<?php

namespace App\Exports\Download\Met;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MetDataWorkbookExport implements WithMultipleSheets
{
    protected $query;

    public function __construct(array $query = null)
    {
        $this->query = $query;
    }


    public function sheets():array
    {
        if (array_key_exists('aggregation', $this->query)) {
            $aggregation = $this->query['aggregation'];
        }

        if ($aggregation === 'daily_data') {
            return [
                new MetadataDataExport($this->query),
                new DailyMetDataExport($this->query),
            ];
        }

        if ($aggregation === 'tendays_data') {
            return [
                new MetadataDataExport($this->query),
                new TendaysMetDataExport($this->query),
            ];
        }

        if ($aggregation === 'monthly_data') {
            return [
                new MetadataDataExport($this->query),
                new MonthlyMetDataExport($this->query),
            ];
        }

        if ($aggregation === 'yearly_data') {
            return [
                new MetadataDataExport($this->query),
                new YearlyMetDataExport($this->query),
            ];
        }
    }

}
