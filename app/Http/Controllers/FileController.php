<?php
namespace App\Http\Controllers;

use Alert;
use App\File;
use DB;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use \GuzzleHttp\Client;


class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Retrieve file from POST request
        //
        $station = $_POST['weatherstation'];
        

            if($request->hasFile('data-file')){

                // handle file and store it for prosperity
                $file = $request->file('data-file');
             

                $name = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('rawfiles',$name);

                $newFile = new File;
                $newFile->path = $path;
                $newFile->name = $name;
                $newFile->station_id = $station;

                $newFile->save();
                \Alert::success('<h4>El archivo ha sido subido exitosamente</h4>')->flash();
                return Redirect::back();

            }
            \Alert::error("<h4>El archivo no fue seleccionado</h4>")->flash();

            return Redirect::back();;
        


        // Send file onto cloud function
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
