<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Comunidad
 *
 * @property int $id
 * @property int $municipio_id
 * @property string $name
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $altitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Municipio $municipio
 * @method static \Illuminate\Database\Eloquent\Builder|Comunidad newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comunidad newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comunidad query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comunidad whereAltitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comunidad whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comunidad whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comunidad whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comunidad whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comunidad whereMunicipioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comunidad whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comunidad whereUpdatedAt($value)
 */
	class Comunidad extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Agronomic\Cultivo
 *
 * @property int $id
 * @property string $lkp_cultivo_id
 * @property string|null $lkp_variedad_id
 * @property string $parcela_id
 * @property string|null $propiedad_cultivo
 * @property string|null $propiedad_variedad
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Agronomic\Enfermedade[] $enfermedades
 * @property-read int|null $enfermedades_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Agronomic\Fenologia[] $fenologias
 * @property-read int|null $fenologias_count
 * @property-read \App\Models\LkpCultivo $lkp_cultivos
 * @property-read \App\Models\LkpVariedad|null $lkp_variedad
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Agronomic\ManejoParcela[] $manejo_parcelas
 * @property-read int|null $manejo_parcelas_count
 * @property-read \App\Models\Agronomic\Parcela $parcela
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Agronomic\Plaga[] $plagas
 * @property-read int|null $plagas_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Agronomic\Rendimento[] $rendimentos
 * @property-read int|null $rendimentos_count
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Cultivo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Cultivo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Cultivo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Cultivo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Cultivo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Cultivo whereLkpCultivoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Cultivo whereLkpVariedadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Cultivo whereParcelaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Cultivo wherePropiedadCultivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Cultivo wherePropiedadVariedad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Cultivo whereUpdatedAt($value)
 */
	class Cultivo extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Met\Daily
 *
 * @property int|null $id
 * @property string $fecha
 * @property int|null $id_station
 * @property \App\Models\Met\Station|null $station
 * @property string|null $max_temperatura_interna
 * @property string|null $min_temperatura_interna
 * @property string|null $avg_temperatura_interna
 * @property int|null $max_humedad_interna
 * @property int|null $min_humedad_interna
 * @property string|null $avg_humedad_interna
 * @property string|null $max_temperatura_externa
 * @property string|null $min_temperatura_externa
 * @property string|null $avg_temperatura_externa
 * @property int|null $max_humedad_externa
 * @property int|null $min_humedad_externa
 * @property string|null $avg_humedad_externa
 * @property string|null $max_presion_relativa
 * @property string|null $min_presion_relativa
 * @property string|null $avg_presion_relativa
 * @property string|null $max_presion_absoluta
 * @property string|null $min_presion_absoluta
 * @property string|null $avg_presion_absoluta
 * @property string|null $max_velocidad_viento
 * @property string|null $min_velocidad_viento
 * @property string|null $avg_velocidad_viento
 * @property string|null $max_sensacion_termica
 * @property string|null $min_sensacion_termica
 * @property string|null $avg_sensacion_termica
 * @property string|null $lluvia_24_horas_total
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily query()
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereAvgHumedadExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereAvgHumedadInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereAvgPresionAbsoluta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereAvgPresionRelativa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereAvgSensacionTermica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereAvgTemperaturaExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereAvgTemperaturaInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereAvgVelocidadViento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereIdStation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereLluvia24HorasTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereMaxHumedadExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereMaxHumedadInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereMaxPresionAbsoluta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereMaxPresionRelativa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereMaxSensacionTermica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereMaxTemperaturaExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereMaxTemperaturaInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereMaxVelocidadViento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereMinHumedadExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereMinHumedadInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereMinPresionAbsoluta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereMinPresionRelativa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereMinSensacionTermica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereMinTemperaturaExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereMinTemperaturaInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereMinVelocidadViento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Daily whereStation($value)
 */
	class Daily extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Met\DailyDataPreview
 *
 * @property int|null $id
 * @property string $fecha
 * @property int $id_station
 * @property string $uploader_id
 * @property string|null $max_temperatura_interna
 * @property string|null $min_temperatura_interna
 * @property string|null $avg_temperatura_interna
 * @property int|null $max_humedad_interna
 * @property int|null $min_humedad_interna
 * @property string|null $avg_humedad_interna
 * @property string|null $max_temperatura_externa
 * @property string|null $min_temperatura_externa
 * @property string|null $avg_temperatura_externa
 * @property int|null $max_humedad_externa
 * @property int|null $min_humedad_externa
 * @property string|null $avg_humedad_externa
 * @property string|null $max_presion_relativa
 * @property string|null $min_presion_relativa
 * @property string|null $avg_presion_relativa
 * @property string|null $max_presion_absoluta
 * @property string|null $min_presion_absoluta
 * @property string|null $avg_presion_absoluta
 * @property string|null $max_velocidad_viento
 * @property string|null $min_velocidad_viento
 * @property string|null $avg_velocidad_viento
 * @property string|null $max_sensacion_termica
 * @property string|null $min_sensacion_termica
 * @property string|null $avg_sensacion_termica
 * @property string|null $lluvia_24_horas_total
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview query()
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereAvgHumedadExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereAvgHumedadInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereAvgPresionAbsoluta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereAvgPresionRelativa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereAvgSensacionTermica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereAvgTemperaturaExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereAvgTemperaturaInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereAvgVelocidadViento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereIdStation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereLluvia24HorasTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereMaxHumedadExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereMaxHumedadInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereMaxPresionAbsoluta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereMaxPresionRelativa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereMaxSensacionTermica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereMaxTemperaturaExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereMaxTemperaturaInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereMaxVelocidadViento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereMinHumedadExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereMinHumedadInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereMinPresionAbsoluta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereMinPresionRelativa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereMinSensacionTermica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereMinTemperaturaExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereMinTemperaturaInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereMinVelocidadViento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\DailyDataPreview whereUploaderId($value)
 */
	class DailyDataPreview extends \Eloquent {}
}

