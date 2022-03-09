
@extends('layouts.app')
@section('content')

 

    <div class="mx-5">
      <h3 class="section-title font-weight-bold text-center mb-3">Weatherstations</h3>
        <p class="section-intro mx-auto text-center mb-5 text-secondary">Descripción de los datos.</p>
        
     
     <div id="app">
     	 <uploader-weather-data></uploader-weather-data>
     </div>
    </div>
     

@endsection



@section('javascript')

    <!-- to include Axios CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>

    <script src="js/data_upload.js"></script>
    
@endsection
