<?php

namespace App\Jobs;

use Exception;
use App\Models\User;
use App\Models\Region;
use App\Models\DataMap;
use App\Models\Xlsform;
use App\Models\Comunidad;
use App\Models\Municipio;
use App\Models\Submission;
use Illuminate\Support\Str;
use App\Models\Departamento;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Controllers\DataMapController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GetDataFromKobo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $form;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Xlsform $form)
    {
        $this->form = $form;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Http::withBasicAuth(config('services.kobo.username'), config('services.kobo.password'))
        ->withHeaders(['Accept' => 'application/json'])
        ->get(config('services.kobo.endpoint_v2').'/assets/'.$this->form->kobo_id.'/data/')
        ->throw()
        ->json();

        \Log::info($response);

        $data = $response['results'];

        //compare
        $submissions = Submission::where('xlsform_id', '=', $this->form->id)->get();

        foreach ($data as $newSubmission) {
            if (!in_array($newSubmission['_id'], $submissions->pluck('id')->toArray())) {
                Submission::create([
                    'id' => $newSubmission['_id'],
                    'uuid' => $newSubmission['_uuid'],
                    'xlsform_id' => $this->form->id,
                    'content' => json_encode($newSubmission),
                    'fecha_hora' => $newSubmission['_submission_time'],
                ]);

                //merge all the modules
                // if (!array_key_exists('modulo_loop', $newSubmission)  || !array_key_exists('modulos', $newSubmission)) {
                //     throw new Exception('You are trying get data to wrong form');

                // } else {

                //     if (array_key_exists('modulo_loop/extra_modulo', $newSubmission['modulo_loop'][0])) {
                //         $newSubmission['modulos'] = $newSubmission['modulos'] . ' ' . $newSubmission['modulo_loop'][0]['modulo_loop/extra_modulo'];
                //     } else {
                //         $newSubmission['modulos'] = $newSubmission['modulos'];
                //     }
                //     $newSubmission['modulos'] = explode(' ', $newSubmission['modulos']);
                // }


                // $newSubmission = $this->deleteGroupName($newSubmission);

                // //There are only ever 2 iterations of the modulo_loop, so flatten it.
                // if (count($newSubmission['modulo_loop'])==2) {
                //     $newSubmission['modulo_loop'] = $newSubmission['modulo_loop'][0] + $newSubmission['modulo_loop'][1];
                // } else {
                //     $newSubmission['modulo_loop'] = $newSubmission['modulo_loop'][0];
                // }


                // // Create new parcela record if needed
                // if ($newSubmission['registrar_parcela'] == 1) {
                //     //Update or create the record for all the locations
                //     if ($newSubmission['region'] == 999) {
                //         $region = Region::updateOrCreate([
                //             'name' => $newSubmission['otra_region']
                //         ]);

                //         //update the region id value
                //         $newSubmission['region'] = $region->id;
                //     }

                //     if ($newSubmission['departamento'] == 999) {
                //         $departamento = Departamento::updateOrCreate([
                //             'region_id' => $newSubmission['region'],
                //             'name' => $newSubmission['otro_departamento']
                //         ]);

                //         //update the departamento id value
                //         $newSubmission['departamento'] = $departamento->id;
                //     }

                //     if ($newSubmission['municipio'] == 999) {
                //         $municipio = Municipio::updateOrCreate([
                //             'departamento_id' => $newSubmission['departamento'],
                //             'name' => $newSubmission['otro_municipio']
                //         ]);

                //         //update the municipio id value
                //         $newSubmission['municipio'] = $municipio->id;
                //     }

                //     if ($newSubmission['comunidad'] == 999) {
                //         $location = explode(" ", $newSubmission['gps']);
                //         $comunidad = Comunidad::updateOrCreate(
                //             [
                //                 'municipio_id' => $newSubmission['municipio'],
                //                 'name' => $newSubmission['otra_comunidad']
                //             ],
                //             [
                //                 'latitude' => isset($location[0]) ? $location[0] : null,
                //                 'longitude' => isset($location[1]) ? $location[1] : null,
                //                 'altitude' => isset($location[2]) ? $location[2] : null
                //             ]
                //         );
                //         $newSubmission['comunidad'] = $comunidad->id;
                //     }
                //     $dataMap = DataMap::findorfail('parcela');
                //     DataMapController::newRecord($dataMap, $newSubmission);
                // } else {
                //     $dataMap = DataMap::findorfail('parcela');
                //     DataMapController::updateRecord($dataMap, $newSubmission, $newSubmission['parcela_id']);
                // }

                // // Iterate through the other parcela-level modules.
                // foreach ($newSubmission['modulos'] as $parcelaModule) {
                //     if ($parcelaModule === 'C') {
                //         $this->processCultivoData($newSubmission);
                //     } else {
                //         $dataMap = DataMap::findOrFail($parcelaModule);
                //         $data = $newSubmission['modulo_loop'];
                //         $data['parcela_id'] = $newSubmission['parcela_id'];
                //         $data['_id'] = $newSubmission['_id'];

                //         DataMapController::newRecord($dataMap, $data);
                //     }
                // } // end foreach plot-level module
            } // end if $submission does not exist
        } // end foreach record
    } // end handle method

    public function processCultivoData($newSubmission)
    {
        foreach ($newSubmission['modulo_loop']['cultivo_loop'] as $cultivo) {

            // add parcela_id and submission_id to the current cultivo

            $cultivo['parcela_id'] =  $newSubmission['parcela_id'];
            $cultivo['_id'] =  $newSubmission['_id'];

            // get the cultivo_id from the creation of the cultivo
            $dataMap = DataMap::findorfail('C');
            $new_cultivo = DataMapController::newRecord($dataMap, $cultivo);
            $cultivo['cultivo_id'] =  $new_cultivo->id;

            // check if there is the modulo_cultivo_loop and merge the array in one

            if (array_key_exists('modulo_cultivo_loop', $cultivo)) {

                if (count($cultivo['modulo_cultivo_loop'])==2) {
                    $cultivo['modulo_cultivo_loop'] = $cultivo['modulo_cultivo_loop'][0] + $cultivo['modulo_cultivo_loop'][1];
                } else {
                    $cultivo['modulo_cultivo_loop'] = $cultivo['modulo_cultivo_loop'][0];

                }

                //check if there is an extra_modulo_cultivo and create the cultivo_modules

                if (array_key_exists('extra_modulo_cultivo', $cultivo['modulo_cultivo_loop'])) {
                    $cultivo_modules = $cultivo['modulos_cultivo'] . ' '. $cultivo['modulo_cultivo_loop']['extra_modulo_cultivo'];
                } else {
                    $cultivo_modules = $cultivo['modulos_cultivo'];
                }

                $cultivo_modules = explode(' ', $cultivo_modules);
                $cultivo = $cultivo + $cultivo['modulo_cultivo_loop'];
                unset($cultivo['modulo_cultivo_loop']);




                foreach ($cultivo_modules as $cultivo_module) {
                    //eventually we should get to a place where we don't need to manually map module '3' to either plagas or engermedades...
                    // but for now this will have to do...

                    if ($cultivo_module == 3 && array_key_exists('problema', $cultivo)) {
                        if ($cultivo['problema'] == 'plagas' || $cultivo['problema'] == 'ambas') {
                            $dataMap_cultivo_module = DataMap::findorfail('plagas');
                            DataMapController::newRecord($dataMap_cultivo_module, $cultivo);

                        }
                        if ($cultivo['problema'] == 'enfermedades' || $cultivo['problema'] == 'ambas') {

                            $dataMap_cultivo_module = DataMap::findorfail('enfermedades');
                            DataMapController::newRecord($dataMap_cultivo_module, $cultivo);

                        }
                    }else if($cultivo_module != 3){
                            $dataMap_cultivo_module = DataMap::findorfail($cultivo_module);
                            DataMapController::newRecord($dataMap_cultivo_module, $cultivo);
                    }

                }
            }
        }
    }



    public function deleteGroupName(array $array)
    {
        foreach ($array as $key => $value) {
            if (Str::contains($key, '/') && $key!="formhub/uuid") {
                // e.g. replace $newSubmission['groupname/subgroup/name'] with $newSubmission['name']
                unset($array[$key]);
                $key = explode('/', $key);
                $key = end($key);
                $array[$key] = $value;
            }
            if (is_array($value)) {
                $array[$key] = $this->deleteGroupName($value);
            }
        }
        return $array;
    }
}
