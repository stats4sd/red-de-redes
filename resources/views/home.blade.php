@extends('layouts.app')


@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">



            <link href ="{{asset('css/style.css')}}" rel="stylesheet"/>

            <div class ="card mt-4">
                <div class="card-header">Data</div>



                <div id="temps_div"></div>
                <?= $lava->render('LineChart', 'Temperature', 'temps_div') ?>

               <!-- <div id="chartscontent" ></div> -->



            <div class="tab">
                <button class="tablinks" onclick="openTable(event, 'Daily')" id="defaultOpen">Daily</button>
                <button class="tablinks" onclick="openTable(event, 'Tendays')">Ten Days</button>
                <button class="tablinks" onclick="openTable(event, 'Monthly')">Monthly</button>
                <button class="tablinks" onclick="openTable(event, 'Yearly')">Yearly</button>
            </div>


            <div id="Daily" class="tabcontent">
                <h3>Daily Data</h3>
                    <p>Daily data summeries from weather station</p>
                        <div class="card-body">

                        <table class="table table-striped" id="daily-table"></table>

                    </div>
            </div>

            <div id="Tendays" class="tabcontent">
                <h3>Ten days</h3>
                    <p>Ten days summeries from weather station</p>
                        <div class="card-body">
                            <table class= "table table-striped" id="ten-table"></table>
                        </div>
            </div>


            <div id="Monthly" class="tabcontent">
                <h3>Monthly</h3>
                    <p>Monthly data summeries from weather station</p>
                        <div class="card-body">
                            <table class= "table table-striped" id="monthly-table"></table>
                        </div>
            </div>

            <div id="Yearly" class="tabcontent">
                <h3>Yearly Data</h3>
                    <p>Yearly data summeries from weather station</p>
                        <div class="card-body">
                            <table class= "table table-striped" id="yearly-table"></table>
                        </div>
            </div>
        </div>




    </div>
         <div class="col-md-4">
            <div class="card mt-4">
                <div class="card-header">Panel de Control</div>
                    <div class="card-body">


                        <div class="container">
                          <h2>Weather Station Filter</h2>
                          <div class="form-group">
                                <label for="stations-content">Select Weather Station</label>

                                <select id="filter_station" class="form-control">

                                    <option id='1' value='1'>Chojñapata-Davis</option>
                                    <option id='2' value='2'>Chinchaya-Davis</option>
                                    <option id='3' value='3'>Chinchaya-Chinas</option>
                                    <option id='4' value='4'>Calahuancane-Davis</option>
                                    <option id='5' value='5'>Cutusuma-Davis</option>
                                    <option id='6' value='6'>Iñacamaya-Davis</option>
                                    <option id='7' value='7'>Incamya-Chinas</option>

                                </select>
                            </div>
                             <hr>
                                <button onclick="filterByStation()">Submit Filter</button>

                            <hr>


                         <!-- <h2>Graphics</h2>

                       <!--  <form action="{{ route('getGraphic') }} " method="POST"> -->
                       <!--  @csrf


                          <div class="form-group">
                                <label for="stations-content">Select Value to Display</label>
                                <select id="graphics_id" class="form-control">

                                    <option id='1' value='1'>Temperatura Interna</option>
                                    <option id='2' value='2'>Humedad Interna</option>
                                    <option id='3' value='3'>Temperatura Externa</option>
                                    <option id='4' value='4'>Humedad Externa</option>
                                    <option id='5' value='5'>Presion Relativa</option>
                                    <option id='6' value='6'>Presion Absoluta</option>
                                    <option id='7' value='7'>Velocidad Viento</option>
                                    <option id='8' value='8'>Sensacion Termica</option>
                                    <option id='9' value='9'>Lluvia 24 horas</option>

                                </select>
                            </div>
                             <hr>
                                <button onclick="openGraphics()">Submit Graphic</button> -->
                      <!--   </form> -->




                    </div>
                </div>
            </div>
            </div>

        </div>
</div>

@endsection

@section('javascript')
<script src="js/buttonTable.js"></script>
<script src="js/openGraphics.js"></script>
<!-- Table Daily Data-->
<script>


