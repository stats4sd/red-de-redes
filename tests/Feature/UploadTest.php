<?php

namespace Tests\Feature;

use App\Models\Met\Station;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UploadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_uploads_a_davis_txt_file(): void
    {
        $user = User::factory()->create(['type' => 'admin']);
        $station = Station::factory()->create(['type' => 'davis']);

        $uploadFile = new UploadedFile(
            base_path('tests/Files/default-davis.txt'),
            'default-davis.txt'
        );

        $this->actingAs($user)
            ->post(url('files'), [
                'data-file' => $uploadFile,
                'selectedStation' => $station->id,
                'selectedUnitTemp' => 'ºC',
                'selectedUnitPres' => 'hpa',
                'selectedUnitWind' => 'm/s',
                'selectedUnitRain' => 'mm',
            ]);

        // confirm preview table has 100 records for $station dated from  2018-12-19 to 2018-12-20
        $this->assertDatabaseCount('met_data_preview', 100);

        // confirm values from 1st entry are in the database;
        $this->assertDatabaseHas('met_data_preview',
            [
                'fecha_hora' => '2018-12-19 20:30:00',
                'intervalo' => 15,
                'temperatura_interna' => 15.4,
                'humedad_interna' => 62,
                'temperatura_externa' => 6.4,
                'humedad_externa' => 80,
                'presion_relativa' => 1002,
                'presion_absoluta' => null, // ?? why should this be null?
                'velocidad_viento' => 1.3,
                'sensacion_termica' => null,
                'rafaga' => null,
                'direccion_del_viento' => 'SSW',
                'punto_rocio' => 3.2,
                'lluvia_hora' => 2.2,
                'lluvia_24_horas' => null,
                'lluvia_semana' => null,
                'lluvia_mes' => null,
                'lluvia_total' => null,
                'hi_temp' => 7.4,
                'low_temp' => 1.2,
                'wind_cod' => null,
                'wind_run' => 1.21,
                'hi_speed' => 1.8,
                'hi_dir' => 'SW',
                'wind_cod_dom' => null,
                'wind_chill' => 5.7,
                'index_heat' => 6.3,
                'index_thw' => 5.6,
                'index_thsw' => 1.7,
                'rain' => 1.11,
                'solar_rad' => 1,
                'solar_energy' => 1.11,
                'radsolar_max' => 3,
                'uv_index' => 3.3,
                'uv_dose' => 3.33,
                'uv_max' => 4.4,
                'heat_days_d' => 0.124,
                'cool_days_d' => 0.57,
                'in_dew' => 8.2,
                'in_heat' => 14.8,
                'in_emc' => 11.45,
                'in_air_density' => 1.1971,
                'evapotran' => 0.01,
                'wind_tx' => 1,
                'iss_recept' => 1.8,
                'station_id' => $station->id,
            ]);


    }

    /** @test */
    public function it_uploads_a_david_file_with_missing_values(): void
    {
        $user = User::factory()->create(['type' => 'admin']);
        $station = Station::factory()->create(['type' => 'davis']);

        $uploadFile = new UploadedFile(
            base_path('tests/Files/missing-davis.txt'),
            'missing-davis.txt'
        );

        $this->actingAs($user)
            ->post(url('files'), [
                'data-file' => $uploadFile,
                'selectedStation' => $station->id,
                'selectedUnitTemp' => 'ºC',
                'selectedUnitPres' => 'hpa',
                'selectedUnitWind' => 'm/s',
                'selectedUnitRain' => 'mm',
            ]);

        $this->assertDatabaseHas('met_data_preview', [
            'fecha_hora' => '2016-04-13 21:00:00',
            'station_id' => $station->id,
            'temperatura_externa' => null,
            'hi_temp' => 60.0,
            'low_temp' => null,
        ]);
    }

    /** @test */
    public function it_uploads_a_davis_file_with_999_values(): void
    {
        $user = User::factory()->create(['type' => 'admin']);
        $station = Station::factory()->create(['type' => 'davis']);

        $uploadFile = new UploadedFile(
            base_path('tests/Files/999-davis.txt'),
            'missing-davis.txt'
        );

        $this->actingAs($user)
            ->post(url('files'), [
                'data-file' => $uploadFile,
                'selectedStation' => $station->id,
                'selectedUnitTemp' => 'ºC',
                'selectedUnitPres' => 'hpa',
                'selectedUnitWind' => 'm/s',
                'selectedUnitRain' => 'mm',
            ]);

        $this->assertDatabaseHas('met_data_preview', [
            'fecha_hora' => '2016-04-13 21:00:00',
            'station_id' => $station->id,
            'temperatura_externa' => null,
            'hi_temp' => 60.0,
            'low_temp' => null,
            'rain' => null,
        ]);
    }
}
