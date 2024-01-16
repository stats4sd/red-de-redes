<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Http\Requests\QrCodeRequest;

class QrController extends Controller
{
    public function newCodes(QrCodeRequest $request)
    {
        $validatedData = $request->validated();

        $num = $validatedData['start_number'] ? $validatedData['code_number'] + $validatedData['start_number'] - 1 : $validatedData['code_number'];
        $label_number = $validatedData['label_number'];
        $start_number = $validatedData['start_number'] ?: 1;

        if($label_number==50){
            
            $colNumbers=5;

        }
        elseif($label_number==21){

            $colNumbers=3;

        }else{

            $colNumbers=2;

        }

        //create new QR code entries
        $qrcodes = [];

        for ($i = $start_number; $i <= $num; $i++) {
            $qrcode = QrCode::create([
                'prefix'  => $validatedData['prefix'],
                'code' => $validatedData['prefix'],
                'suffix' => $validatedData['suffix'],
                ]);

            $qrcode->code = $validatedData['prefix'] . '-' . sprintf('%03d', $i) . $validatedData['suffix'];
            $qrcode->save();

            $qrcodes[] = $qrcode;
        }

        return view('qr-print', ['qrcodes'=>$qrcodes, 'labelNum'=>$num, 'labelSize'=>$label_number, 'colNumbers'=>$colNumbers]);
    }

    public function printView()
    {
        if (session('qrcodes')) {
            $qrcodes = session()->get('qrcodes');
        } else {
            $qrcodes = QrCode::all();
        }

        return view('qr-print', compact('qrcodes'));
    }
}
