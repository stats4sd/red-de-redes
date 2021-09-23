<template>
    <div class="container">
         <h4  class="mt-3"><b>Módulos</b></h4>
            
                <b-form-checkbox-group
                    v-model="modulesSelected"
                    :options="modules"
                    value-field="value"
                    text-field="label"
                   
                    :aria-describedby="selectModuleLabel"
                    stacked
                ></b-form-checkbox-group>
           
        <div class="justify-content-center" v-if="modulesSelected.includes('daily_data')">

            <h4  class="mt-3"><b>Estación</b></h4>
            <v-select :options="stations" :reduce="label => label.id" v-model="stationsSelected" multiple></v-select>
            
            <h4  class="mt-3"><b>Agregación</b></h4>
            <v-select :options="aggregations" :reduce="label => label.value" v-model="aggregationSelected"></v-select>

            <h4  class="mt-3" v-if="aggregationSelected.includes('senamhi_daily') || aggregationSelected.includes('senamhi_monthly')"><b>Parámetros meteorológicos</b></h4>
            <v-select v-if="aggregationSelected.includes('senamhi_daily') || aggregationSelected.includes('senamhi_monthly')" :options="meteoParameters" :reduce="label => label.value" v-model="meteoParameterSelected"></v-select>
            
            <h4  class="mt-3" v-if="aggregationSelected.includes('senamhi_daily')"><b>Año</b></h4>
            <v-select v-if="aggregationSelected.includes('senamhi_daily')" label="fecha" :options="yearsFilter" :reduce="fecha => fecha.fecha" v-model="yearSelected"></v-select>
            <div class="row">
                <div class="col">
                    <h4  class="mt-3" v-if="aggregationSelected.includes('senamhi_monthly')"><b>Mes Initial</b></h4>
                    <v-select v-if="aggregationSelected.includes('senamhi_monthly')" :options="months" :reduce="label => label.value" v-model="monthInitialSelected"></v-select>

                </div>
                <div class="col">
                    <h4  class="mt-3" v-if="aggregationSelected.includes('senamhi_monthly')"><b>Mes Final</b></h4>
                    <v-select v-if="aggregationSelected.includes('senamhi_monthly')" :options="months" :reduce="label => label.value" v-model="monthFinalSelected"></v-select>
        
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <h4  class="mt-3" v-if="aggregationSelected.includes('senamhi_monthly')"><b>Año Inicial</b></h4>
                    <v-select v-if="aggregationSelected.includes('senamhi_monthly')" label="fecha" :options="yearsFilter" :reduce="fecha => fecha.fecha" v-model="yearInitialSelected"></v-select>

                </div>
                <div class="col">
                
                    <h4  class="mt-3" v-if="aggregationSelected.includes('senamhi_monthly')"><b>Año Final</b></h4>
                    <v-select v-if="aggregationSelected.includes('senamhi_monthly')" label="fecha" :options="yearsFilter" :reduce="fecha => fecha.fecha" v-model="yearFinalSelected"></v-select>
                

                </div>
            </div> 
            
           
        </div>
       
            <b-form-group
            v-if="modulesSelected.includes('parcelas')"
            label="¿Qué le interesa de las parcelas?"
            class="mt-3"
            v-slot="{ selectPlotLabel }"
            >
                <b-form-checkbox-group
                    v-if="modulesSelected.includes('parcelas')"
                    v-model="parcelasModulesSelected"
                    :options="parcelasModules"
                    value-field="value"
                    text-field="label"
                    :aria-describedby="selectPlotLabel"
                    stacked
                ></b-form-checkbox-group>
            </b-form-group>
   
            <b-form-group
            v-if="modulesSelected.includes('cultivos')"
            label="¿Qué le interesa de los cultivos?"
            class="mt-3"
            v-slot="{ selectCultivoLabel }"
            >
                <b-form-checkbox-group
                    v-if="modulesSelected.includes('cultivos')"
                    v-model="cultivosModulesSelected"
                    :options="cultivosModules"
                    value-field="value"
                    text-field="label"
                    :aria-describedby="selectCultivoLabel"
                    stacked
                ></b-form-checkbox-group>
            </b-form-group>
         <div>
            <div v-if="modulesSelected.includes('daily_data')">
                <div class="row">
                    <div class="col">
                        <h4 class="mt-3"><b>Fecha de inicio</b></h4>
                        <input class="form-control" type="date" v-model="startDate">
                    </div>
                    <div class="col">
                        <h4 class="mt-3"><b>Fecha final</b></h4>
                        <input class="form-control" type="date" v-model="endDate">
                    </div>

                </div>
                <div v-if="modulesSelected.includes('parcelas') || modulesSelected.includes('cultivos')">
                    <h4 class="mt-3"><b>Departamento</b></h4>
                        <v-select label="name" :options="departamentos" :reduce="name => name.id" v-model="departamentosSelected" multiple style="width:100%"></v-select>

                    <h4 class="mt-3"><b>Municipio</b></h4>
                        <v-select label="name" :options="municipiosFilter" :reduce="name => name.id" v-model="municipiosSelected" multiple style="width:100%"></v-select>

                    <h4 class="mt-3"><b>Comunidad</b></h4>
                        <v-select label="name" :options="comunidadsFilter" :reduce="name => name.id" v-model="comunidadsSelected" multiple style="width:100%"></v-select>
                </div>

            </div>
            <button class="site-btn mt-3 mb-3 float-right" v-on:click="submit">Visualizar</button>
        
        </div>
    </div>
</template>