$(document).ready(function() {

    window.dailyTable = $('#daily-table').DataTable({
        dom: 'Bfritip',
        buttons:[
            {
            extend: 'collection',
            text: 'Show columns',
            buttons: [ 'columnsVisibility' ],
            visibility: true
            },
            {
                extend: 'csv',
                filename: 'daily',
            }
        ],
        processing: true,
        serverSide: false,
        "scrollY":  "500px",
        "scrollX":  true,
        "scrollCollapse": true,
        "paging":         true,
        ajax: '{!! route('getDaily') !!}',
        columns: [
            { data: 'station_id', name: 'station_id', title:'Id Station '},
            { data: 'fecha', name: 'fecha' , title: 'Fecha'},
            { data: 'max_temperatura_interna', name: 'max_temperatura_interna', title: 'Max Temperatura Interna'},
            { data: 'min_temperatura_interna', name: 'min_temperatura_interna', title: 'Min Temperatura Interna'},
            { data: 'max_temperatura_externa', name: 'max_temperatura_externa', title: 'Max Temperatura Externa'},
            { data: 'min_temperatura_externa', name: 'min_temperatura_externa', title: 'Min Temperatura Externa'},
            { data: 'max_humedad_interna', name: 'max_humedad_interna', title: 'Max Humedad Interna'},
            { data: 'min_humedad_interna', name: 'min_humedad_interna', title: 'Min Humedad Interna'},
            { data: 'max_humedad_externa', name: 'max_humedad_externa', title: 'Max Humedad Externa'},
            { data: 'min_humedad_externa', name: 'min_humedad_externa', title: 'Min Humedad Externa'},
            { data: 'max_presion_relativa', name: 'max_presion_relativa', title: 'Max Presión Relativa'},
            { data: 'min_presion_relativa', name: 'min_presion_relativa', title: 'Min Presión Relativa'},
            { data: 'max_presion_absoluta', name: 'max_presion_absoluta', title: 'Max Presión Absoluta'},
            { data: 'min_presion_absoluta', name: 'min_presion_absoluta', title: 'Min Presión Absoluta'},
            { data: 'max_velocidad_viento', name: 'max_velocidad_viento', title: 'Max Velocidad Viento'},
            { data: 'min_velocidad_viento', name: 'min_velocidad_viento', title: 'Min Velocidad Viento'},
            { data: 'max_sensacion_termica', name: 'max_sensacion_termica', title: 'Max Sensación Termica'},
            { data: 'min_sensacion_termica', name: 'min_sensacion_termica', title: 'Min Sensación Termica'},
            { data: 'lluvia_24_horas_total', name: 'lluvia_24_horas_total', title: 'Max Lluvia 24 horas'},


        ]
    });
});

</script>

<!-- Table Ten Data-->
<script>


$(document).ready(function() {

    window.tenTable = $('#ten-table').DataTable({
        dom: 'Bfritip',
        buttons:[
            {
            extend: 'collection',
            text: 'Show columns',
            buttons: [ 'columnsVisibility' ],
            visibility: true
            },
            {
                extend: 'csv',
                filename: 'ten_days',
            }
        ],
        processing: true,
        serverSide: false,
        "scrollY":  "500px",
        "scrollX":  true,
        "scrollCollapse": true,
        "paging":         true,
        ajax: '{!! route('getTenDays') !!}',
        columns: [

            { data: 'station_id', name: 'station_id', title:'Id Station '},
            { data: 'max_fecha', name: 'max_fecha', title: 'Max Fecha', title: 'Primer día'},
            { data: 'min_fecha', name: 'min_fecha' , title: 'Min Fecha', title: 'Último día'},
            { data: 'max_temperatura_interna', name: 'max_temperatura_interna', title: 'Max Temperatura Interna'},
            { data: 'min_temperatura_interna', name: 'min_temperatura_interna', title: 'Min Temperatura Interna'},
            { data: 'max_temperatura_externa', name: 'max_temperatura_externa', title: 'Max Temperatura Externa'},
            { data: 'min_temperatura_externa', name: 'min_temperatura_externa', title: 'Min Temperatura Externa'},
            { data: 'max_humedad_interna', name: 'max_humedad_interna', title: 'Max Humedad Interna'},
            { data: 'min_humedad_interna', name: 'min_humedad_interna', title: 'Min Humedad Interna'},
            { data: 'max_humedad_externa', name: 'max_humedad_externa', title: 'Max Humedad Externa'},
            { data: 'min_humedad_externa', name: 'min_humedad_externa', title: 'Min Humedad Externa'},
            { data: 'max_presion_relativa', name: 'max_presion_relativa', title: 'Max Presión Relativa'},
            { data: 'min_presion_relativa', name: 'min_presion_relativa', title: 'Min Presión Relativa'},
            { data: 'max_presion_absoluta', name: 'max_presion_absoluta', title: 'Max Presión Absoluta'},
            { data: 'min_presion_absoluta', name: 'min_presion_absoluta', title: 'Min Presión Absoluta'},
            { data: 'max_velocidad_viento', name: 'max_velocidad_viento', title: 'Max Velocidad Viento'},
            { data: 'min_velocidad_viento', name: 'min_velocidad_viento', title: 'Min Velocidad Viento'},
            { data: 'max_sensacion_termica', name: 'max_sensacion_termica', title: 'Max Sensación Termica'},
            { data: 'min_sensacion_termica', name: 'min_sensacion_termica', title: 'Min Sensación Termica'},
            { data: 'lluvia_24_horas_total', name: 'lluvia_24_horas_total', title: 'Max Lluvia 24 horas'},


        ]
    });
});

</script>

<!-- Table Monthly Data-->
<script>


