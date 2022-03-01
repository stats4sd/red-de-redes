<template>
    <div class="container">
        <div id="title">
            <h2>Data Download Page</h2>
        </div>

        <!-- Criteria for met data -->
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
                    <select v-model="form.stations" multiple>
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
                        v-model="form.aggregation"
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
                    <select v-model="form.fromYear">
                        <option v-for="year in years" :value="year" :key="year">
                            {{ year }}
                        </option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>To *</td>
                <td>
                    <select v-model="form.toYear">
                         <option v-for="year in years" :value="year" :key="year">
                            {{ year }}
                        </option>
                    </select>
                </td>
            </tr>

            <!-- meteo individual variables for Senamhi Daily, Senamhi Monthly, heatmap -->
            <!-- TODO: show this selection box when aggreation is Senamhi Daily / Senamhi Monthly / heatmap -->
            <tr>
                <td>Individual Variable</td>
                <td>
                    <select v-model="form.meteoIndividualVariable">
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

            <!-- meteo individual variables for time series, boxplot -->
            <!-- TODO: show this selection box when aggreation is time series / boxplot -->
            <!--
            <tr>
                <td>Variable Type</td>
                <td>
                    <select v-model="form.meteoVariableType">
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
            -->

        </table>

        <br />

        <!-- Criteria for agronomic data -->
        <!--
        <table width="500" border="1" cellpadding="5" cellspacing="2">
            <tr>
                <td colspan="2" bgcolor="lightgreen">
                    <b>Section 3. Agronomic Data</b>
                </td>
            </tr>

            <tr>
                <td width="200">Departamento *</td>
                <td>
                    <select
                        v-model="form.departamento"
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
                        v-model="form.municipio"
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
                    <select v-model="form.comunidad">
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
                        v-model="form.plotLevelSuelos"
                        id="plotLevelSuelosSelected"
                    />
                    <label for="plotLevelSuelosSelected">&nbsp;Suelos</label
                    ><br />
                    <input
                        type="checkbox"
                        v-model="form.plotLevelManejoDeLaParcela"
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
                        v-model="form.cropLevelFenologia"
                        id="cropLevelFenologiaSelected"
                    />
                    <label for="cropLevelFenologiaSelected"
                        >&nbsp;Fenologia</label
                    ><br />
                    <input
                        type="checkbox"
                        v-model="form.cropLevelPlagas"
                        id="cropLevelPlagasSelected"
                    />
                    <label for="cropLevelPlagasSelected">&nbsp;Plagas</label
                    ><br />
                    <input
                        type="checkbox"
                        v-model="form.cropLevelEnfermedades"
                        id="cropLevelEnfermedadesSelected"
                    />
                    <label for="cropLevelEnfermedadesSelected"
                        >&nbsp;Enfermedades</label
                    ><br />
                    <input
                        type="checkbox"
                        v-model="form.cropLevelRendimientos"
                        id="cropLevelRendimientosSelected"
                    />
                    <label for="cropLevelRendimientosSelected"
                        >&nbsp;Rendimientos</label
                    ><br />
                </td>
            </tr>
        </table>

        <br />
        -->
        

        <table width="500" border="0" cellpadding="5" cellspacing="2">
            <tr>
                <td align="center">
                    <input
                        type="button"
                        @click="showValues"
                        id="btnShowValues"
                        name="btnShowValues"
                        value="Show Values"
                    />
                    &nbsp;
                    <input
                        type="button"
                        @click="validateValues"
                        id="btnValidate"
                        name="btnValidate"
                        value="Validate"
                    />
                    &nbsp;
                    <input
                        type="button"
                        @click="assignValues"
                        id="btnAssign"
                        name="btnAssign"
                        value="Assign Values"
                    />
                    &nbsp;
                    <input
                        type="button"
                        @click="downloadData"
                        id="btnSubmit"
                        name="btnSubmit"
                        value="Submit"
                    />
                </td>
            </tr>
        </table>

        <br/>


        <!-- Criteria for additional graph -->
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

        <br/>


        <table width="500" border="0" cellpadding="5" cellspacing="2">
            <tr>
                <td align="center">
                    <input
                        type="button"
                        @click="showGraph"
                        id="btnSubmit"
                        name="btnSubmit"
                        value="Submit"
                    />
                </td>
            </tr>
        </table>

        <br/>


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
                    2010, 2011, 2012, 2013, 2014, 2015, 2016, 2017, 2018, 2019,
                    2020, 2021, 2022, 2023, 2024, 2025, 2026, 2027, 2028, 2029,
                    2030, 2031, 2032, 2033, 2034, 2035, 2036, 2037, 2038, 2039,
                    2040, 2041, 2042, 2043, 2044, 2045, 2046, 2047, 2048, 2049,
                    2050, 2051, 2052, 2053, 2054, 2055, 2056, 2057, 2058, 2059,
                    2060, 2061, 2062, 2063, 2064, 2065, 2066, 2067, 2068, 2069,
                    2070, 2071, 2072, 2073, 2074, 2075, 2076, 2077, 2078, 2079,
                    2080, 2081, 2082, 2083, 2084, 2085, 2086, 2087, 2088, 2089,
                    2090, 2091, 2092, 2093, 2094, 2095, 2096, 2097, 2098, 2099,
                ],

                // meteo individual variables for Senamhi Daily, Senamhi Monthly, heatmap
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
                        value: "lluvia_24_hors_total",
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
                        value: "lluvia_24_hors_total",
                    },
                ],

                // form variable for storing the selected value(s) of corresponding selection box
                form: {

                    // array for storing multiple values selected
                    stations: [],

                    // variable for storing single value selected
                    aggregation: "",
                    fromYear: "",
                    toYear: "",
                    meteoIndividualVariable: "",
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
                },

                graphForm: {
                    graphType: "",
                    station: "",
                    fromYear: "",
                    toYear: "",
                    meteoVariableType: "",
                },

            };
        },

        // custom methods section
        methods: {
            // TODO: to set corresponding flag to show related form element
            aggregationChange() {
                //alert("aggregationChange");
            },

            // comment temporary when "Agronomic Data" is hidden
            /*
            // to reset municipio and comunidad when departmento is changed
            // to be called when departmento value changed
            departamentoChanged() {
                //alert("departamentoChanged");

                // reset municipio and comunidad
                this.form.municipio = "";
                this.form.comunidad = "";

                // filter municipios that belong to selected departamento
                this.municipiosFiltered = this.municipios.filter(
                    this.checkMunicipio
                );

                // reset comunidadsFiltered as municipios is not selected yet
                this.comunidadsFiltered = [];
            },

            // to determine each municipio whether belongs to the selected departamento
            checkMunicipio(municipio) {
                return municipio.departamento_id == this.form.departamento;
            },

            // to reset comunidad when municipio is changed
            // to be called when municipio value changed
            municipioChanged() {
                //alert("municipioChanged");

                // reset comunidad
                this.form.comunidad = "";

                // filter municipios that belong to selected municipio
                this.comunidadsFiltered = this.comunidads.filter(
                    this.checkComunidad
                );
            },

            // to determine each comunidad item whether belongs to the selected municipio
            checkComunidad(comunidad) {
                return comunidad.municipio_id == this.form.municipio;
            },
            */

            // to show selected values for checking
            // to be called when "Show Values" button is clicked
            showValues() {
                //alert("showValues");

                var result = "";

                result += "Met station: " + this.form.stations + "\n";
                result += "Aggregation: " + this.form.aggregation + "\n";
                result +=
                    "From: " +
                    this.form.fromYear +
                    "\n";
                result +=
                    "To: " +
                    this.form.toYear +
                    "\n";
                result +=
                    "Individual Variable: " +
                    this.form.meteoIndividualVariable +
                    "\n";
                result += "Variable Type: " + this.form.meteoVariableType + "\n";

                /*
                result += "Departamento: " + this.form.departamento + "\n";
                result += "Municipio: " + this.form.municipio + "\n";
                result += "Comunidad: " + this.form.comunidad + "\n";
                result += "Suelos: " + this.form.plotLevelSuelos + "\n";
                result +=
                    "Manejo de la parcela: " +
                    this.form.plotLevelManejoDeLaParcela +
                    "\n";
                result += "Fenologia: " + this.form.cropLevelFenologia + "\n";
                result += "Plagas: " + this.form.cropLevelPlagas + "\n";
                result +=
                    "Enfermedades: " + this.form.cropLevelEnfermedades + "\n";
                result +=
                    "Rendimientos: " + this.form.cropLevelRendimientos + "\n";
                */

                alert(result);
            },

            validateValues() {
                //alert("validateValues");

                var result = true;

                // array empty or does not exist
                if (
                    this.form.stations === undefined ||
                    this.form.stations.length == 0
                ) {
                    result = false;
                    alert("Please select at least one met station");
                    return;
                }

                if (this.form.aggregation == "") {
                    result = false;
                    alert("Please select an aggregation");
                    return;
                }

                if (this.form.fromYear == "") {
                    result = false;
                    alert("Please select a From Year");
                    return;
                }

                if (this.form.toYear == "") {
                    result = false;
                    alert("Please select a To Year");
                    return;
                }

                if (
                    this.form.fromYear > this.form.toYear 
                ) {
                    result = false;
                    alert("Report duration 'From' should be earlier than 'To'");
                    return;
                }

                if (
                    this.form.aggregation == "senamhi_daily" ||
                    this.form.aggregation == "senamhi_monthly"
                    // || this.form.aggregation == "heatmap"
                ) {
                    if (this.form.meteoIndividualVariable == "") {
                        result = false;
                        alert("Please select an individual variable");
                        return;
                    }
                }

                /*
                if (
                    this.form.aggregation == "time_series" ||
                    this.form.aggregation == "boxplot"
                ) {
                    if (this.form.meteoVariableType == "") {
                        result = false;
                        alert("Please select a variable type");
                        return;
                    }
                }
                */

                /*
                if (this.form.departamento == "") {
                    result = false;
                    alert("Please select a departamento");
                    return;
                }

                if (this.form.municipio == "") {
                    result = false;
                    alert("Please select a municipio");
                    return;
                }

                if (this.form.comunidad == "") {
                    result = false;
                    alert("Please select a comunidad");
                    return;
                }

                // question: TBC, is plot level data a compulsory criteria?
                // plot level data, need to check at least one option
                if (
                    !this.form.plotLevelSuelos &&
                    !this.form.plotLevelManejoDeLaParcela
                ) {
                    result = false;
                    alert("Please select at least one plot level data");
                    return;
                }

                // question: TBC, is crop level data a compulsory criteria?
                // crop level data, need to check at least one option
                if (
                    !this.form.cropLevelFenologia &&
                    !this.form.cropLevelPlagas &&
                    !this.form.cropLevelEnfermedades &&
                    !this.form.cropLevelRendimientos
                ) {
                    result = false;
                    alert("Please select at least one crop level data");
                    return;
                }
                */

                // check validation result
                if (result) {
                    alert(
                        "All criteria are validated, can proceed to generate report"
                    );
                } else {
                    alert(
                        "Some criteria are not properly set, please correct them first"
                    );
                }
            },

            // to assign values to criteria to ease testing
            // to be called when "Assign Values" button is clicked
            assignValues() {
                this.form = {
                    stations: ["1", "2", "3", "6"],
                    aggregation: "daily_data",
                    fromYear: "2010",
                    toYear: "2020",
                    meteoIndividualVariable: "max_temperatura_interna",
                    meteoVariableType: "temperatura_interna",

                    /*
                    departamento: "1",
                    municipio: "1",
                    comunidad: "1",
                    plotLevelSuelos: "true",
                    plotLevelManejoDeLaParcela: "false",
                    cropLevelFenologia: "true",
                    cropLevelPlagas: "true",
                    cropLevelEnfermedades: "false",
                    cropLevelRendimientos: "false",
                    */
                }
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
                var reportUrl = "/data-download/download"

                // axios send request to generate excel file for download
                // TODO: how to keep the original generated file name?
                axios
                    .post(reportUrl, this.form, {
                        responseType: 'arraybuffer'
                    })
                    .then(response => {
                        console.log(response.data);

                        var fileExt = "xlsx";
                        var fileType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'

                        if(this.form.aggregation === "senamhi_monthly" || this.form.aggregation === "senamhi_daily") {
                            fileExt = "csv"
                            fileType = "text/csv"
                        }

                        // This code segment can trigger "Save As" dialog with a pre-defined file name
                        const blob = new Blob([response.data],
                                        { type: fileType }),
                                        link = document.createElement('a');

                        link.href = window.URL.createObjectURL(blob);


                        var aggregationLabel = this.aggregations.filter((item) => item.value === this.form.aggregation)[0].label;

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

            // to send request for showing a graph
            // to be called when "Submit" button in Additional graph section is clicked
            showGraph() {
                alert("showGraph");

                // TODO: send HTTP POST request to generate graph

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
