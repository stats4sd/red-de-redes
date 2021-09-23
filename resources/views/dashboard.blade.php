
@extends(backpack_view('blank'))

@section('header')
    <section class="content-header">
      <h1>
        {{ trans('backpack::base.dashboard') }}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }} </a></li>
        <li class="active">{{ trans('backpack::base.dashboard') }}</li>
      </ol>
    </section>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="box-title">
                        <label></label>
                    
                    </div>
                </div>
                <div class="card">
                    <h5 class="card-header">Filtros de gráficos</h5>
                    <div class="card-body">
                        <form method="post">
                    @csrf

                    <div class="form-group">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm">
                                    <label for="station" ><b>Seleccione la estación</b></label>
                                    
                                        <select class="form-control" id="station" style="width:200px;">
                                            @foreach($stations as $station)
                                            <option class = "active" value={{$station->id}}>{{$station->label}}</option>
                                            @endforeach
                                        </select>  
                                  
                                </div>
                                <div class="col-sm">
                                    
                                    <label for="aggregation"><b>Seleccione la agregación</b></label>
                                    
                                    <select class="form-control" id="aggregation" style="width:200px;">
                                        <option value="daily">Diario</option>
                                        <option value="ten_days">Diez Días</option>
                                        <option value="monthly">Mensual</option>
                                        <option value="yearly">Anual</option>
                                    </select>   
                                </div>
                                <div class="col-sm">
                                    <label for="year"><b>Seleccione el año</b></label>
                                    <select class="form-control" id="year" style="width:200px;">
                                        @foreach($years as $year)

                                        <option value={{$year->fecha}}>{{$year->fecha}}</option>
                                        
                                        @endforeach
                                    </select>                   

                                    
                                </div>
                                <div class="col-sm">
                                    <label for="month"><b>Seleccione el mes</b></label>
                                    <select class="form-control" id="month" style="width:200px;">
                                        <option value="01">Enero</option>
                                        <option value="02">Febrero</option>
                                        <option value="03">Marzo</option>
                                        <option value="04">Abril</option>
                                        <option value="05">Mayo</option>
                                        <option value="06">Junio</option>
                                        <option value="07">Julio</option>
                                        <option value="08">Agosto</option>
                                        <option value="09">Septiembre</option>
                                        <option value="10">Octubre</option>
                                        <option value="11">Noviembre</option>
                                        <option value="12">Diciembre</option>
                                    </select>                   

                                    
                                </div>
                                <div class="col-sm">
                                     <button type="submit" id="filter" class="btn btn-dark mt-4" checked>GENERAR GRÁFICOS</button>
                                </div>
                            </div>
                        </div>
                    </div>
                        </form>
                    </div>
                </div>
                <div class="box-body">
                    <div class="container-fluid">
                        <div class="row">

                            <div class="col-sm-6">    
                                <h4><b>Temperatura Interna</b></h4>
                                <canvas id="tempInt" height="280" width="600"></canvas>
                            </div>
                            
                            <div class="col-sm-6">
                                <h4><b>Temperatura Externa</b></h4>
                                <canvas id="tempOut" height="280" width="600"></canvas>
                            </div>
                         </div>

                        
                        <div class="row">  
                            <div class="col-sm-6">
                            <h4><b>Humedad Interna</b></h4>    
                                <canvas id="HumInt" height="280" width="600"></canvas>
                            </div>
                            
                            <div class="col-sm-6">
                                <h4><b>Humedad Externa</b></h4>
                                <canvas id="HumOut" height="280" width="600"></canvas>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">    
                                <h4><b>Presión Relativa</b></h4>
                                <canvas id="PresRel" height="280" width="600"></canvas>
                            </div>
                            
                            <div class="col-sm-6">
                                <h4><b>Presión Absoluta</b></h4>
                                <canvas id="PresAbs" height="280" width="600"></canvas>
                            </div>
                        </div>

                        
                        <div class="row">  
                            <div class="col-sm-6">    
                                <h4><b>Sensación térmica</b></h4>
                                <canvas id="SenTerm" height="280" width="600"></canvas>
                            </div>
                            
                            <div class="col-sm-6">
                                <h4><b>Velocidad viento</b></h4>
                                <canvas id="VelocViento" height="280" width="600"></canvas>
                            </div>
                        </div>

                        
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection


