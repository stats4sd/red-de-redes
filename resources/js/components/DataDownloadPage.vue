<template>

    <div class="container">

        <div id="title">
            <h2>Data Download Page</h2>
        </div>

        <!-- Criteria for met data -->
        <table width="500" border="1" cellpadding="5" cellspacing="2">
            <tr>
                <td colspan="2" bgcolor="lightblue">
                    <b>Criteria for Met Data</b>
                </td>
            </tr>

            <tr>
                <td width="200">
                    Met Station *
                    <br/><br/><p>(Press [Cntl] for multiple selection)</p>
                </td>
                <td>
                    <select v-model="stationsSelected" multiple>
                        <option v-for="station in stations" :value="station.id">{{ station.id }}. {{ station.label }}</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>
                    Aggregation *
                </td>
                <td>
                    <select v-model="aggregationSelected">
                        <option v-for="aggregation in aggregations" :value="aggregation.value">{{ aggregation.label }}</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>
                    From *
                </td>
                <td>
                    <select v-model="fromMonthSelected">
                        <option v-for="month in months" :value="month.value">{{ month.label }}</option>
                    </select>
                    
                    <select v-model="fromYearSelected">
                        <option v-for="year in years" :value="year">{{ year }}</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>
                    To *
                </td>
                <td>
                    <select v-model="toMonthSelected">
                        <option v-for="month in months" :value="month.value">{{ month.label }}</option>
                    </select>
                    <select v-model="toYearSelected">
                        <option v-for="year in years" :value="year">{{ year }}</option>
                    </select>
                </td>
            </tr>

        </table>


        <br/>


        <!-- Criteria for agronomic data -->
        <table width="500" border="1" cellpadding="5" cellspacing="2">
            <tr>
                <td colspan="2" bgcolor="lightgreen">
                    <b>Criteria for Agronomic Data</b>
                </td>
            </tr>
    
            <tr>
                <td width="200">
                    Departamento *
                </td>
                <td>
                    <select v-model="departamentoSelected" @change="departamentoChanged">
                        <option v-for="departamento in departamentos" :value="departamento.id">{{ departamento.name }}</option>
                    </select>
                </td>
            </tr>
    
            <tr>
                <td>
                    Municipio *
                </td>
                <td>
                    <select v-model="municipioSelected" @change="municipioChanged">
                        <option v-for="municipio in municipiosFiltered" :value="municipio.id">{{ municipio.name }}</option>
                    </select>
                </td>
            </tr>
    
            <tr>
                <td>
                    Comunidad *
                </td>
                <td>
                    <select v-model="comunidadSelected">
                        <option v-for="comunidad in comunidadsFiltered" :value="comunidad.id">{{ comunidad.name }}</option>
                    </select>
                </td>
            </tr>
    
            <tr>
                <td>
                    Plot Level Data
                </td>
                <td>
                    <input type="checkbox" v-model="plotLevelSuelosSelected" id="plotLevelSuelosSelected">
                    <label for="plotLevelSuelosSelected">&nbsp;Suelos</label><br/>
                    <input type="checkbox" v-model="plotLevelManejoDeLaParcelaSelected" id="plotLevelManejoDeLaParcelaSelected">
                    <label for="plotLevelManejoDeLaParcelaSelected">&nbsp;Manejo de la parcela</label>
                </td>
            </tr>
    
            <tr>
                <td>
                    Crop Level Data
                </td>
                <td>
                    <input type="checkbox" v-model="cropLevelFenologiaSelected" id="cropLevelFenologiaSelected">
                    <label for="cropLevelFenologiaSelected">&nbsp;Fenologia</label><br/>
                    <input type="checkbox" v-model="cropLevelPlagasSelected" id="cropLevelPlagasSelected">
                    <label for="cropLevelPlagasSelected">&nbsp;Plagas</label><br/>
                    <input type="checkbox" v-model="cropLevelEnfermedadesSelected" id="cropLevelEnfermedadesSelected">
                    <label for="cropLevelEnfermedadesSelected">&nbsp;Enfermedades</label><br/>
                    <input type="checkbox" v-model="cropLevelRendimientosSelected" id="cropLevelRendimientosSelected">
                    <label for="cropLevelRendimientosSelected">&nbsp;Rendimientos</label><br/>
                </td>
            </tr>
    
        </table>


        <br/>


        <table width="500" border="0" cellpadding="5" cellspacing="2">
            <tr>
                <td align="center">
                    <input type="button" @click="showValues" id="btnShowValues" name="btnShowValues" value="Show Values">
                    &nbsp;
                    <input type="button" @click="validateValues" id="btnValidate" name="btnValidate" value="Validate">
                    &nbsp;
                    <input type="button" @click="assignValues" id="btnAssign" name="btnAssign" value="Assign Values">
                    &nbsp;
                    <input type="button" @click="downloadData" id="btnSubmit" name="btnSubmit" value="Submit">
                </td>
            </tr>
        </table>

    </div>