$(document).ready(function() {

    window.monthlyTable = $('#monthly-table').DataTable({
        dom: 'Bfritip',
        buttons:[
            {
            extend: 'collection',
            text: 'Show columns',
            buttons: [ 'columnsVisibility' ],
            visibility: true
            },
            {
                extend: 'csv',
                filename: 'monthly',
            }
        ],
        processing: true,
        serverSide: false,
        "scrollY":  "500px",
        "scrollX":  true,
        "scrollCollapse": true,
        "paging":         true,
        ajax: '{!! route('getMonthly') !!}',
        columns: [

            { data: 'station_id', name: 'station_id', title:'Id Station '},
            { data: 'fecha', name: 'fecha' , title: 'Fecha'},
            { data: 'max_temperatura_interna', name: 'max_temperatura_interna', title: 'Max Temperatura Interna'},
            { data: 'min_temperatura_interna', name: 'min_temperatura_interna', title: 'Min Temperatura Interna'},
            { data: 'max_temperatura_externa', name: 'max_temperatura_externa', title: 'Max Temperatura Externa'},
            { data: 'min_temperatura_externa', name: 'min_temperatura_externa', title: 'Min Temperatura Externa'},
            { data: 'max_humedad_interna', name: 'max_humedad_interna', title: 'Max Humedad Interna'},
            { data: 'min_humedad_interna', name: 'min_humedad_interna', title: 'Min Humedad Interna'},
            { data: 'max_humedad_externa', name: 'max_humedad_externa', title: 'Max Humedad Externa'},
            { data: 'min_humedad_externa', name: 'min_humedad_externa', title: 'Min Humedad Externa'},
            { data: 'max_presion_relativa', name: 'max_presion_relativa', title: 'Max Presión Relativa'},
            { data: 'min_presion_relativa', name: 'min_presion_relativa', title: 'Min Presión Relativa'},
            { data: 'max_presion_absoluta', name: 'max_presion_absoluta', title: 'Max Presión Absoluta'},
            { data: 'min_presion_absoluta', name: 'min_presion_absoluta', title: 'Min Presión Absoluta'},
            { data: 'max_velocidad_viento', name: 'max_velocidad_viento', title: 'Max Velocidad Viento'},
            { data: 'min_velocidad_viento', name: 'min_velocidad_viento', title: 'Min Velocidad Viento'},
            { data: 'max_sensacion_termica', name: 'max_sensacion_termica', title: 'Max Sensación Termica'},
            { data: 'min_sensacion_termica', name: 'min_sensacion_termica', title: 'Min Sensación Termica'},
            { data: 'lluvia_24_horas_total', name: 'lluvia_24_horas_total', title: 'Max Lluvia 24 horas'},

        ]
    });
});

</script>
<!-- Table Yearly Data-->
<script>


$(document).ready(function() {

    window.yearlyTable = $('#yearly-table').DataTable({
        dom: 'Bfritip',
        buttons:[
            {
            extend: 'collection',
            text: 'Show columns',
            buttons: [ 'columnsVisibility' ],
            visibility: true
            },
            {
                extend: 'csv',
                filename: 'yearly',
            }
        ],
        processing: true,
        serverSide: false,
        "scrollY":  "500px",
        "scrollX":  true,
        "scrollCollapse": true,
        "paging":         true,
        ajax: '{!! route('getMonthly') !!}',
        columns: [

            { data: 'station_id', name: 'station_id', title:'Id Station '},
            { data: 'fecha', name: 'fecha' , title: 'Fecha'},
            { data: 'max_temperatura_interna', name: 'max_temperatura_interna', title: 'Max Temperatura Interna'},
            { data: 'min_temperatura_interna', name: 'min_temperatura_interna', title: 'Min Temperatura Interna'},
            { data: 'max_temperatura_externa', name: 'max_temperatura_externa', title: 'Max Temperatura Externa'},
            { data: 'min_temperatura_externa', name: 'min_temperatura_externa', title: 'Min Temperatura Externa'},
            { data: 'max_humedad_interna', name: 'max_humedad_interna', title: 'Max Humedad Interna'},
            { data: 'min_humedad_interna', name: 'min_humedad_interna', title: 'Min Humedad Interna'},
            { data: 'max_humedad_externa', name: 'max_humedad_externa', title: 'Max Humedad Externa'},
            { data: 'min_humedad_externa', name: 'min_humedad_externa', title: 'Min Humedad Externa'},
            { data: 'max_presion_relativa', name: 'max_presion_relativa', title: 'Max Presión Relativa'},
            { data: 'min_presion_relativa', name: 'min_presion_relativa', title: 'Min Presión Relativa'},
            { data: 'max_presion_absoluta', name: 'max_presion_absoluta', title: 'Max Presión Absoluta'},
            { data: 'min_presion_absoluta', name: 'min_presion_absoluta', title: 'Min Presión Absoluta'},
            { data: 'max_velocidad_viento', name: 'max_velocidad_viento', title: 'Max Velocidad Viento'},
            { data: 'min_velocidad_viento', name: 'min_velocidad_viento', title: 'Min Velocidad Viento'},
            { data: 'max_sensacion_termica', name: 'max_sensacion_termica', title: 'Max Sensación Termica'},
            { data: 'min_sensacion_termica', name: 'min_sensacion_termica', title: 'Min Sensación Termica'},
            { data: 'lluvia_24_horas_total', name: 'lluvia_24_horas_total', title: 'Max Lluvia 24 horas'},

        ]
    });
});

</script>
<!-- Filter table by station
 -->
 <script src="js/filterByStation.js"></script>
@endsection
