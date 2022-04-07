
@extends('layouts.app')
@section('content')



    <div class="mx-5">
        <h3 class="section-title font-weight-bold text-center mb-3">Weatherstations</h3>
        <p class="section-intro mx-auto text-center mb-5 text-secondary">Los datos han sido ingresados exitosamente..</p>

        <div class="row">
            <div class="col-sm-2 offset-3">
                <a type="button" class="btn btn-secondary" href="{{ url('home') }}"><b>Go Back To Home Page</b></a>
            </div>
            <div class="col-sm-2 offset-2">
                <a type="button" class="btn btn-secondary" href="{{ url('data-upload') }}"><b>Upload Another Data File</b></a>
            </div>
        </div>

    </div>



@endsection
