<template>
    <div class="container">
        <div id="title">
            <h2>Data Download Page</h2>
        </div>

        <!-- Section 1. Criteria for met data -->
        <table width="100%" border="0">
        <tr>
        <td>
            
            <table width="500" border="1" cellpadding="5" cellspacing="2">
                <tr>
                    <td colspan="2" bgcolor="lightblue">
                        <b>Section 1. Met Data</b>
                    </td>
                </tr>

                <tr>
                    <td width="200">
                        Met Station *
                        <br /><br />
                        <p>(Press [Cntl] for multiple selection)</p>
                    </td>
                    <td>
                        <select v-model="metDataForm.stations" multiple>
                            <option :key="station.id" v-for="station in stations" :value="station.id">
                                {{ station.id }}. {{ station.label }}
                            </option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Aggregation *</td>
                    <td>
                        <select
                            v-model="metDataForm.aggregation"
                            @change="aggregationChange"
                        >
                            <option
                                v-for="aggregation in aggregations"
                                :key="aggregation.value"
                                :value="aggregation.value"
                            >
                                {{ aggregation.label }}
                            </option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>From *</td>
                    <td>
                        <select v-model="metDataForm.fromYear">
                            <option v-for="year in years" :value="year" :key="year">
                                {{ year }}
                            </option>
                        </select>
                    </td>
                </tr>

                <!-- disable To Year when aggregation is Senamhi Daily -->
                <tr>
                    <td>To *</td>
                    <td>
                        <select v-model="metDataForm.toYear" :disabled="metDataForm.aggregation == 'senamhi_daily'">
                            <option v-for="year in years" :value="year" :key="year">
                                {{ year }}
                            </option>
                        </select>
                    </td>
                </tr>

                <!-- disable Individual Variable when aggreation is not Senamhi Daily or Senamhi Monthly -->
                <tr>
                    <td>Individual Variable</td>
                    <td>
                        <select v-model="metDataForm.meteoIndividualVariable" :disabled="metDataForm.aggregation != 'senamhi_daily' && metDataForm.aggregation != 'senamhi_monthly'">
                            <option
                                v-for="meteoIndividualVariable in meteoIndividualVariables"
                                :value="meteoIndividualVariable.value"
                                :key="meteoIndividualVariable.value"
                            >
                                {{ meteoIndividualVariable.label }}
                            </option>
                        </select>
                    </td>
                </tr>

            </table>

            <table width="500" border="0" cellpadding="10" cellspacing="2">
                <tr>
                    <td align="center">
                        <input
                            type="button"
                            @click="showGraph1"
                            id="btnShowGraph1"
                            name="btnShowGraph1"
                            value="Show Graph"
                        />
                        &nbsp;
                        <input
                            type="button"
                            @click="downloadMetDataFile"
                            id="btnDownloadMetDataFile"
                            name="btnDownloadMetDataFile"
                            value="Download File"
                        />
                    </td>
                </tr>
            </table>

        </td>

        <!-- Section 1 graph -->

        <td width="500" align="center">
            <div id="graph1">
                <img :src="metDataForm.graph1Url" width="500" height="300">
            </div>
        </td>
        
        </tr>
        </table>


        <br />


        <!-- Section 2. Criteria for additional graph -->
        <table width="100%" border="0">
        <tr>
        <td valign="top">
            
            <table width="500" border="1" cellpadding="5" cellspacing="2">
                <tr>
                    <td colspan="2" bgcolor="lightblue">
                        <b>Section 2. Additional Graphs</b>
                    </td>
                </tr>

                <tr>
                    <td>Graph Type *</td>
                    <td>
                        <select v-model="graphForm.graphType">
                            <option
                                v-for="graphType in graphTypes"
                                :value="graphType.value"
                                :key="graphType"
                            >
                                {{ graphType.label }}
                            </option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td width="200">
                        Met Station *
                    </td>
                    <td>
                        <select v-model="graphForm.station">
                            <option :key="station.id" v-for="station in stations" :value="station.id">
                                {{ station.id }}. {{ station.label }}
                            </option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>From *</td>
                    <td>
                        <select v-model="graphForm.fromYear">
                            <option v-for="year in years" :value="year" :key="year">
                                {{ year }}
                            </option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>To *</td>
                    <td>
                        <select v-model="graphForm.toYear">
                            <option v-for="year in years" :value="year" :key="year">
                                {{ year }}
                            </option>
                        </select>
                    </td>
                </tr>

                <!-- meteo individual variables for time series, boxplot -->
                <tr>
                    <td>Variable Type</td>
                    <td>
                        <select v-model="graphForm.meteoVariableType">
                            <option
                                v-for="meteoVariableType in meteoVariableTypes"
                                :value="meteoVariableType.value"
                                :key="meteoVariableType"
                            >
                                {{ meteoVariableType.label }}
                            </option>
                        </select>
                    </td>
                </tr>
            </table>

            <table width="500" border="0" cellpadding="10" cellspacing="2">
                <tr>
                    <td align="center">
                        <input
                            type="button"
                            @click="showGraph2"
                            id="btnShowGraph2"
                            name="btnShowGraph2"
                            value="Show Graph"
                        />
                    </td>
                </tr>
            </table>

        </td>

        <!-- Section 2 graph -->

        <td width="500" align="center">
            <div id="graph2">
                <img :src="graphForm.graph2Url" width="500" height="300">
            </div>
        </td>
        </tr>
        </table>


        <br/>

       
        <!-- Section 3. Criteria for Agronomic Data -->
        <!-- comment temporary when "Agronomic Data" is hidden -->