namespace App\Models{

    use App\Models\Met\MetData;

    /**
 * AppModelsData *
 * @property string $fecha_hora
 * @property int $id
 * @property int|null $intervalo
 * @property string|null $temperatura_interna
 * @property int|null $humedad_interna
 * @property string|null $temperatura_externa
 * @property int|null $humedad_externa
 * @property string|null $presion_relativa
 * @property string|null $presion_absoluta
 * @property string|null $velocidad_viento
 * @property string|null $sensacion_termica
 * @property string|null $rafaga
 * @property string|null $direccion_del_viento
 * @property string|null $punto_rocio
 * @property string|null $lluvia_hora
 * @property string|null $lluvia_24_horas
 * @property string|null $lluvia_semana
 * @property string|null $lluvia_mes
 * @property string|null $lluvia_total
 * @property string|null $hi_temp
 * @property string|null $low_temp
 * @property string|null $wind_cod
 * @property string|null $wind_run
 * @property string|null $hi_speed
 * @property string|null $hi_dir
 * @property string|null $wind_cod_dom
 * @property string|null $wind_chill
 * @property string|null $index_heat
 * @property string|null $index_thw
 * @property string|null $index_thsw
 * @property string|null $rain
 * @property string|null $solar_rad
 * @property string|null $solar_energy
 * @property string|null $radsolar_max
 * @property string|null $uv_index
 * @property string|null $uv_dose
 * @property string|null $uv_max
 * @property string|null $heat_days_d
 * @property string|null $cool_days_d
 * @property string|null $in_dew
 * @property string|null $in_heat
 * @property string|null $in_emc
 * @property string|null $in_air_density
 * @property string|null $evapotran
 * @property string|null $soil_1_moist
 * @property string|null $soil_2_moist
 * @property string|null $leaf_wet1
 * @property string|null $leaf_wet2
 * @property string|null $leaf_wet3
 * @property string|null $leaf_wet4
 * @property string|null $wind_samp
 * @property string|null $wind_tx
 * @property string|null $iss_recept
 * @property int|null $id_station
 * @property int|null $observation_id
 * @property string $meteobridge_latitude
 * @property string $meteobridge_longitude
 * @property string $meteobridge_altitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $leaf_temp_1
 * @property string|null $leaf_temp_2
 * @property string|null $leaf_temp_3
 * @property string|null $leaf_temp_4
 * @property string|null $soil_temp_1
 * @property string|null $soil_temp_2
 * @property string|null $soil_temp_3
 * @property string|null $soil_temp_4
 * @property string|null $soil_3_moist
 * @property string|null $soil_4_moist
 * @property int $meteobridge
 * @property string|null $hardware_id
 * @property-read \App\Models\Met\Observation|null $observation
 * @property-read \App\Models\Met\Station|null $station
 * @method static \Illuminate\Database\Eloquent\Builder|MetData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MetData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MetData query()
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereCoolDaysD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereDireccionDelViento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereEvapotran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereFechaHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereHardwareId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereHeatDaysD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereHiDir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereHiSpeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereHiTemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereHumedadExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereHumedadInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereIdStation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereInAirDensity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereInDew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereInEmc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereInHeat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereIndexHeat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereIndexThsw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereIndexThw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereIntervalo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereIssRecept($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereLeafTemp1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereLeafTemp2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereLeafTemp3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereLeafTemp4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereLeafWet1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereLeafWet2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereLeafWet3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereLeafWet4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereLluvia24Horas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereLluviaHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereLluviaMes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereLluviaSemana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereLluviaTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereLowTemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereMeteobridge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereMeteobridgeAltitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereMeteobridgeLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereMeteobridgeLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereObservationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData wherePresionAbsoluta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData wherePresionRelativa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData wherePuntoRocio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereRadsolarMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereRafaga($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereRain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereSensacionTermica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereSoil1Moist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereSoil2Moist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereSoil3Moist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereSoil4Moist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereSoilTemp1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereSoilTemp2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereSoilTemp3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereSoilTemp4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereSolarEnergy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereSolarRad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereTemperaturaExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereTemperaturaInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereUvDose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereUvIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereUvMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereVelocidadViento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereWindChill($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereWindCod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereWindCodDom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereWindRun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereWindSamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetData whereWindTx($value)
 */
	class Data extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\DataMap
 *
 * @property string $id
 * @property string $title
 * @property array $variables
 * @property string $model
 * @property int $location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $select_multiple if this is set, this data map is for data at a different level, based on a select_multiple and possibly a repeat group based on that select_multiple response.
 * @property string|null $select_multiple_other the name of the "enter other value" question linked to the select_multiple variable
 * @property string|null $select_multiple_other_label the variable name that contains the "specify other" question. (This is so we can pull in the user-specified label)
 * @property string|null $repeat_group the name of the repeat group to look inside to find the main variables for this data map
 * @property string|null $inner_name the variable name of the calculate with the `selected-at(pos(..))` code (inside the repeat group)
 * @property string|null $innter_label the variable name of the calculate with the `jr:choice-name()` code (inside the repeat group)
 * @method static \Illuminate\Database\Eloquent\Builder|DataMap newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DataMap newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DataMap query()
 * @method static \Illuminate\Database\Eloquent\Builder|DataMap whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataMap whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataMap whereInnerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataMap whereInnterLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataMap whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataMap whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataMap whereRepeatGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataMap whereSelectMultiple($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataMap whereSelectMultipleOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataMap whereSelectMultipleOtherLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataMap whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataMap whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DataMap whereVariables($value)
 */
	class DataMap extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Departamento
 *
 * @property int $id
 * @property int $region_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Municipio[] $municipios
 * @property-read int|null $municipios_count
 * @property-read \App\Models\Region $region
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento query()
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento whereUpdatedAt($value)
 */
	class Departamento extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Agronomic\Enfermedade
 *
 * @property int $id
 * @property int $cultivo_id
 * @property string|null $name
 * @property int $submission_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Agronomic\Cultivo $cultivo
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Enfermedade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Enfermedade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Enfermedade query()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Enfermedade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Enfermedade whereCultivoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Enfermedade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Enfermedade whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Enfermedade whereSubmissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Enfermedade whereUpdatedAt($value)
 */
	class Enfermedade extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Agronomic\Fenologia
 *
 * @property int $id
 * @property int $cultivo_id
 * @property int|null $epoca_siembra
 * @property string|null $fecha_siembra
 * @property string|null $fecha_emergencia
 * @property string|null $fecha_hojas
 * @property string|null $fecha_floracion
 * @property string|null $fecha_fructificacion
 * @property string|null $fecha_maduracion
 * @property string|null $fecha_cosecha
 * @property int|null $edad_plantacion
 * @property string|null $fecha_dormida
 * @property string|null $fecha_hinchada
 * @property string|null $fecha_cuajado
 * @property int $submission_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Agronomic\Cultivo $cultivo
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Fenologia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Fenologia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Fenologia query()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Fenologia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Fenologia whereCultivoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Fenologia whereEdadPlantacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Fenologia whereEpocaSiembra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Fenologia whereFechaCosecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Fenologia whereFechaCuajado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Fenologia whereFechaDormida($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Fenologia whereFechaEmergencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Fenologia whereFechaFloracion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Fenologia whereFechaFructificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Fenologia whereFechaHinchada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Fenologia whereFechaHojas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Fenologia whereFechaMaduracion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Fenologia whereFechaSiembra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Fenologia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Fenologia whereSubmissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Fenologia whereUpdatedAt($value)
 */
	class Fenologia extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LkpCultivo
 *
 * @property string $id
 * @property string $name
 * @property string|null $propiedad
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Agronomic\Cultivo[] $cultivos
 * @property-read int|null $cultivos_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LkpVariedad[] $variedad
 * @property-read int|null $variedad_count
 * @method static \Illuminate\Database\Eloquent\Builder|LkpCultivo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LkpCultivo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LkpCultivo query()
 * @method static \Illuminate\Database\Eloquent\Builder|LkpCultivo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LkpCultivo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LkpCultivo whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LkpCultivo wherePropiedad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LkpCultivo whereUpdatedAt($value)
 */
	class LkpCultivo extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LkpVariedad
 *
 * @property string $id
 * @property string $lkp_cultivo_id
 * @property string $name
 * @property string|null $propiedad
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\LkpCultivo $lkp_cultivo
 * @method static \Illuminate\Database\Eloquent\Builder|LkpVariedad newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LkpVariedad newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LkpVariedad query()
 * @method static \Illuminate\Database\Eloquent\Builder|LkpVariedad whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LkpVariedad whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LkpVariedad whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LkpVariedad whereLkpCultivoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LkpVariedad whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LkpVariedad wherePropiedad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LkpVariedad whereUpdatedAt($value)
 */
	class LkpVariedad extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Agronomic\ManejoParcela
 *
 * @property int $id
 * @property int $cultivo_id
 * @property string|null $fecha_roturado_suelo
 * @property string|null $tipo_preparacion_roturado
 * @property string|null $fecha_rastrado_suelo
 * @property string|null $tipo_preparacion_rastrado
 * @property string|null $fecha_fertilizacion
 * @property string|null $tipo_fertilizacion_suelo
 * @property string|null $abono_organico
 * @property string|null $abono_cantidad_kg
 * @property string|null $fertilizante_quimico
 * @property string|null $fertilizante_cantidad_kg
 * @property string|null $foliar_producto
 * @property string|null $tipo_riego
 * @property string|null $fuente_agua
 * @property string|null $tipo_deshierbe
 * @property string|null $fecha_aporque
 * @property string|null $tipo_aporque
 * @property string|null $fecha_tazeo
 * @property string|null $tipo_tazeo
 * @property string|null $fecha_poda
 * @property string|null $tipo_poda
 * @property string|null $fecha_control_fitosanitario
 * @property string|null $tipo_control_fitosanitario
 * @property string|null $tipo_producto
 * @property string|null $producto
 * @property int $submission_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Agronomic\Cultivo $cultivo
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela query()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereAbonoCantidadKg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereAbonoOrganico($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereCultivoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereFechaAporque($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereFechaControlFitosanitario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereFechaFertilizacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereFechaPoda($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereFechaRastradoSuelo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereFechaRoturadoSuelo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereFechaTazeo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereFertilizanteCantidadKg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereFertilizanteQuimico($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereFoliarProducto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereFuenteAgua($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereProducto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereSubmissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereTipoAporque($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereTipoControlFitosanitario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereTipoDeshierbe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereTipoFertilizacionSuelo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereTipoPoda($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereTipoPreparacionRastrado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereTipoPreparacionRoturado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereTipoProducto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereTipoRiego($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereTipoTazeo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\ManejoParcela whereUpdatedAt($value)
 */
	class ManejoParcela extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Monthly
 *
 * @property string $fecha
 * @property int|null $id_station
 * @property \App\Models\Met\Station|null $station
 * @property string|null $max_temperatura_interna
 * @property string|null $min_temperatura_interna
 * @property string|null $avg_temperatura_interna
 * @property int|null $max_humedad_interna
 * @property int|null $min_humedad_interna
 * @property string|null $avg_humedad_interna
 * @property string|null $max_temperatura_externa
 * @property string|null $min_temperatura_externa
 * @property string|null $avg_temperatura_externa
 * @property int|null $max_humedad_externa
 * @property int|null $min_humedad_externa
 * @property string|null $avg_humedad_externa
 * @property string|null $max_presion_relativa
 * @property string|null $min_presion_relativa
 * @property string|null $avg_presion_relativa
 * @property string|null $max_presion_absoluta
 * @property string|null $min_presion_absoluta
 * @property string|null $avg_presion_absoluta
 * @property string|null $max_velocidad_viento
 * @property string|null $min_velocidad_viento
 * @property string|null $avg_velocidad_viento
 * @property string|null $max_sensacion_termica
 * @property string|null $min_sensacion_termica
 * @property string|null $avg_sensacion_termica
 * @property string|null $lluvia_24_horas_total
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly query()
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereAvgHumedadExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereAvgHumedadInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereAvgPresionAbsoluta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereAvgPresionRelativa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereAvgSensacionTermica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereAvgTemperaturaExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereAvgTemperaturaInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereAvgVelocidadViento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereIdStation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereLluvia24HorasTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereMaxHumedadExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereMaxHumedadInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereMaxPresionAbsoluta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereMaxPresionRelativa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereMaxSensacionTermica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereMaxTemperaturaExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereMaxTemperaturaInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereMaxVelocidadViento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereMinHumedadExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereMinHumedadInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereMinPresionAbsoluta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereMinPresionRelativa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereMinSensacionTermica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereMinTemperaturaExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereMinTemperaturaInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereMinVelocidadViento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Monthly whereStation($value)
 */
	class Monthly extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Agronomic\MuestraSuelo
 *
 * @property int $id
 * @property string $parcela_id
 * @property int $submission_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Agronomic\Parcela $parcela
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\MuestraSuelo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\MuestraSuelo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\MuestraSuelo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\MuestraSuelo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\MuestraSuelo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\MuestraSuelo whereParcelaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\MuestraSuelo whereSubmissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\MuestraSuelo whereUpdatedAt($value)
 */
	class MuestraSuelo extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Municipio
 *
 * @property int $id
 * @property int $departamento_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comunidad[] $comunidades
 * @property-read int|null $comunidades_count
 * @property-read \App\Models\Departamento $departamento
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio query()
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio whereDepartamentoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Municipio whereUpdatedAt($value)
 */
	class Municipio extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Met\Observation
 *
 * @property int $id
 * @property string $files
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Met\MetData[] $data
 * @property-read int|null $data_count
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Observation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Observation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Observation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Observation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Observation whereFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Observation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Observation whereUpdatedAt($value)
 */
	class Observation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Agronomic\Parcela
 *
 * @property string $id
 * @property int $comunidad_id
 * @property string|null $fecha
 * @property string|null $area_originale
 * @property string|null $area_m2
 * @property string|null $pendiente
 * @property int|null $drenaje
 * @property int|null $salinidad
 * @property string $latitude
 * @property string|null $image
 * @property string $longitude
 * @property string $altitude
 * @property string $accuracy
 * @property int $submission_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Agronomic\Cultivo[] $cultivos
 * @property-read int|null $cultivos_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Agronomic\MuestraSuelo[] $muestra_suelos
 * @property-read int|null $muestra_suelos_count
 * @property-read \App\Models\Submission $submissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Agronomic\Suelo[] $suelos
 * @property-read int|null $suelos_count
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Parcela newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Parcela newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Parcela query()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Parcela whereAccuracy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Parcela whereAltitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Parcela whereAreaM2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Parcela whereAreaOriginale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Parcela whereComunidadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Parcela whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Parcela whereDrenaje($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Parcela whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Parcela whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Parcela whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Parcela whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Parcela whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Parcela wherePendiente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Parcela whereSalinidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Parcela whereSubmissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Parcela whereUpdatedAt($value)
 */
	class Parcela extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Agronomic\Plaga
 *
 * @property int $id
 * @property int $cultivo_id
 * @property string|null $name
 * @property string|null $cantidad_insectos_m2
 * @property string|null $cantidad_larvas
 * @property int|null $mosca_numero
 * @property int|null $mosca_trampas
 * @property int|null $mosca_dias
 * @property int|null $presencia_mosca
 * @property string|null $plaga_fecha
 * @property int $submission_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Agronomic\Cultivo $cultivo
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Plaga newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Plaga newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Plaga query()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Plaga whereCantidadInsectosM2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Plaga whereCantidadLarvas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Plaga whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Plaga whereCultivoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Plaga whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Plaga whereMoscaDias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Plaga whereMoscaNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Plaga whereMoscaTrampas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Plaga whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Plaga wherePlagaFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Plaga wherePresenciaMosca($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Plaga whereSubmissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Plaga whereUpdatedAt($value)
 */
	class Plaga extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\QrCode
 *
 * @property int $id
 * @property string $prefix
 * @property string $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QrCode whereUpdatedAt($value)
 */
	class QrCode extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Region
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Departamento[] $departamentos
 * @property-read int|null $departamentos_count
 * @method static \Illuminate\Database\Eloquent\Builder|Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region query()
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereUpdatedAt($value)
 */
	class Region extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Agronomic\Rendimento
 *
 * @property int $id
 * @property int $cultivo_id
 * @property string|null $cantidad_cosechada_kg
 * @property string|null $superficie_cosechada_m2
 * @property string|null $peso_muestra_tuberculos
 * @property string|null $peso_danados_tuberculos
 * @property string|null $peso_muestra_grano
 * @property string|null $peso_danado_grano
 * @property int $submission_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $cantidad_cosechada_p
 * @property-read \App\Models\Agronomic\Cultivo $cultivo
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Rendimento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Rendimento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Rendimento query()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Rendimento whereCantidadCosechadaKg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Rendimento whereCantidadCosechadaP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Rendimento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Rendimento whereCultivoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Rendimento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Rendimento wherePesoDanadoGrano($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Rendimento wherePesoDanadosTuberculos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Rendimento wherePesoMuestraGrano($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Rendimento wherePesoMuestraTuberculos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Rendimento whereSubmissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Rendimento whereSuperficieCosechadaM2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Rendimento whereUpdatedAt($value)
 */
	class Rendimento extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Met\Station
 *
 * @property int $id
 * @property string|null $hardware_id
 * @property string $label
 * @property string $latitude
 * @property string $longitude
 * @property string|null $altitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Met\MetData[] $data
 * @property-read int|null $data_count
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Station newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Station newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Station query()
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Station whereAltitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Station whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Station whereHardwareId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Station whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Station whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Station whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Station whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Station whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Station whereUpdatedAt($value)
 */
	class Station extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Submission
 *
 * @property int $id
 * @property string $uuid
 * @property int $xlsform_id
 * @property mixed $content
 * @property string $fecha_hora
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $altitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Xlsform $xls_form
 * @method static \Illuminate\Database\Eloquent\Builder|Submission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Submission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Submission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Submission whereAltitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Submission whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Submission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Submission whereFechaHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Submission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Submission whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Submission whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Submission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Submission whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Submission whereXlsformId($value)
 */
	class Submission extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Agronomic\Suelo
 *
 * @property int $id
 * @property string $parcela_id
 * @property string $materia_organica
 * @property string $textura
 * @property string $pH
 * @property int $submission_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Agronomic\Parcela $parcela
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Suelo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Suelo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Suelo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Suelo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Suelo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Suelo whereMateriaOrganica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Suelo wherePH($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Suelo whereParcelaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Suelo whereSubmissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Suelo whereTextura($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agronomic\Suelo whereUpdatedAt($value)
 */
	class Suelo extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Met\TenDays
 *
 * @property string|null $min_fecha
 * @property string|null $max_fecha
 * @property int|null $id_station
 * @property \App\Models\Met\Station|null $station
 * @property string|null $max_temperatura_interna
 * @property string|null $min_temperatura_interna
 * @property string|null $avg_temperatura_interna
 * @property int|null $max_humedad_interna
 * @property int|null $min_humedad_interna
 * @property string|null $avg_humedad_interna
 * @property string|null $max_temperatura_externa
 * @property string|null $min_temperatura_externa
 * @property string|null $avg_temperatura_externa
 * @property int|null $max_humedad_externa
 * @property int|null $min_humedad_externa
 * @property string|null $avg_humedad_externa
 * @property string|null $max_presion_relativa
 * @property string|null $min_presion_relativa
 * @property string|null $avg_presion_relativa
 * @property string|null $max_presion_absoluta
 * @property string|null $min_presion_absoluta
 * @property string|null $avg_presion_absoluta
 * @property string|null $max_velocidad_viento
 * @property string|null $min_velocidad_viento
 * @property string|null $avg_velocidad_viento
 * @property string|null $max_sensacion_termica
 * @property string|null $min_sensacion_termica
 * @property string|null $avg_sensacion_termica
 * @property string|null $lluvia_24_horas_total
 * @property int|null $group_by
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays query()
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereAvgHumedadExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereAvgHumedadInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereAvgPresionAbsoluta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereAvgPresionRelativa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereAvgSensacionTermica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereAvgTemperaturaExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereAvgTemperaturaInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereAvgVelocidadViento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereGroupBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereIdStation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereLluvia24HorasTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereMaxFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereMaxHumedadExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereMaxHumedadInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereMaxPresionAbsoluta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereMaxPresionRelativa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereMaxSensacionTermica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereMaxTemperaturaExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereMaxTemperaturaInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereMaxVelocidadViento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereMinFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereMinHumedadExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereMinHumedadInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereMinPresionAbsoluta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereMinPresionRelativa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereMinSensacionTermica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereMinTemperaturaExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereMinTemperaturaInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereMinVelocidadViento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\TenDays whereStation($value)
 */
	class TenDays extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $type
 * @property string|null $kobo_id
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereKoboId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{

    use App\Models\Met\MetDataPreview;

    /**
 * App\Models\Met\MetDataPreview
 *
 * @property string $fecha_hora
 * @property int $id
 * @property int|null $intervalo
 * @property string|null $temperatura_interna
 * @property int|null $humedad_interna
 * @property string|null $temperatura_externa
 * @property int|null $humedad_externa
 * @property string|null $presion_relativa
 * @property string|null $presion_absoluta
 * @property string|null $velocidad_viento
 * @property string|null $sensacion_termica
 * @property string|null $rafaga
 * @property string|null $direccion_del_viento
 * @property string|null $punto_rocio
 * @property string|null $lluvia_hora
 * @property string|null $lluvia_24_horas
 * @property string|null $lluvia_semana
 * @property string|null $lluvia_mes
 * @property string|null $lluvia_total
 * @property string|null $hi_temp
 * @property string|null $low_temp
 * @property string|null $wind_cod
 * @property string|null $wind_run
 * @property string|null $hi_speed
 * @property string|null $hi_dir
 * @property string|null $wind_cod_dom
 * @property string|null $wind_chill
 * @property string|null $index_heat
 * @property string|null $index_thw
 * @property string|null $index_thsw
 * @property string|null $rain
 * @property string|null $solar_rad
 * @property string|null $solar_energy
 * @property string|null $radsolar_max
 * @property string|null $uv_index
 * @property string|null $uv_dose
 * @property string|null $uv_max
 * @property string|null $heat_days_d
 * @property string|null $cool_days_d
 * @property string|null $in_dew
 * @property string|null $in_heat
 * @property string|null $in_emc
 * @property string|null $in_air_density
 * @property string|null $evapotran
 * @property string|null $soil_1_moist
 * @property string|null $soil_2_moist
 * @property string|null $leaf_wet1
 * @property string|null $leaf_wet2
 * @property string|null $leaf_wet3
 * @property string|null $leaf_wet4
 * @property string|null $wind_samp
 * @property string|null $wind_tx
 * @property string|null $iss_recept
 * @property int $id_station
 * @property int|null $observation_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $leaf_temp_1
 * @property string|null $leaf_temp_2
 * @property string|null $leaf_temp_3
 * @property string|null $leaf_temp_4
 * @property string|null $soil_temp_1
 * @property string|null $soil_temp_2
 * @property string|null $soil_temp_3
 * @property string|null $soil_temp_4
 * @property string|null $soil_3_moist
 * @property string|null $soil_4_moist
 * @property string $uploader_id
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview query()
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereCoolDaysD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereDireccionDelViento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereEvapotran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereFechaHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereHeatDaysD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereHiDir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereHiSpeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereHiTemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereHumedadExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereHumedadInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereIdStation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereInAirDensity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereInDew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereInEmc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereInHeat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereIndexHeat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereIndexThsw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereIndexThw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereIntervalo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereIssRecept($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereLeafTemp1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereLeafTemp2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereLeafTemp3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereLeafTemp4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereLeafWet1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereLeafWet2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereLeafWet3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereLeafWet4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereLluvia24Horas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereLluviaHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereLluviaMes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereLluviaSemana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereLluviaTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereLowTemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereObservationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview wherePresionAbsoluta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview wherePresionRelativa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview wherePuntoRocio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereRadsolarMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereRafaga($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereRain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereSensacionTermica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereSoil1Moist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereSoil2Moist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereSoil3Moist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereSoil4Moist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereSoilTemp1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereSoilTemp2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereSoilTemp3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereSoilTemp4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereSolarEnergy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereSolarRad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereTemperaturaExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereTemperaturaInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereUploaderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereUvDose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereUvIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereUvMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereVelocidadViento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereWindChill($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereWindCod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereWindCodDom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereWindRun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereWindSamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MetDataPreview whereWindTx($value)
 */
	class WeatherDataPreview extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Xlsform
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $xlsfile
 * @property string|null $kobo_id
 * @property string|null $kobo_version_id
 * @property int $is_active If true, form is active and deployed on Kobotools
 * @property string|null $enketo_url
 * @property string|null $link_page
 * @property string|null $description
 * @property array|null $media
 * @property mixed|null $content
 * @property int $live If true, this form is available to projects to use
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array $csv_lookups should be an array of objects, each with 2 properties: "mysql_view" and "csv_file"
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Submission[] $submissions
 * @property-read int|null $submissions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform query()
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereCsvLookups($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereEnketoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereKoboId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereKoboVersionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereLinkPage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereMedia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xlsform whereXlsfile($value)
 */
	class Xlsform extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Met\Yearly
 *
 * @property string $fecha
 * @property int|null $id_station
 * @property \App\Models\Met\Station|null $station
 * @property string|null $max_temperatura_interna
 * @property string|null $min_temperatura_interna
 * @property string|null $avg_temperatura_interna
 * @property int|null $max_humedad_interna
 * @property int|null $min_humedad_interna
 * @property string|null $avg_humedad_interna
 * @property string|null $max_temperatura_externa
 * @property string|null $min_temperatura_externa
 * @property string|null $avg_temperatura_externa
 * @property int|null $max_humedad_externa
 * @property int|null $min_humedad_externa
 * @property string|null $avg_humedad_externa
 * @property string|null $max_presion_relativa
 * @property string|null $min_presion_relativa
 * @property string|null $avg_presion_relativa
 * @property string|null $max_presion_absoluta
 * @property string|null $min_presion_absoluta
 * @property string|null $avg_presion_absoluta
 * @property string|null $max_velocidad_viento
 * @property string|null $min_velocidad_viento
 * @property string|null $avg_velocidad_viento
 * @property string|null $max_sensacion_termica
 * @property string|null $min_sensacion_termica
 * @property string|null $avg_sensacion_termica
 * @property string|null $lluvia_24_horas_total
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly query()
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereAvgHumedadExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereAvgHumedadInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereAvgPresionAbsoluta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereAvgPresionRelativa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereAvgSensacionTermica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereAvgTemperaturaExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereAvgTemperaturaInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereAvgVelocidadViento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereIdStation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereLluvia24HorasTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereMaxHumedadExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereMaxHumedadInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereMaxPresionAbsoluta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereMaxPresionRelativa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereMaxSensacionTermica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereMaxTemperaturaExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereMaxTemperaturaInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereMaxVelocidadViento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereMinHumedadExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereMinHumedadInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereMinPresionAbsoluta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereMinPresionRelativa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereMinSensacionTermica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereMinTemperaturaExterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereMinTemperaturaInterna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereMinVelocidadViento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Met\Yearly whereStation($value)
 */
	class Yearly extends \Eloquent {}
}

