<template>
    <div>
    <bolivia-map
    :stations="stations"
    :parcelas="parcelas"
    :stationsSelected.sync="stationsSelected" 
    ></bolivia-map>
	<div class="row">
        <div class="col-sm-3 mt-2">
            <div class="card">
                <div class="mt-2">
              
                	<control-panel 
                        :departamentos="departamentos"
                        :municipios="municipios"
                        :comunidads="comunidads" 
                        :comunidadsSelected.sync="comunidadsSelected" 
                        :startDate.sync="startDate" 
                        :endDate.sync="endDate" 
                        :modules.sync="modules" 
                        :aggregations.sync="aggregations"
                        :aggregationSelected.sync="aggregationSelected"
                        :meteoParameters.sync="meteoParameters"
                        :meteoParameterSelected.sync="meteoParameterSelected"
                        :meteoParameterLabel.sync="meteoParameterLabel"
                        :years.sync="years"
                        :yearSelected.sync="yearSelected"
                        :months.sync="months"
                        :monthInitialSelected.sync="monthInitialSelected"
                        :monthFinalSelected.sync="monthFinalSelected"
                        :yearInitialSelected.sync="yearInitialSelected"
                        :yearFinalSelected.sync="yearFinalSelected"
                        :parcelasModules.sync="parcelasModules"
                        :cultivosModules.sync="cultivosModules"
                        :modulesSelected.sync="modulesSelected"
                        :parcelasModulesSelected.sync="parcelasModulesSelected"
                        :cultivosModulesSelected.sync="cultivosModulesSelected" 
                        :weather.sync="weather" 
                        :senamhi.sync="senamhi"
                        :stationDetails.sync="stationDetails"
                        :parcelasData.sync="parcelasData"
                        :suelos.sync="suelos"
                        :manejo_parcelas.sync="manejo_parcelas"
                        :plagas.sync="plagas"
                        :enfermedades.sync="enfermedades"
                        :rendimentos.sync="rendimentos"
                        :fenologia.sync="fenologia"
                        :stations="stations" 
                        :stationsSelected.sync="stationsSelected"
                        :showTable.sync="showTable" >
                    </control-panel>
                </div>
            </div>
        </div>
        <div class="col-sm-9 mb-5">
            <div class="mt-2">
                <b-card no-body v-if="showTable">
                    <b-tabs pills card>
                        <b-tab v-if="weather.length!==0" title="Información meteorológica" active>
                            <b-card-text>
                              <b-row>
                                    <b-col cols="auto" class="mr-auto p-3">
                                        <p><b>Estación :</b> {{ stationDetails.label }}</p>
                                        <p><b>Fecha de inicio :</b> {{ startDate }}</p>
                                        <p><b>Fecha final :</b> {{ endDate }}</p>
                                      
                                    </b-col>
                                    <b-col cols="auto" class="p-3">
                                        <p><b>Latitud :</b> {{stationDetails.latitude }}</p>
                                        <p><b>Longitud :</b> {{stationDetails.longitude }}</p>
                                        <p><b>Altitud :</b> {{stationDetails.altitude }}</p>
                                    </b-col>
                                </b-row>
                                <p v-if="weather.length!==0">Mostrando {{weather.to}} de {{weather.total}} entradas</p> 
                                <tables :data="weather.data" :fields="weatherFields"></tables>
                            </b-card-text>
                        </b-tab>
                        <b-tab v-if="senamhi.length!==0" title="Senamhi data" active>
                            <b-card-text>
                                <b-row>
                                    <b-col cols="auto" class="mr-auto p-3">
                                        <p><b>Estación :</b> {{ stationDetails.label }}</p>
                                        <p v-if="aggregationSelected=='senamhi_daily'"><b>Año :</b> {{ yearSelected }}</p>
                                        <p v-if="aggregationSelected=='senamhi_monthly'"><b>Mes Initial :</b> {{ months[monthInitialSelected-1].label }}</p>
                                        <p v-if="aggregationSelected=='senamhi_monthly'"><b>Mes Final :</b> {{ months[monthFinalSelected-1].label }}</p>
                                        <p v-if="aggregationSelected=='senamhi_monthly'"><b>Año Initial :</b> {{ yearInitialSelected }}</p>
                                        <p v-if="aggregationSelected=='senamhi_monthly'"><b>Año Final :</b> {{ yearFinalSelected }}</p>
                                    </b-col>
                                    <b-col cols="auto" class="p-3">
                                        <p><b>Latitud :</b> {{stationDetails.latitude }}</p>
                                        <p><b>Longitud :</b> {{stationDetails.longitude }}</p>
                                        <p><b>Altitud :</b> {{stationDetails.altitude }}</p>
                                    </b-col>
                                </b-row>
                                <h4 class="text-center"><b>{{ meteoParameterLabel }}</b></h4>
                                <tables v-if="aggregationSelected=='senamhi_daily'" :data="senamhi" :fields="senamhiDailyFields" :sortBy="day"></tables>
                                <tables v-if="aggregationSelected=='senamhi_monthly'" :data="senamhi" :fields="senamhiMonthlyFields" :sortBy="day"></tables>
                            </b-card-text>
                        </b-tab>
                        <b-tab v-if="parcelasData.length!==0" title="Parcelas">
                            <b-card-text>
                                <p v-if="parcelasData.length!==0">Mostrando {{parcelasData.to}} de {{parcelasData.total}} registros</p>
                                <tables :data="parcelasData.data"></tables>
                            </b-card-text>
                        </b-tab>
                        <b-tab v-if="fenologia.length!==0" title="Fenologia">
                            <b-card-text>
                                <p v-if="fenologia.length!==0">Mostrando {{fenologia.to}} de {{fenologia.total}} registros</p>
                                <tables :data="fenologia.data"></tables>
                            </b-card-text>
                        </b-tab>
                        <b-tab v-if="suelos.length!==0" title="Suelos">
                            <b-card-text>
                                <p v-if="suelos.length!==0">Mostrando {{suelos.to}} de {{suelos.total}} registros</p>
                                <tables :data="suelos.data"></tables>
                            </b-card-text>
                        </b-tab>
                        <b-tab v-if="manejo_parcelas.length!==0" title="Manejo de la parcelas">
                            <b-card-text>
                                <p v-if="manejo_parcelas.length!==0">Mostrando {{manejo_parcelas.to}} de {{manejo_parcelas.total}} registros</p>
                                <tables :data="manejo_parcelas.data"></tables>
                            </b-card-text>
                        </b-tab>
                        <b-tab v-if="plagas.length!==0" title="Plagas">
                            <b-card-text>
                                <p v-if="plagas.length!==0">Mostrando {{plagas.to}} de {{plagas.total}} registros</p>
                                <tables :data="plagas.data"></tables>
                            </b-card-text>
                        </b-tab>
                        <b-tab v-if="enfermedades.length!==0" title="Enfermedades">
                            <b-card-text>
                                <p v-if="enfermedades.length!==0">Mostrando {{enfermedades.to}} de {{enfermedades.total}} registros</p>
                                <tables :data="enfermedades.data"></tables>
                            </b-card-text>
                        </b-tab>
                        <b-tab v-if="rendimentos.length!==0" title="Rendimentos">
                            <b-card-text>
                                <p v-if="rendimentos.length!==0">Mostrando {{rendimentos.to}} de {{rendimentos.total}} registros</p>
                                <tables :data="rendimentos.data"></tables>
                            </b-card-text>
                        </b-tab>
                        <button class="site-btn mt-5 mb-2 mx-2" v-on:click="download" style="float: right;">Descargar</button>
                    </b-tabs>
              </b-card>
            </div>
        </div>
    </div>
                      
    </div>
    
