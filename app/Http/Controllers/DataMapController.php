<?php

namespace App\Http\Controllers;

use App\Events\NewDataVariableSpotted;
use App\Models\Comunidad;
use App\Models\DataMap;
use App\Models\Parcela;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DataMapController extends Controller
{
    public static function newRecord(DataMap $dataMap, Array $data)
    {
		
    	$newModel = DataMapController::createNewModel($dataMap, $data);
		$class = 'App\\Models\\'.$dataMap->model;
	    $newItem = new $class();
	    $newItem->fill($newModel);
	    $newItem->save();
        return $newItem;
    }

    //update exists plot
    public static function updateRecord(DataMap $dataMap, Array $data, $id)
    {
    	$model = DataMapController::createNewModel($dataMap, $data);
    	$class = 'App\\Models\\'.$dataMap->model;
	    $class::where('id', $id)->update($model);
        return $newItem;
    }

    public static function createNewModel(DataMap $dataMap, Array $data)
    {
    	//add the submission_id    		
		$newModel = [
        "submission_id" => $data['_id']
    	];


    	// split the gps coordinates into longitude, latitude, altitude and accuracy 
    	if($dataMap->location && isset($data['gps']) && $data['gps']) {
            $location = explode(" ", $data['gps']);
            $newModel["longitude"] = isset($location[1]) ? $location[1] : null;
            $newModel["latitude"] = isset($location[0]) ? $location[0] : null;
            $newModel["altitude"] = isset($location[2]) ? $location[2] : null;
            $newModel["accuracy"] = isset($location[3]) ? $location[3] : null;
    	}
	
		foreach($dataMap->variables as $variable) {
			// if the variable is new (i.e. hasn't been manually added to the database)
            if($variable['in_db'] == 0) {
                //don't actually process it (as the SQL Insert will fail)
                //just tell the admin about it!
                NewDataVariableSpotted::dispatch();
                continue;
            }

            $variableName = $variable['name'];
            $value = null;

            switch ($variable['type']) {
                case 'boolean':
                    if (isset($data[$variableName]) && $data[$variableName]) {
                        switch ($data[$variableName]) {
                            case 'yes':
                                $value = 1;
                            break;

                            case 'no':
                                $value = 0;
                            break;

                            case "1":
                            case "0":
                                $value = $data[$variableName];
                            break;
                            // error handling in a painfully basic way - set any unhandled values to null;
                            default:
                                $value = null;
                            break;
                        }
                    }
                break;

                case 'photo':
                    if(isset($data[$variableName]) && $data[$variableName]) {
                        $value = $data[$variableName];
                        ImportAttachmentFromKobo::dispatch($value, $data);
                    }
                break;

                case 'date':
                    if (isset($data[$variableName]) && $data[$variableName]) {
                        $value = Carbon::parse($data[$variableName]);
                        $value = $value->toDateString();
                    }
                break;

                case 'datetime':
                    if (isset($data[$variableName]) && $data[$variableName]) {
                        $value = Carbon::parse($data[$variableName]);
                        $value = $value->toDateTimeString();
                    }
                break;

                case 'select_multiple':
                case 'geopoint':
                    $value = null;
                break;

                default:
                    $value = isset($data[$variableName]) ? $data[$variableName] : null;
                break;
            }

            if(!is_null($value)) {
            	//look the column name that matches to the variable name from the survey 
                $newModel[$variable['column_name']] = $value;
            }
		}

		return $newModel;
	
    }

}