@section('after_scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery("#aggregation").change(function(event){

        var agg = jQuery('#aggregation').val();
    
        if(agg=='ten_days' || agg=='monthly'){
                $("#month").attr('disabled','disabled');
               
        }else if(agg=='yearly'){
            $("#month").attr('disabled','disabled');
            $("#year").attr('disabled','disabled');
            
        }else {
             $("#month").removeAttr('disabled');
            $("#year").removeAttr('disabled');
        }

    });
});
jQuery(document).ready(function(){

    jQuery("#filter").click(function(event){

        event.preventDefault();
        var target = event.target;
        target.disabled = true;
        target.innerHTML = `<div class="spinner-border spinner-border-sm"></div> Preparando...`;
            var station_id = jQuery('#station').val();
            var agg = jQuery('#aggregation').val();
            var year = jQuery('#year').val();
            var month = jQuery('#month').val();

            $.ajax({
            url : '/admin/dashboard/charts',
            type : 'POST',
            data : {
                station_id : station_id,
                agg: agg,
                year: year,
                month: month,
                year: year
            },
            
        }).done(function(response) {
            target.disabled = false;
            target.innerHTML = " GENERAR GRÁFICOS ";
            var data = response.data;
            var fecha = data.map(x => {
                return x.fecha;
            });

            var max = data.map(x => {
                return x.max_temperatura_interna;
            });
            
            var min = data.map(x => {
                return x.min_temperatura_interna;
            });
            var max_temp_ext = data.map(x => {
                return x.max_temperatura_externa;
            });
            
            var min_temp_ext= data.map(x => {
                return x.min_temperatura_externa;
            });
            var max_hum_int = data.map(x => {
                return x.max_humedad_interna;
            });
            
            var min_hum_int= data.map(x => {
                return x.min_humedad_interna;
            });

            var max_hum_ext = data.map(x => {
                return x.max_humedad_externa;
            });
            
            var min_hum_ext= data.map(x => {
                return x.min_humedad_externa;
            });

            var max_pres_rel = data.map(x => {
                return x.max_presion_relativa;
            });
            
            var min_pres_rel= data.map(x => {
                return x.min_presion_relativa;
            });

            var max_pres_abs = data.map(x => {
                return x.max_presion_absoluta;
            });
            
            var min_pres_abs= data.map(x => {
                return x.min_presion_absoluta;
            });

            var max_sen_term = data.map(x => {
                return x.max_sensacion_termica;
            });
            
            var min_sen_term= data.map(x => {
                return x.min_sensacion_termica;
            });
          
            var max_veloc_viento = data.map(x => {
                return x.max_velocidad_viento;
            });
            
            var min_veloc_viento= data.map(x => {
                return x.min_velocidad_viento;
            });



            //temperature inside 
            var tempInt = document.getElementById('tempInt').getContext('2d');
            var mixedChart = new Chart(tempInt, {
                type: 'line',

                data: {
                    datasets: [{
                        label: 'Max Temp Int',
                        data: max,
                        borderColor: 'rgb(255, 99, 132)',
                        options: {
                                    title: {
                                        display: true,
                                        text: 'Custom Chart Title'
                                    }
                                }
                        
                    }, {
                        label: 'Min Temp Int',
                        data: min,
                        borderColor: 'rgb(54, 162, 235)',

                        // Changes this dataset to become a line
                        type: 'line'
                    }],
                    labels: fecha
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
                });
            //temperature outside
            var tempOut = document.getElementById('tempOut').getContext('2d');
            var mixedChart = new Chart(tempOut, {
                type: 'line',
                data: {
                    datasets: [{
                        label: 'Max Temp Ext',
                        data: max_temp_ext,
                        borderColor: 'rgb(255, 99, 132)',
                    }, {
                        label: 'Min Temp Ext',
                        data: min_temp_ext,
                        borderColor: 'rgb(54, 162, 235)',

                        // Changes this dataset to become a line
                        type: 'line'
                    }],
                    labels: fecha
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
                });

            //humedad interna
            var HumInt = document.getElementById('HumInt').getContext('2d');
            var mixedChart = new Chart(HumInt, {
                type: 'line',
                data: {
                    datasets: [{
                        label: 'Max Humedad Int',
                        data: max_hum_int,
                        borderColor: 'rgb(255, 99, 132)'
                    }, {
                        label: 'Min Humedad Int',
                        data: min_hum_int,
                        borderColor: 'rgb(54, 162, 235)',

                        // Changes this dataset to become a line
                        type: 'line'
                    }],
                    labels: fecha
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
                });


            //humedad externa
            var HumOut = document.getElementById('HumOut').getContext('2d');
            var mixedChart = new Chart(HumOut, {
                type: 'line',
                data: {
                    datasets: [{
                        label: 'Max Humedad Ext',
                        data: max_hum_ext,
                        borderColor: 'rgb(255, 99, 132)'
                    }, {
                        label: 'Min Humedad Ext',
                        data: min_hum_ext,
                        borderColor: 'rgb(54, 162, 235)',

                        // Changes this dataset to become a line
                        type: 'line'
                    }],
                    labels: fecha
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
                });

            //presion relativa

            var PresRel = document.getElementById('PresRel').getContext('2d');
            var mixedChart = new Chart(PresRel, {
                type: 'line',
                data: {
                    datasets: [{
                        label: 'Max Presión Relativa',
                        data: max_pres_rel,
                        borderColor: 'rgb(255, 99, 132)'
                    }, {
                        label: 'Min Presión Relativa',
                        data: min_pres_rel,
                        borderColor: 'rgb(54, 162, 235)',

                        // Changes this dataset to become a line
                        type: 'line'
                    }],
                    labels: fecha
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
                });


            //presion absoluta

            var PresAbs = document.getElementById('PresAbs').getContext('2d');
            var mixedChart = new Chart(PresAbs, {
                type: 'line',
                data: {
                    datasets: [{
                        label: 'Max Presión Absoluta',
                        data: max_pres_abs,
                        borderColor: 'rgb(255, 99, 132)'
                    }, {
                        label: 'Min Presión Absoluta',
                        data: min_pres_abs,
                        borderColor: 'rgb(54, 162, 235)',

                        // Changes this dataset to become a line
                        type: 'line'
                    }],
                    labels: fecha
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
                });
            //Sensacion Termica
            var SenTerm = document.getElementById('SenTerm').getContext('2d');
            var mixedChart = new Chart(SenTerm, {
                type: 'line',
                data: {
                    datasets: [{
                        label: 'Max Sensación Térmica',
                        data: max_sen_term,
                        borderColor: 'rgb(255, 99, 132)'
                    }, {
                        label: 'Min Sensación Térmica',
                        data: min_sen_term,
                        borderColor: 'rgb(54, 162, 235)',

                        // Changes this dataset to become a line
                        type: 'line'
                    }],
                    labels: fecha
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
                });
            //Velocidad Viento 

            var VelocViento = document.getElementById('VelocViento').getContext('2d');
            var mixedChart = new Chart(VelocViento, {
                type: 'line',
                data: {
                    datasets: [{
                        label: 'Max Velocidad Viento',
                        data: max_veloc_viento,
                        borderColor: 'rgb(255, 99, 132)'
                    }, {
                        label: 'Min Velocidad Viento',
                        data: min_veloc_viento,
                        borderColor: 'rgb(54, 162, 235)',

                        // Changes this dataset to become a line
                        type: 'line'
                    }],
                    labels: fecha
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
                });
            
            
            });



        });


    

});

</script>
@endsection
