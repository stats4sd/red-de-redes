<?php

namespace App\Jobs\Met;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class GenerateDailySummary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
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
        DB::unprepared('truncate table met_summary_daily;');

        DB::unprepared('insert into met_summary_daily (
            select
                id,
                fecha,
                station_id,
                daily_data.max_temperatura_interna,
                daily_data.max_temperatura_interna,
                daily_data.avg_temperatura_interna,
                daily_data.max_humedad_interna,
                daily_data.min_humedad_interna,
                daily_data.avg_humedad_interna,
                daily_data.max_temperatura_externa,
                daily_data.min_temperatura_externa,
                daily_data.avg_temperatura_externa,
                daily_data.max_humedad_externa,
                daily_data.min_humedad_externa,
                daily_data.avg_humedad_externa,
                daily_data.max_presion_relativa,
                daily_data.min_presion_relativa,
                daily_data.avg_presion_relativa,
                daily_data.max_presion_absoluta,
                daily_data.min_presion_absoluta,
                daily_data.avg_presion_absoluta,
                daily_data.max_velocidad_viento,
                daily_data.min_velocidad_viento,
                daily_data.avg_velocidad_viento,
                daily_data.max_sensacion_termica,
                daily_data.min_sensacion_termica,
                daily_data.avg_sensacion_termica,
                daily_data.lluvia_24_horas_total,
                now(),
                now()
            from daily_data);'
        );

    }
}