<!--
        <table width="100%" border="0">
        <tr>
        <td>
            
            <table width="500" border="1" cellpadding="5" cellspacing="2">
                <tr>
                    <td colspan="2" bgcolor="lightblue">
                        <b>Section 3. Agronomic Data</b>
                    </td>
                </tr>
        
                <tr>
                    <td width="200">Departamento *</td>
                    <td>
                        <select
                            v-model="agroDataForm.departamento"
                            @change="departamentoChanged"
                        >
                            <option
                                v-for="departamento in departamentos"
                                :value="departamento.id"
                                :key="departamento.id"
                            >
                                {{ departamento.name }}
                            </option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Municipio *</td>
                    <td>
                        <select
                            v-model="agroDataForm.municipio"
                            @change="municipioChanged"
                        >
                            <option
                                v-for="municipio in municipiosFiltered"
                                :value="municipio.id"
                                :key="municipio.id"
                            >
                                {{ municipio.name }}
                            </option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Comunidad *</td>
                    <td>
                        <select v-model="agroDataForm.comunidad">
                            <option
                                v-for="comunidad in comunidadsFiltered"
                                :value="comunidad.id"
                                :key="comunidad.id"
                            >
                                {{ comunidad.name }}
                            </option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Plot Level Data</td>
                    <td>
                        <input
                            type="checkbox"
                            v-model="agroDataForm.plotLevelSuelos"
                            id="plotLevelSuelosSelected"
                        />
                        <label for="plotLevelSuelosSelected">&nbsp;Suelos</label
                        ><br />
                        <input
                            type="checkbox"
                            v-model="agroDataForm.plotLevelManejoDeLaParcela"
                            id="plotLevelManejoDeLaParcelaSelected"
                        />
                        <label for="plotLevelManejoDeLaParcelaSelected"
                            >&nbsp;Manejo de la parcela</label
                        >
                    </td>
                </tr>

                <tr>
                    <td>Crop Level Data</td>
                    <td>
                        <input
                            type="checkbox"
                            v-model="agroDataForm.cropLevelFenologia"
                            id="cropLevelFenologiaSelected"
                        />
                        <label for="cropLevelFenologiaSelected"
                            >&nbsp;Fenologia</label
                        ><br />
                        <input
                            type="checkbox"
                            v-model="agroDataForm.cropLevelPlagas"
                            id="cropLevelPlagasSelected"
                        />
                        <label for="cropLevelPlagasSelected">&nbsp;Plagas</label
                        ><br />
                        <input
                            type="checkbox"
                            v-model="agroDataForm.cropLevelEnfermedades"
                            id="cropLevelEnfermedadesSelected"
                        />
                        <label for="cropLevelEnfermedadesSelected"
                            >&nbsp;Enfermedades</label
                        ><br />
                        <input
                            type="checkbox"
                            v-model="agroDataForm.cropLevelRendimientos"
                            id="cropLevelRendimientosSelected"
                        />
                        <label for="cropLevelRendimientosSelected"
                            >&nbsp;Rendimientos</label
                        ><br />
                    </td>
                </tr>

            </table>

            <table width="500" border="0" cellpadding="10" cellspacing="2">
                <tr>
                    <td align="center">
                        <input
                            type="button"
                            @click="showGraph3"
                            id="btnShowGraph3"
                            name="btnShowGraph3"
                            value="Show Graph"
                        />
                        &nbsp;
                        <input
                            type="button"
                            @click="downloadAgroDataFile"
                            id="btnDownloadAgroDataFile"
                            name="btnDownloadAgroDataFile"
                            value="Download File"
                        />
                    </td>
                </tr>
            </table>

        </td>

        <td width="500" align="center">
            <div id="graph3">
                <img :src="agroDataForm.graph3Url" width="500" height="300">
            </div>
        </td>
        </tr>
        </table>


        <br/>
