<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Municipio;

class MunicipioController extends Controller
{
    public function index()
    {
        $municipios = Municipio::select('id', 'name', 'departamento_id')->get();

        return $municipios->toJson();
    }
}
