<?php


namespace App\Http\Controllers\Api;

use App\Models\Parcela;
use App\Http\Controllers\Controller;

class ParcelaController extends Controller
{
    public function index()
    {
        $parcelas = Parcela::with(['submissions'])->get();

        return $parcelas->toJson();
    }
}