</template>


<script>
import {computed, h, onMounted, ref, watchEffect} from "vue";

export default {

    // define component name
    name: "DataDownloadPage",

    // data section for variable declaration
    data() {
        return {

            // empty array, to get option list from server
            stations: [],
            departamentos: [],
            municipios: [],
            comunidads: [],

            // array for filtered option list
            municipiosFiltered: [],
            comunidadsFiltered: [],

            // pre-defined options for corresponding selection box
            // added "Raw Data" for illustration temporary
            aggregations: [
                {label: 'Raw Data', value:'raw_data'}, 
                {label: 'Senamhi Diario', value:'senamhi_daily'}, 
                {label: 'Senamhi Mensual', value:'senamhi_monthly'}, 
                {label: 'Diario', value:'daily_data'}, 
                {label: 'Diez días', value:'tendays_data'}, 
                {label: 'Mensual', value:'monthly_data'}, 
                {label: 'Anual', value:'yearly_data'}
            ],

            months: [
                {label: 'Jan', value: '01'},
                {label: 'Feb', value: '02'},
                {label: 'Mar', value: '03'},
                {label: 'Apr', value: '04'},
                {label: 'May', value: '05'},
                {label: 'Jun', value: '06'},
                {label: 'Jul', value: '07'},
                {label: 'Aug', value: '08'},
                {label: 'Sep', value: '09'},
                {label: 'Oct', value: '10'},
                {label: 'Nov', value: '11'},
                {label: 'Dec', value: '12'},
            ],

            years: [
                2010,2011,2012,2013,2014,2015,2016,2017,2018,2019,
                2020,2021,2022,2023,2024,2025,2026,2027,2028,2029,
                2030,2031,2032,2033,2034,2035,2036,2037,2038,2039,
                2040,2041,2042,2043,2044,2045,2046,2047,2048,2049,
                2050,2051,2052,2053,2054,2055,2056,2057,2058,2059,
                2060,2061,2062,2063,2064,2065,2066,2067,2068,2069,
                2070,2071,2072,2073,2074,2075,2076,2077,2078,2079,
                2080,2081,2082,2083,2084,2085,2086,2087,2088,2089,
                2090,2091,2092,2093,2094,2095,2096,2097,2098,2099,
            ],


            // variables for storing the selected value(s) of corresponding selection box

            // array for storing multiple values selected
            stationsSelected: [],

            // variable for storing single value selected
            aggregationSelected: '',
            fromMonthSelected: '',
            fromYearSelected: '',
            toMonthSelected: '',
            toYearSelected: '',
            departamentoSelected: '',
            municipioSelected: '',
            comunidadSelected: '',

            // variable for storing checkbox value
            plotLevelSuelosSelected: false,
            plotLevelManejoDeLaParcelaSelected: false,
            cropLevelFenologiaSelected: false,
            cropLevelPlagasSelected: false,
            cropLevelEnfermedadesSelected: false,
            cropLevelRendimientosSelected: false,

        }
    },

    // custom methods section
    methods: {

        // to reset municipio and comunidad when departmento is changed
        // to be called when departmento value changed
        departamentoChanged() {
            //alert("departamentoChanged");

            // reset municipio and comunidad
            this.municipioSelected = ''
            this.comunidadSelected = ''

            // filter municipios that belong to selected departamento
            this.municipiosFiltered = this.municipios.filter(this.checkMunicipio);

            // reset comunidadsFiltered as municipios is not selected yet
            this.comunidadsFiltered = [];
        },

        // to determine each municipio whether belongs to the selected departamento
        checkMunicipio(municipio) {
            return (municipio.departamento_id == this.departamentoSelected);
        },

        // to reset comunidad when municipio is changed
        // to be called when municipio value changed
        municipioChanged() {
            //alert("municipioChanged");

            // reset comunidad
            this.comunidadSelected = ''

            // filter municipios that belong to selected municipio
            this.comunidadsFiltered = this.comunidads.filter(this.checkComunidad);
        },

        // to determine each comunidad item whether belongs to the selected municipio
        checkComunidad(comunidad) {
            return (comunidad.municipio_id == this.municipioSelected);
        },

        // to show selected values for checking
        // to be called when "Show Values" button is clicked
        showValues() {
            //alert("showValues");

            var result = ''

            result += 'Met station: ' + this.stationsSelected + '\n'
            result += 'Aggregation: ' + this.aggregationSelected + '\n'
            result += 'From : ' + this.fromMonthSelected + ' ' + this.fromYearSelected + '\n'
            result += 'To : ' + this.toMonthSelected + ' ' + this.toYearSelected + '\n'
            result += 'Departamento : ' + this.departamentoSelected  + '\n'
            result += 'Municipio  : ' + this.municipioSelected + '\n'
            result += 'Comunidad  : ' + this.comunidadSelected + '\n'
            result += 'Suelos  : ' + this.plotLevelSuelosSelected + '\n'
            result += 'Manejo de la parcela  : ' + this.plotLevelManejoDeLaParcelaSelected + '\n'
            result += 'Fenologia  : ' + this.cropLevelFenologiaSelected + '\n'
            result += 'Plagas  : ' + this.cropLevelPlagasSelected + '\n'
            result += 'Enfermedades  : ' + this.cropLevelEnfermedadesSelected + '\n'
            result += 'Rendimientos  : ' + this.cropLevelRendimientosSelected + '\n'

            alert(result)
        },

        validateValues() {
            //alert("validateValues");

            var result = true;

            // array empty or does not exist
            if (this.stationsSelected === undefined || this.stationsSelected.length == 0) {
                result = false;
                alert("Please select at least one met station");
                return;
            }

            if (this.aggregationSelected == '') {
                result = false;
                alert("Please select an aggregation");
                return;
            }

            if (this.fromMonthSelected == '') {
                result = false;
                alert("Please select a From Month");
                return;
            }

            if (this.fromYearSelected == '') {
                result = false;
                alert("Please select a From Year");
                return;
            }

            if (this.toMonthSelected == '') {
                result = false;
                alert("Please select a To Month");
                return;
            }

            if (this.toYearSelected == '') {
                result = false;
                alert("Please select a To Year");
                return;
            }

            if (this.fromYearSelected + this.fromMonthSelected > this.toYearSelected + this.toMonthSelected) {
                result = false;
                alert("Report duration 'From' should be earlier than 'To'");
                return;
            }

            if (this.departamentoSelected == '') {
                result = false;
                alert("Please select a departamento");
                return;
            }

            if (this.municipioSelected == '') {
                result = false;
                alert("Please select a municipio");
                return;
            }

            if (this.comunidadSelected == '') {
                result = false;
                alert("Please select a comunidad");
                return;
            }

            // question: TBC, is plot level data a compulsory criteria?
            // plot level data, need to check at least one option
            if (!this.plotLevelSuelosSelected && !this.plotLevelManejoDeLaParcelaSelected) {
                result = false;
                alert("Please select at least one plot level data");
                return;
            }

            // question: TBC, is crop level data a compulsory criteria?
            // crop level data, need to check at least one option
            if (!this.cropLevelFenologiaSelected && !this.cropLevelPlagasSelected &&
                !this.cropLevelEnfermedadesSelected && !this.cropLevelRendimientosSelected) {
                result = false;
                alert("Please select at least one crop level data");
                return;
            }

            // check validation result
            if (result) {
                alert("All criteria are validated, can proceed to generate report");
            } else {
                alert("Some criteria are not properly set, please correct them first");
            }

        },

        // to assign values to criteria to ease testing
        // to be called when "Assign Values" button is clicked
        assignValues() {
            //alert("assignValues");

            this.stationsSelected = ['1','2','3','6'];
            this.aggregationSelected = 'raw_data';
            this.fromMonthSelected = '08';
            this.fromYearSelected = '2020';
            this.toMonthSelected = '08';
            this.toYearSelected = '2020';

            this.departamentoSelected = '1';
            this.municipioSelected = '1';
            this.comunidadSelected = '1';
            this.plotLevelSuelosSelected = 'true';
            this.plotLevelManejoDeLaParcelaSelected = 'false';
            this.cropLevelFenologiaSelected = 'true';
            this.cropLevelPlagasSelected = 'true';
            this.cropLevelEnfermedadesSelected = 'false';
            this.cropLevelRendimientosSelected = 'false';
        },

        // to adding leading zero for value less than 10
        // to be called when preparing file name with current date and time
        addLeadingZero(value) {
            if (value < 10) {
                return "0" + value;
            } else {
                return value;
            }
        },

        // to send request for data download
        // to be called when "Submit" button is clicked
        downloadData() {
            //alert("downloadData");

            // construct URL with parameter values
            var $reportUrl = 'admin/met_raw_data/export?' + 
                        'stations=' + this.stationsSelected +
                        '&aggregation=' + this.aggregationSelected +
                        '&fromYear=' + this.fromYearSelected + 
                        '&fromMonth=' + this.fromMonthSelected +
                        '&toYear=' + this.toYearSelected + 
                        '&toMonth=' + this.toMonthSelected + 
                        '&comunidad=' + this.comunidadSelected + 
                        '&plotLevelSuelos=' + this.plotLevelSuelosSelected + 
                        '&plotLevelManejoDeLaParcela=' + this.plotLevelManejoDeLaParcelaSelected + 
                        '&cropLevelFenologia=' + this.cropLevelFenologiaSelected + 
                        '&cropLevelPlagas=' + this.cropLevelPlagasSelected + 
                        '&cropLevelEnfermedades=' + this.cropLevelEnfermedadesSelected + 
                        '&cropLevelRendimientos=' + this.cropLevelRendimientosSelected
                        ;

            // axios send request to generate excel file for download
            // TODO: how to keep the original generated file name?
            axios
                .get($reportUrl, {
                    responseType: 'arraybuffer'
                })
                .then(response => {

                    // This code segment can trigger "Save As" dialog with a random file name
                    /*
                    let blob = new Blob([response.data], 
                                        { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' }),
                                        url = window.URL.createObjectURL(blob);

                    // Mostly the same, I was just experimenting with different approaches, tried link.click, iframe and other solutions
                    // to trigger "Save As" dialog
                    window.open(url) 
                    */

                    // This code segment can trigger "Save As" dialog with a pre-defined file name
                    const blob = new Blob([response.data],
                                    { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' }),
                                    link = document.createElement('a');
                    
                    link.href = window.URL.createObjectURL(blob);

                    // prepare filename with current date and time
                    var today = new Date();
                    var filename = "data_download - " + 
                                   today.getFullYear() + 
                                   this.addLeadingZero(today.getMonth()+1) + 
                                   this.addLeadingZero(today.getDate()+1) + 
                                   "_" + 
                                   this.addLeadingZero(today.getHours()) + 
                                   this.addLeadingZero(today.getMinutes()) + 
                                   this.addLeadingZero(today.getSeconds()) +
                                   ".xlsx";

                    link.download = filename;
                    link.click();
                })

        }

    },

    // Vue life cycle event section
    // mounted() function will be executed before Vue component is generated
    // Axios send ajax requests to get data list from server
    mounted() {
        //alert("mounted");

        // get all stations from table stations
        axios.get('api/stations').then((response) => {
            this.stations = response.data;
        }),

        // get all departmentos from table departamento
        axios.get('api/departamentos').then((response) => {
            this.departamentos = response.data;
        }),

        // get all municipios from table municipio
        axios.get('api/municipios').then((response) => {
            this.municipios = response.data;
        }),

        // get all comunidads from table comunidad
        axios.get('api/comunidads').then((response) => {
            this.comunidads = response.data;
        })

    },

    components: {
    },

    props: {
    },

    setup(props) {

        onMounted(() => {

        })

        // Present initial list as table
        const columns = ref([
            {
                title: "Código variedad ",
                key: "id",
            },
            {
                key: "common_name",
                title: "Nombre común"
            }
        ]);

        watchEffect(onInvalidate => {

        })


        // Click on a table row and get the full variety props:

        watchEffect(onInvalidateGet => {

        })


    },
}
</script>


<style>

</style>