-->

    </div>
</template>


<script>
    import { computed, h, onMounted, ref, watchEffect } from "vue";

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
                aggregations: [
                    { label: "Senamhi Diario", value: "senamhi_daily" },
                    { label: "Senamhi Mensual", value: "senamhi_monthly" },
                    { label: "Diario", value: "daily_data" },
                    { label: "Diez días", value: "tendays_data" },
                    { label: "Mensual", value: "monthly_data" },
                    { label: "Anual", value: "yearly_data" },
                ],

                years: [
                    2000, 2001, 2002, 2003, 2004, 2005, 2006, 2007, 2008, 2009,
                    2010, 2011, 2012, 2013, 2014, 2015, 2016, 2017, 2018, 2019,
                    2020, 2021, 2022, 2023, 2024, 2025, 2026, 2027, 2028, 2029,
                    2030, 2031, 2032, 2033, 2034, 2035, 2036, 2037, 2038, 2039,
                    2040, 2041, 2042, 2043, 2044, 2045, 2046, 2047, 2048, 2049,
                    2050, 2051, 2052, 2053, 2054, 2055, 2056, 2057, 2058, 2059,
                    2060, 2061, 2062, 2063, 2064, 2065, 2066, 2067, 2068, 2069,
                    2070, 2071, 2072, 2073, 2074, 2075, 2076, 2077, 2078, 2079,
                    2080, 2081, 2082, 2083, 2084, 2085, 2086, 2087, 2088, 2089,
                    2090, 2091, 2092, 2093, 2094, 2095, 2096, 2097, 2098, 2099,
                    2100,
                ],

                // meteo individual variables for Senamhi Daily, Senamhi Monthly
                meteoIndividualVariables: [
                    {
                        label: "Temperatura Máxima Interna (°C)",
                        value: "max_temperatura_interna",
                    },
                    {
                        label: "Temperatura Mínima Interna (°C)",
                        value: "min_temperatura_interna",
                    },
                    {
                        label: "Temperatura Media Interna (°C)",
                        value: "avg_temperatura_interna",
                    },
                    {
                        label: "Temperatura Máxima Externa (°C)",
                        value: "max_temperatura_externa",
                    },
                    {
                        label: "Temperatura Mínima Externa (°C)",
                        value: "min_temperatura_externa",
                    },
                    {
                        label: "Temperatura Media Externa (°C)",
                        value: "avg_temperatura_externa",
                    },
                    {
                        label: "Humedad Máxima Interna %",
                        value: "max_humedad_interna",
                    },
                    {
                        label: "Humedad Mínima Interna %",
                        value: "min_humedad_interna",
                    },
                    {
                        label: "Humedad Media Interna %",
                        value: "avg_humedad_interna",
                    },
                    {
                        label: "Humedad Máxima Externa %",
                        value: "max_humedad_externa",
                    },
                    {
                        label: "Humedad Mínima Externa %",
                        value: "min_humedad_externa",
                    },
                    {
                        label: "Humedad Media Externa %",
                        value: "avg_humedad_externa",
                    },
                    {
                        label: "Presion Relativa Máxima (hPa)",
                        value: "max_presion_relativa",
                    },
                    {
                        label: "Presion Relativa Mínima (hPa)",
                        value: "min_presion_relativa",
                    },
                    {
                        label: "Presion Relativa Media (hPa)",
                        value: "avg_presion_relativa",
                    },
                    {
                        label: "Presion Absoluta Máxima (hPa)",
                        value: "max_presion_absoluta",
                    },
                    {
                        label: "Presion Absoluta Mínima (hPa)",
                        value: "min_presion_absoluta",
                    },
                    {
                        label: "Presion Absoluta Media (hPa)",
                        value: "avg_presion_absoluta",
                    },
                    {
                        label: "Velocidad Viento Máxima (m/s)",
                        value: "max_velocidad_viento",
                    },
                    {
                        label: "Velocidad Viento Mínima (m/s)",
                        value: "min_velocidad_viento",
                    },
                    {
                        label: "Velocidad Viento Media (m/s)",
                        value: "avg_velocidad_viento",
                    },
                    {
                        label: "Sensacion Termica Máxima (°C)",
                        value: "max_sensacion_termica",
                    },
                    {
                        label: "Sensacion Termica Mínima (°C)",
                        value: "min_sensacion_termica",
                    },
                    {
                        label: "Sensacion Termica Media (°C)",
                        value: "avg_sensacion_termica",
                    },
                    {
                        label: "Precipitación Diaria (mm)",
                        value: "lluvia_24_horas_total",
                    },
                ],

                // graph types
                graphTypes: [
                    { label: "Gráfico de series temporales", value: "time_series" },
                    { label: "Gráfico de caja (boxplot)", value: "boxplot" },
                ],

                // meteo variable types for time series, boxplot
                meteoVariableTypes: [
                    {
                        label: "Temperatura Interna (°C)",
                        value: "temperatura_interna",
                    },
                    {
                        label: "Temperatura Externa (°C)",
                        value: "temperatura_externa",
                    },
                    { label: "Humedad Interna %", value: "humedad_interna" },
                    { label: "Humedad Externa %", value: "humedad_externa" },
                    { label: "Presion Relativa (hPa)", value: "presion_relativa" },
                    { label: "Presion Absoluta (hPa)", value: "presion_absoluta" },
                    { label: "Velocidad Viento (m/s)", value: "velocidad_viento" },
                    { label: "Sensacion Termica (°C)", value: "sensacion_termica" },
                    {
                        label: "Precipitación Diaria (mm)",
                        value: "lluvia_24_horas_total",
                    },
                ],

                // form for section 1 met data
                metDataForm: {
                    // array for storing multiple values selected
                    stations: [],

                    // variable for storing single value selected
                    aggregation: "",
                    fromYear: "",
                    toYear: "",
                    meteoIndividualVariable: "",

                    // action type can be "show_graph" or "download_file"
                    actionType: "",

                    // heatmap is the only available graph type in Section 1. Met Data
                    graphType: "heatmap",

                    // to store the URL of the graph 1 to be showed in front end
                    graph1Url: "/images/graph/blank5x5.png",

                    // to store the validation result before "Show Graph" or "Download File"
                    validationResult: false,
                },

                // form for section 2 additional graphs
                graphForm: {
                    graphType: "",
                    station: "",
                    fromYear: "",
                    toYear: "",
                    meteoVariableType: "",

                    // action type can be "show_graph"
                    actionType: "show_graph",

                    // to store the URL of the graph 2 to be showed in front end
                    graph2Url: "/images/graph/blank5x5.png",

                    // to store the validation result before "Show Graph"
                    validationResult: false,
                },

                // form for section 3 agronomic data
                agroDataForm: {
                    departamento: "",
                    municipio: "",
                    comunidad: "",

                    // variable for storing checkbox value
                    plotLevelSuelos: false,
                    plotLevelManejoDeLaParcela: false,
                    cropLevelFenologia: false,
                    cropLevelPlagas: false,
                    cropLevelEnfermedades: false,
                    cropLevelRendimientos: false,

                    // action type can be "show_graph" or "download_file"
                    actionType: "",

                    // to store the URL of the graph 2 to be showed in front end
                    graph3Url: "/images/graph/blank5x5.png",

                    // to store the validation result before "Show Graph" or "Download File"
                    validationResult: false,
                }

            };
        },

        // custom methods section
        methods: {

            // functions for section 1 met data

            aggregationChange() {
                //alert("aggregationChange");
            },

            validateMetDataForm() {
                //alert("validateMetDataForm");

                // array empty or does not exist
                if (
                    this.metDataForm.stations === undefined ||
                    this.metDataForm.stations.length == 0
                ) {
                    alert("Please select at least one met station");
                    return false;
                }

                if (this.metDataForm.aggregation == "") {
                    alert("Please select an aggregation");
                    return false;
                }

                if (this.metDataForm.fromYear == "") {
                    alert("Please select a From Year");
                    return false;
                }

                // To year is not necessary for senamhi daily
                if (this.metDataForm.aggregation != "senamhi_daily") {
                    if (this.metDataForm.toYear == "") {
                        alert("Please select a To Year");
                        return false;
                    }

                    if (this.metDataForm.fromYear > this.metDataForm.toYear) {
                        alert("From Year should be equal to or earlier than To Year");
                        return false;
                    }
                }

                if (
                    this.metDataForm.aggregation == "senamhi_daily" ||
                    this.metDataForm.aggregation == "senamhi_monthly"
                ) {
                    if (this.metDataForm.stations.length > 1) {
                        alert("For senamhi daily or senamhi monthly, please select one met station only");
                        return false;
                    }

                    if (this.metDataForm.meteoIndividualVariable == "") {
                        alert("Please select an individual variable");
                        return false;
                    }
                }

                // passed all validation
                return true;
            },

            // to send request for generate a graph to be showed
            // to be called when "Show Graph" button is clicked in section 1 met data
            showGraph1() {
                //alert("showGraph1");

                // return immediately if validation result is false, error message for specific criteria should be showed already
                if (this.validateMetDataForm() == false) {
                    return;
                }

                this.showPleaseWaitInGraph1();

                // construct URL with parameter values
                var reportUrl = "/data-download/download"

                this.metDataForm.actionType = "show_graph";

                // axios send request to generate graph to be showed in front end
                // the primary objective is to show whether there is met data for specified criteria
                axios
                    .post(reportUrl, this.metDataForm, {

                    })
                    .then(response => {
                        //console.log(response.data);

                        // set the URL of generated graph to form variable graph1Url, img src and graph will be updated by Vue automatically
                        this.metDataForm.graph1Url = response.data;
                    })
            },

            showPleaseWaitInGraph1() {
                this.metDataForm.graph1Url = "/images/graph/please_wait.png";
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

            // to send request for met data file download
            // to be called when "Download File" button is clicked in section 1 met data
            downloadMetDataFile() {
                //alert("downloadMetDataFile");

                // return immediately if validation result is false, error message for specific criteria should be showed already
                if (this.validateMetDataForm() == false) {
                    return;
                }

                // construct URL with parameter values
                var reportUrl = "/data-download/download"

                this.metDataForm.actionType = "download_file";

                // axios send request to generate file for download
                axios
                    .post(reportUrl, this.metDataForm, {
                        responseType: 'arraybuffer'
                    })
                    .then(response => {
                        //console.log(response.data);

                        // generated file is in Excel file format for daily, tendays, monthly, yearly
                        var fileExt = "xlsx";
                        var fileType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'

                        // This code segment can trigger "Save As" dialog with a pre-defined file name
                        const blob = new Blob([response.data],
                                        { type: fileType }),
                                        link = document.createElement('a');

                        link.href = window.URL.createObjectURL(blob);

                        // get aggregation from form, use it as a part of the filename
                        var aggregationLabel = this.aggregations.filter((item) => item.value === this.metDataForm.aggregation)[0].label;

                        // prepare filename with current date and time
                        var today = new Date();
                        var filename = "Met Data - " +
                                       aggregationLabel + " - " +
                                       today.getFullYear() +
                                       this.addLeadingZero(today.getMonth()+1) +
                                       this.addLeadingZero(today.getDate()+1) +
                                       "_" +
                                       this.addLeadingZero(today.getHours()) +
                                       this.addLeadingZero(today.getMinutes()) +
                                       this.addLeadingZero(today.getSeconds()) +
                                       "." +
                                       fileExt;

                        link.download = filename;
                        link.click();
                    })
            },


            // functions for section 2 additional graphs

            validateGraphForm() {
                //alert("validateGraphForm");

                if (this.graphForm.graphType == "") {
                    alert("Please select a graph type");
                    return false;
                }

                // array empty or does not exist
                if (this.graphForm.station == "") {
                    alert("Please select a met station");
                    return false;
                }

                if (this.graphForm.fromYear == "") {
                    alert("Please select a From Year");
                    return false;
                }

                if (this.graphForm.toYear == "") {
                    alert("Please select a To Year");
                    return false;
                }

                if (this.graphForm.fromYear > this.graphForm.toYear) {
                    alert("From Year should be equal to or earlier than To Year");
                    return false;
                }

                if (this.graphForm.meteoVariableType == "") {
                    alert("Please select a variable type");
                    return false;
                }

                // passed all validation
                return true;
            },

            // to send request for generate a graph to be showed
            // to be called when "Show Graph" button is clicked in section 2 additional graphs section
            showGraph2() {
                //alert("showGraph2");

                // return immediately if validation result is false, error message for specific criteria should be showed already
                if (this.validateGraphForm() == false) {
                    return;
                }

                this.showPleaseWaitInGraph2();

                // construct URL with parameter values
                var reportUrl = "/data-download/download"

                this.graphForm.actionType = "show_graph";

                // axios send request to generate graph to be showed in front end
                // the primary objective is to show whether there is met data for specified criteria
                axios
                    .post(reportUrl, this.graphForm, {

                    })
                    .then(response => {
                        //console.log(response.data);

                        // set the URL of generated graph to graphForm variable graph2Url, img src and graph will be updated by Vue automatically
                        this.graphForm.graph2Url = response.data;
                    })
            },

            showPleaseWaitInGraph2() {
                this.graphForm.graph2Url = "/images/graph/please_wait.png";
            },


            // functions for section 3 agronomic data

            // to reset municipio and comunidad when departmento is changed
            // to be called when departmento value changed
            departamentoChanged() {
                //alert("departamentoChanged");

                // reset municipio and comunidad
                this.agroDataForm.municipio = "";
                this.agroDataForm.comunidad = "";

                // filter municipios that belong to selected departamento
                this.municipiosFiltered = this.municipios.filter(
                    this.checkMunicipio
                );

                // reset comunidadsFiltered as municipios is not selected yet
                this.comunidadsFiltered = [];
            },

            // to determine each municipio whether belongs to the selected departamento
            checkMunicipio(municipio) {
                return municipio.departamento_id == this.agroDataForm.departamento;
            },

            // to reset comunidad when municipio is changed
            // to be called when municipio value changed
            municipioChanged() {
                //alert("municipioChanged");

                // reset comunidad
                this.agroDataForm.comunidad = "";

                // filter municipios that belong to selected municipio
                this.comunidadsFiltered = this.comunidads.filter(
                    this.checkComunidad
                );
            },

            // to determine each comunidad item whether belongs to the selected municipio
            checkComunidad(comunidad) {
                return comunidad.municipio_id == this.agroDataForm.municipio;
            },

            validateAgroDataForm() {
                //alert("validateAgroDataForm");

                if (this.agroDataForm.departamento == "") {
                    alert("Please select a departamento");
                    return false;
                }

                if (this.agroDataForm.municipio == "") {
                    alert("Please select a municipio");
                    return false;
                }

                if (this.agroDataForm.comunidad == "") {
                    alert("Please select a comunidad");
                    return false;
                }

                // question: TBC, is plot level data a compulsory criteria?
                // plot level data, need to check at least one option
                if (
                    !this.agroDataForm.plotLevelSuelos &&
                    !this.agroDataForm.plotLevelManejoDeLaParcela
                ) {
                    alert("Please select at least one plot level data");
                    return false;
                }

                // question: TBC, is crop level data a compulsory criteria?
                // crop level data, need to check at least one option
                if (
                    !this.agroDataForm.cropLevelFenologia &&
                    !this.agroDataForm.cropLevelPlagas &&
                    !this.agroDataForm.cropLevelEnfermedades &&
                    !this.agroDataForm.cropLevelRendimientos
                ) {
                    alert("Please select at least one crop level data");
                    return false;
                }

                // passed all validation
                return true;

            },

            // to send request for generate a graph to be showed
            // to be called when "Show Graph" button is clicked in section 3 agronomic data
            showGraph3() {
                //alert("showGraph3");

                // return immediately if validation result is false, error message for specific criteria should be showed already
                if (this.validateAgroDataForm() == false) {
                    return;
                }

                this.showPleaseWaitInGraph3();

                alert("TODO: confirm whether we need to show graph in section 3");

            },

            showPleaseWaitInGraph3() {
                this.agroDataForm.graph3Url = "/images/graph/please_wait.png";
            },

            // to send request for met data file download
            // to be called when "Download File" button is clicked in section 3 agronomic data
            downloadAgroDataFile() {
                //alert("downloadAgroDataFile");

                // return immediately if validation result is false, error message for specific criteria should be showed already
                if (this.validateAgroDataForm() == false) {
                    return;
                }

                // TOOD: URL to be confirmed
                // construct URL with parameter values
                var reportUrl = "/data-download/download"

                this.agroDataForm.actionType = "download_file";

                // axios send request to generate file for download
                axios
                    .post(reportUrl, this.metDataForm, {
                        responseType: 'arraybuffer'
                    })
                    .then(response => {
                        //console.log(response.data);

                        // generated file is in Excel file format for daily, tendays, monthly, yearly
                        var fileExt = "xlsx";
                        var fileType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'

                        // This code segment can trigger "Save As" dialog with a pre-defined file name
                        const blob = new Blob([response.data],
                                        { type: fileType }),
                                        link = document.createElement('a');

                        link.href = window.URL.createObjectURL(blob);

                        // prepare filename with current date and time
                        var today = new Date();
                        var filename = "Agro Data - " +
                                       today.getFullYear() +
                                       this.addLeadingZero(today.getMonth()+1) +
                                       this.addLeadingZero(today.getDate()+1) +
                                       "_" +
                                       this.addLeadingZero(today.getHours()) +
                                       this.addLeadingZero(today.getMinutes()) +
                                       this.addLeadingZero(today.getSeconds()) +
                                       "." +
                                       fileExt;

                        link.download = filename;
                        link.click();
                    })
            },
 
        },

        // Vue life cycle event section
        // mounted() function will be executed before Vue component is generated
        // Axios send ajax requests to get data list from server
        mounted() {
            //alert("mounted");

            // get all stations from table stations
            axios.get("api/stations").then((response) => {
                this.stations = response.data;
            }),
            // get all departmentos from table departamento
            axios.get("api/departamentos").then((response) => {
                this.departamentos = response.data;
            }),
            // get all municipios from table municipio
            axios.get("api/municipios").then((response) => {
                this.municipios = response.data;
            }),
            // get all comunidads from table comunidad
            axios.get("api/comunidads").then((response) => {
                this.comunidads = response.data;
            });
        },

        components: {},

        props: {},

        setup(props) {
            onMounted(() => {});

            // Present initial list as table
            const columns = ref([
                {
                    title: "Código variedad ",
                    key: "id",
                },
                {
                    key: "common_name",
                    title: "Nombre común",
                },
            ]);

            watchEffect((onInvalidate) => {});

            // Click on a table row and get the full variety props:

            watchEffect((onInvalidateGet) => {});
        },
    };
</script>


<style>
</style>