<script>

    export default {
        data: function(){
            return{

                startDate:null,
                endDate:null,
                departamentosSelected:[],
                municipiosSelected:[],
                comunidadsSelected:[],
                modulesSelected:[],
                parcelasModulesSelected:[],
                cultivosModulesSelected:[],
                weather:[],
                senamhi:[],
                stationDetails:[],
                stationsSelected:[],
                aggregationSelected:[],
                meteoParameterSelected:[],
                meteoParameterLabel:null,
                yearSelected:[],
                monthInitialSelected:[],
                monthFinalSelected:[],
                yearInitialSelected:[],
                yearFinalSelected:[],
                municipiosFilter:[],
                comunidadsFilter:[],
                yearsFilter:[],
                parcelas:[],
                suelos: [],
                manejo_parcelas: [],
                plagas: [],
                enfermedades: [],
                rendimentos: [],
                fenologia: [],
                parcelasData:[],
           
            }

        },
        props: ['departamentos', 'municipios', 'comunidads', 'modules', 'aggregations', 'meteoParameters', 'years', 'months', 'parcelasModules', 'cultivosModules', 'stations', 'showTable'],
        watch: {
            modulesSelected() {
                this.$emit('update:modulesSelected', this.modulesSelected)
            },
            parcelasModulesSelected() {
                this.$emit('update:parcelasModulesSelected', this.parcelasModulesSelected)
            },
            cultivosModulesSelected() {
                this.$emit('update:cultivosModulesSelected', this.cultivosModulesSelected)
            },
            comunidadsSelected() {
                this.$emit('update:comunidadsSelected', this.comunidadsSelected)
            },
            startDate() {
                this.$emit('update:startDate', this.startDate)
            },
            endDate() {
                this.$emit('update:endDate', this.endDate)
            },
            weather() {
                this.$emit('update:weather', this.weather)
            },
            senamhi() {
                this.$emit('update:senamhi', this.senamhi)
            },
            stationDetails(){
                 this.$emit('update:stationDetails', this.stationDetails)
            },
            stationsSelected() {
                this.$emit('update:stationsSelected', this.stationsSelected)
                this.yearsFilter = this.years.filter(year => this.stationsSelected.includes(year.id_station));
            },
            aggregationSelected() {
                this.$emit('update:aggregationSelected', this.aggregationSelected)
            },
            meteoParameterSelected() {
                this.$emit('update:meteoParameterSelected', this.meteoParameterSelected)
                this.meteoParameterLabel = this.meteoParameters.find(meteoParameters => meteoParameters.value === this.meteoParameterSelected).label;
            },
            meteoParameterLabel() {
                this.$emit('update:meteoParameterLabel', this.meteoParameterLabel)
            },
            yearSelected() {
                this.$emit('update:yearSelected', this.yearSelected)
            },
            monthInitialSelected() {
                this.$emit('update:monthInitialSelected', this.monthInitialSelected)
            },
            monthFinalSelected() {
                this.$emit('update:monthFinalSelected', this.monthFinalSelected)
            },
            yearInitialSelected() {
                this.$emit('update:yearInitialSelected', this.yearInitialSelected)
            },
            yearFinalSelected() {
                this.$emit('update:yearFinalSelected', this.yearFinalSelected)
            },
            departamentosSelected() {
       
                this.municipiosFilter = this.municipios.filter(municipio => this.departamentosSelected.includes(municipio.departamento_id));
            },
            municipiosSelected() {
       
                this.comunidadsFilter = this.comunidads.filter(comunidad => this.municipiosSelected.includes(comunidad.municipio_id));
            },
            suelos() {
                this.$emit('update:suelos', this.suelos);
            },
            manejo_parcelas(){
                this.$emit('update:manejo_parcelas', this.manejo_parcelas);
            },
            plagas(){
                this.$emit('update:plagas', this.plagas);
            },
            enfermedades(){
                this.$emit('update:enfermedades', this.enfermedades);
            },
            rendimentos(){
                this.$emit('update:rendimentos', this.rendimentos);
            },
            fenologia(){
                this.$emit('update:fenologia', this.fenologia);
            },
            parcelasData(){
                this.$emit('update:parcelasData', this.parcelasData);
            },
            showTable(){
                this.$emit('update:showTable', this.showTable);
            }

        },

     
        methods: {
            submit: function (event) {
                this.showTable=true
                axios({
                    method: 'post',
                    url: "/show",
                    data: {
                        comunidadsSelected: this.comunidadsSelected,
                        modulesSelected: this.modulesSelected,
                        startDate: this.startDate,
                        endDate: this.endDate,
                        stationsSelected: this.stationsSelected,
                        parcelasModulesSelected: this.parcelasModulesSelected,
                        cultivosModulesSelected: this.cultivosModulesSelected,
                        aggregationSelected: this.aggregationSelected,
                        meteoParameterSelected: this.meteoParameterSelected,
                        yearSelected: this.yearSelected,
                        monthInitialSelected: this.monthInitialSelected,
                        monthFinalSelected: this.monthFinalSelected,
                        yearInitialSelected: this.yearInitialSelected,
                        yearFinalSelected: this.yearFinalSelected,

                    }
                })
                .then((result) => {
                    this.weather = result.data.weather;
                    this.parcelasData = result.data.parcelas;
                    this.suelos = result.data.suelos;
                    this.manejo_parcelas = result.data.manejo_parcelas;
                    this.plagas = result.data.plagas;
                    this.enfermedades = result.data.enfermedades;
                    this.rendimentos = result.data.rendimentos;
                    this.fenologia = result.data.fenologia;
                    this.senamhi = result.data.senamhi;
                    this.stationDetails = result.data.station;
                    console.log(result);
                   
                }, (error) => {
                    console.log(error);
                });          
            }
        } 
    }

</script>