</template>
<script type="text/javascript">
export default {
        data(){
            return{
                modules: [{label:'Información meteorológica', value:'daily_data'},{label:'Parcelas', value:'parcelas'}, {label:'Cultivos', value:'cultivos'} ],

                aggregations: [{label: 'Senamhi Diario', value:'senamhi_daily'}, {label: 'Senamhi Mensual', value:'senamhi_monthly'}, {label: 'Diario', value:'daily_data'}, {label: 'Diez días', value:'tendays_data'}, {label: 'Mensual', value:'monthly_data'}, {label: 'Anual', value:'yearly_data'}],

                meteoParameters: [
                    {label: 'Temperatura Máxima Interna (°C)', value:'max_temperatura_interna'}, {label: 'Temperatura Mínima Interna (°C)', value:'min_temperatura_interna'}, {label: 'Temperatura Media Interna (°C)', value:'avg_temperatura_interna'},
                    {label: 'Temperatura Máxima Externa (°C)', value:'max_temperatura_externa'}, {label: 'Temperatura Mínima Externa (°C)', value:'min_temperatura_externa'}, {label: 'Temperatura Media Externa (°C)', value:'avg_temperatura_externa'}, 
                    {label: 'Humedad Máxima Interna %', value:'max_humedad_interna'}, {label: 'Humedad Mínima Interna %', value:'min_humedad_interna'}, {label: 'Humedad Media Interna %', value:'avg_humedad_interna'},
                    {label: 'Humedad Máxima Externa %', value:'max_humedad_externa'}, {label: 'Humedad Mínima Externa %', value:'min_humedad_externa'}, {label: 'Humedad Media Externa %', value:'avg_humedad_externa'}, 
                    {label: 'Presion Relativa Máxima (hPa)', value:'max_presion_relativa'}, {label: 'Presion Relativa Mínima (hPa)', value:'min_presion_relativa'}, {label: 'Presion Relativa Media (hPa)', value:'avg_presion_relativa'}, 
                    {label: 'Presion absoluta Máxima (hPa)', value:'max_presion_absoluta'}, {label: 'Presion absoluta Mínima (hPa)', value:'min_presion_absoluta'}, {label: 'Presion absoluta Media (hPa)', value:'avg_presion_absoluta'},
                    {label: 'Velocidad Viento Máxima (m/s)', value:'max_velocidad_viento'}, {label: 'Velocidad Viento Mínima (m/s)', value:'min_velocidad_viento'}, {label: 'Velocidad Viento Media (m/s)', value:'avg_velocidad_viento'},
                    {label: 'Sensacion Termica Máxima (°C)', value:'max_sensacion_termica'}, {label: 'Sensacion Termica Mínima (°C)', value:'min_sensacion_termica'}, {label: 'Sensacion Termica Media (°C)', value:'avg_sensacion_termica'}, 
                    {label: 'Precipitación Diaria (mm)', value:'lluvia_24_hors_total'} 
                ],

                parcelasModules: [{label:'Suelos', value:'suelos'},{label:'Manejo de la parcela', value:'manejo_parcelas'}, {label:'Plagas', value:'plagas'}, {label:'Enfermedades', value:'enfermedades'}, {label:'Rendimentos', value:'rendimentos'} ],

                cultivosModules: [{label:'Fenologia', value:'fenologia'},{label:'Manejo de la parcela', value:'manejo_parcelas'}, {label:'Plagas', value:'plagas'}, {label:'Enfermedades', value:'enfermedades'}, {label:'Rendimentos', value:'rendimentos'} ],
      
                startDate:null,
                endDate:null,
                weather:[],
                senamhi:[],
                stationDetails:[],
                senamhiDailyFields: [
                    { key: 'day', sortable: true, label: 'DAY' },'JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'
                ],
                senamhiMonthlyFields: [
                    { key: 'year', sortable: true, label: 'YEAR'},'JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'
                ],
                parcelasData:[],
                fenologia:[],
                suelos:[],
                manejo_parcelas:[],
                plagas:[],
                enfermedades:[],
                parcelas:[],
                stations:[],
                departamentos:[],
                municipios:[],
                comunidads:[],
                comunidadsSelected:[],
                modulesSelected:[],
                meteoParameterSelected:[],
                meteoParameterLabel:null,
                yearSelected:[],
                yearInitialSelected:[],
                yearFinalSelected:[],
                monthInitialSelected:[],
                monthFinalSelected:[],
                parcelasModulesSelected:[],
                cultivosModulesSelected:[],
                stationsSelected:[],
                aggregationSelected:[],
                cultivos:[],
                rendimentos:[],
                showTable:false,
                years:[],
                months:[
                    {label:'January', value:'01'}, {label:'February', value:'02'},{label:'March', value:'03'},{label:'April', value:'04'},{label:'May', value:'05'},{label:'June', value:'06'},{label:'July', value:'07'},{label:'August', value:'08'},{label:'September', value:'09'},{label:'October', value:'10'}, {label:'November', value:'11'},{label:'December', value:'12'},  
                ],
                weatherFields: [
                {key: "fecha", stickyColumn: true, label:'Date', thStyle: { width: '200px'}},
                {key: "station", stickyColumn: false, label:'Station', thStyle: { width: '100px'}},
        
                'max_temperatura_interna',
                'min_temperatura_externa',
                'avg_temperatura_interna',
                'max_humedad_interna',
                'min_humedad_interna',
                'avg_humedad_interna',
                'max_temperatura_externa',
                'min_temperatura_externa',
                'avg_temperatura_externa',
                'max_humedad_externa',
                'min_humedad_externa',
                'avg_humedad_externa',
                'max_presion_relativa',
                'min_presion_relativa',
                'avg_presion_relativa',
                'max_presion_absoluta',
                'min_presion_absoluta',
                'avg_presion_absoluta',
                'max_velocidad_viento',
                'min_velocidad_viento',
                'avg_velocidad_viento',
                'max_sensacion_termica',
                'min_sensacion_termica',
                'avg_sensacion_termica',
                'lluvia_24_horas_total',
                {
                    key: "min_fecha",
                    stickyColumn: true,
                    isRowHeader: false,
                },
                {
                    key: "max_fecha",
                    stickyColumn: true,
                    isRowHeader: false,
                },
                ],

            }

        },
        mounted () {

            axios.get('api/departamentos').then((response) => {
                this.departamentos = response.data;
            }),
            axios.get('api/municipios').then((response) => {
                this.municipios = response.data;
            }),
            axios.get('api/comunidads').then((response) => {
                this.comunidads = response.data;
            }),
            axios.get('api/stations').then((response) => {
                this.stations = response.data;
            }),
            axios.get('api/parcelas').then((response) => {
                this.parcelas = response.data;
            }),
            axios.get('api/years').then((response) => {
                this.years = response.data;
            })
        },
        methods: {
            download: function (event) {
                axios({
                    method: 'post',
                    url: "/download",
                    data: {
                        comunidadsSelected: this.comunidadsSelected,
                        modulesSelected: this.modulesSelected,
                        aggregationSelected: this.aggregationSelected,
                        startDate: this.startDate,
                        endDate: this.endDate,
                        stationsSelected: this.stationsSelected,
                        parcelasModulesSelected: this.parcelasModulesSelected,
                        cultivosModulesSelected: this.cultivosModulesSelected,
                    }
                })
                .then((result) => {
                    
                    window.location.href = result.data['path'];
                }, (error) => {
                    console.log(error);
                });          
               
            }
        }
       
    }

 </script>