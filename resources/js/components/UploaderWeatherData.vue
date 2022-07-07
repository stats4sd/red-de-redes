<template>
    <div class="container">
        <progress-bar
            :current-step="currentStep"
            :steps="steps"
        />
        <div class="container my-4">
            <div class="accordion-area" role="tablist">
                <vue-collapsible-panel-group>
                    <vue-collapsible-panel :expanded="true">
                        <template #title>
                            <span ref="panel1">Paso 1: {{ steps[0].title }}</span>
                        </template>
                        <template #content>
                            <div class="py-4 mx-4">
                                <h3>Sube el archivo de datos</h3>
                                <p>Sube el archivo .csv o .txt que extrajo de la estación meteorológica. Asegúrese de
                                    subir
                                    el archivo de datos original sin editar.</p>
                                <div class="row mx-4 justify-content-center">
                                    <label class="control-label col-sm-6" style="color: black"><h5>Seleccione la
                                        estación</h5>
                                        <v-select @change="modalShow = !modalShow" :options="stations"
                                                  label="label_with_id"
                                                  v-model="selectedStation"></v-select>
                                    </label>
                                </div>
                                <div class="row mx-4 justify-content-center">
                                    <button @click="modalShow = !modalShow">Confirmar estación</button>

                                    <custom-modal v-model="modalShow" v-if="selectedStation" classes="w-50">
                                        <template #title>
                                            <h3>Confirmar estación</h3>
                                        </template>

                                        <p class="my-4"><b>Estación:</b> {{ selectedStation.label }}</p>
                                        <p class="my-4"><b>Latitud:</b> {{ selectedStation.latitude }}</p>
                                        <p class="my-4"><b>Longitud:</b> {{ selectedStation.longitude }}</p>
                                        <p class="my-4"><b>Altitud:</b> {{ selectedStation.altitude }}</p>
                                        <p class="my-4"><b>Tipo:</b> {{ selectedStation.type }}</p>

                                        <!--                                        &lt;!&ndash; add small map, met station photo, nearby village photo for visual identification &ndash;&gt;-->
                                        <!--                                        <table border="1" width="100%">-->
                                        <!--                                            <tr>-->
                                        <!--                                                <td width="33%"><b>Mapa</b></td>-->
                                        <!--                                                <td width="33%"><b>Estación</b></td>-->
                                        <!--                                                <td width="34%"><b>Pueblo cercano</b></td>-->
                                        <!--                                            </tr>-->
                                        <!--                                            <tr>-->
                                        <!--                                                <td><img :src="'images/met_station/'+selectedStation.id+'_map.jpg'"-->
                                        <!--                                                         width="150" height="100"></td>-->
                                        <!--                                                <td><img-->
                                        <!--                                                    :src="'images/met_station/'+selectedStation.id+'_met_station.jpg'"-->
                                        <!--                                                    width="150" height="100"></td>-->
                                        <!--                                                <td><img-->
                                        <!--                                                    :src="'images/met_station/'+selectedStation.id+'_nearby_village.jpg'"-->
                                        <!--                                                    width="150" height="100"></td>-->
                                        <!--                                            </tr>-->
                                        <!--                                        </table>-->

                                        <p class="my-4"><b>¿Está seguro de que {{ selectedStation.label }} es la
                                            estación correcta?</b></p>

                                        <!-- Action row -->
                                        <template #actions>
                                            <div class="btn btn-primary mr-2" @click="stationConfirmed">Sí</div>
                                            <div class="btn btn-primary ml-2" @click="stationNotConfirmed">No</div>
                                        </template>
                                    </custom-modal>
                                </div>

                                <div class="row mx-4 justify-content-center" v-show="showUploadFile">
                                    <label class="control-label col-sm-6" style="color: black"><h5>Seleccione el archivo
                                        de
                                        datos</h5>
                                        <file-input v-model="file" placeholder="Elija un archivo o suéltelo aquí..."/>
                                    </label>
                                </div>
                                <div class="row mx-4 justify-content-center" v-show="showUploadFile">
                                    <label class="control-label col-sm-6" style="color: black"><h5>Selccione el archivo
                                        con
                                        comentarios sobre los datos</h5>
                                        <file-input v-model="filesObservation"
                                                    placeholder="Elija un archivo o suéltelo aquí..."/>
                                    </label>
                                </div>
                                <h3>Seleccione las unidades</h3>
                                <p class="mt-3">Seleccione las unidades utilizadas en el archivo para los siguientes
                                    tipos
                                    de variables:</p>

                                <div class="row py-4 mx-4 justify-content-center">
                                    <label class="control-label col-sm-3" style="color: black"><h5>Temperatura</h5>
                                        <v-select v-model="selectedUnitTemp" :options="unitTemp"
                                                  :clearable="false"></v-select>
                                    </label>
                                    <label class="control-label col-sm-3" style="color: black"><h5>Presión</h5>
                                        <v-select v-model="selectedUnitPres" :options="unitPres"
                                                  :clearable="false"></v-select>
                                    </label>
                                    <label class="control-label col-sm-3" style="color: black"><h5>Velocidad del
                                        viento</h5>
                                        <v-select v-model="selectedUnitWind" :options="unitWind"
                                                  :clearable="false"></v-select>
                                    </label>
                                    <label class="control-label col-sm-3" style="color: black"><h5>Precipitación</h5>
                                        <v-select v-model="selectedUnitRain" :options="unitRain"
                                                  :clearable="false"></v-select>
                                    </label>
                                </div>

                                <div style="text-align: center;">
                                    <div class="alert alert-info show">Después de subir el archivo, tendrá la oportunidad de revisar los valores de los datos y confirmar que estas son las unidades correctas antes de continuar.
                                    </div>
                                    <br/>
                                    <div class="alert alert-danger show" v-if="uploadError!=null">{{ uploadError }}
                                    </div>
                                    <br/>
                                    <button class="site-btn my-4" v-on:click="submit();" :disabled="busy">
                                        <i class="la la-spinner" v-if="busy" label="Spinning"></i> Subir
                                    </button>

                                    <div
                                        v-if="uploadActive || progress === 100"
                                        class="alert show"
                                        :class="success ? 'alert-success' : (error ? 'alert-danger' : 'alert-info')"
                                        >
                                        PROGRESS: {{ current_row }} / {{ total_rows }} ({{ progress }} %)
                                    </div>
                                    <div class="alert alert-danger show" v-if="error">{{ error }}</div>

                                </div>
                            </div>
                        </template>
                    </vue-collapsible-panel>
                    <vue-collapsible-panel :expanded="false">
                        <template #title>
                            <span ref="panel2">Paso 2: {{ steps[1].title }}</span>
                        </template>

                        <template #content>
                            <div class="py-4 mx-4">
                                <h3>Vista preliminar de datos</h3>
                                <p class="mt-3">Este es un ejemplo de sus datos</p>
                                <div class="alert alert-success show">Verifique que las columnas que espera llenar
                                    contengan datos
                                    y que los valores parezcan adecuados para la ubicación seleccionada, la época del
                                    año y
                                    las unidades elegidas.
                                </div>
                                <div class="alert alert-secondary" v-if="previewData!=null">
                                    <ul>
                                        <li>Hay {{ total_rows }} filas</li>
                                        <li>Minumum temperature from records:<b> {{ min_temp }} ºC</b></li>
                                        <li>Maximum temperature from records:<b> {{ max_temp }} ºC</b></li>
                                        <li>Maximum daily rainfall from records:<b> {{ max_daily_rain }} mm</b></li>
                                    </ul>
                                </div>

                                <h5>Preview</h5>
                                <p>Below is a preview of the data that will be stored. It includes the first 10 records from the uploaded file.</p>
                                <div class="mx-4 justify-content-center" style="overflow-x: scroll">

                                    <table class="table table-striped" style="max-height: 600px;">
                                        <tr>
                                            <th>fecha</th>
                                            <th>time</th>
                                            <th>temperatura_interna</th>
                                            <th>temperatura_externa</th>
                                            <th>sensacion_termica</th>
                                            <th>punto_rocio</th>
                                            <th>wind_chill</th>
                                            <th>hi_temp</th>
                                            <th>low_temp</th>
                                            <th>presion_relativa</th>
                                            <th>presion_absoluta</th>
                                            <th>velocidad_viento</th>
                                            <th>rafaga</th>
                                            <th>hi_speed</th>
                                            <th>hi_dir</th>
                                            <th>lluvia_hora</th>
                                            <th>lluvia_24_horas</th>
                                            <th>lluvia_semana</th>
                                            <th>lluvia_mes</th>
                                            <th>lluvia_total</th>
                                            <th>rain</th>
                                        </tr>
                                        <tr v-for="row in previewData" :key="row.id">

                                            <td>{{ row.fecha_hora.substring(0, 10) }}</td>
                                            <td>{{ row.fecha_hora.substring(10) }}</td>

                                            <td>{{ row.temperatura_interna }} {{
                                                    row.temperatura_interna && row.temperatura_interna != '' ? 'ºC' : ''
                                                }}
                                            </td>
                                            <td>{{ row.temperatura_externa }} {{
                                                    row.temperatura_externa && row.temperatura_externa != '' ? 'ºC' : ''
                                                }}
                                            </td>
                                            <td>{{ row.sensacion_termica }}
                                                {{ row.sensacion_termica && row.sensacion_termica != '' ? 'ºC' : '' }}
                                            </td>
                                            <td>{{ row.punto_rocio }}
                                                {{ row.punto_rocio && row.punto_rocio != '' ? 'ºC' : '' }}
                                            </td>
                                            <td>{{ row.wind_chill }}
                                                {{ row.wind_chill && row.wind_chill != '' ? 'ºC' : '' }}
                                            </td>
                                            <td>{{ row.hi_temp }} {{
                                                    row.hi_temp && row.hi_temp != '' ? 'ºC' : ''
                                                }}
                                            </td>
                                            <td>{{ row.low_temp }} {{
                                                    row.low_temp && row.low_temp != '' ? 'ºC' : ''
                                                }}
                                            </td>
                                            <td>{{ row.presion_relativa }}
                                                {{ row.presion_relativa && row.presion_relativa != '' ? 'hPa' : '' }}
                                            </td>
                                            <td>{{ row.presion_absoluta }}
                                                {{ row.presion_absoluta && row.presion_absoluta != '' ? 'hPa' : '' }}
                                            </td>
                                            <td>{{ row.velocidad_viento }}
                                                {{ row.velocidad_viento && row.velocidad_viento != '' ? 'm/s' : '' }}
                                            </td>
                                            <td>{{ row.rafaga }} {{ row.rafaga && row.rafaga != '' ? 'm/s' : '' }}</td>
                                            <td>{{ row.hi_speed }} {{
                                                    row.hi_speed && row.hi_speed != '' ? 'm/s' : ''
                                                }}
                                            </td>
                                            <td>{{ row.hi_dir }}</td>
                                            <td>{{ row.lluvia_hora }}
                                                {{ row.lluvia_hora && row.lluvia_hora != '' ? 'mm' : '' }}
                                            </td>
                                            <td>{{ row.lluvia_24_horas }}
                                                {{ row.lluvia_24_horas && row.lluvia_24_horas != '' ? 'mm' : '' }}
                                            </td>
                                            <td>{{ row.lluvia_semana }}
                                                {{ row.lluvia_semana && row.lluvia_semana != '' ? 'mm' : '' }}
                                            </td>
                                            <td>{{ row.lluvia_mes }}
                                                {{ row.lluvia_mes && row.lluvia_mes != '' ? 'mm' : '' }}
                                            </td>
                                            <td>{{ row.lluvia_total }}
                                                {{ row.lluvia_total && row.lluvia_total != '' ? 'mm' : '' }}
                                            </td>
                                            <td>{{ row.rain }} {{ row.rain && row.rain != '' ? 'mm' : '' }}</td>
                                        </tr>

                                    </table>

                                </div>


                                <div style="text-align: center;">
                                    <div class="alert alert-danger show" v-if="error!=null">{{ error }}</div>
                                    <div class="alert alert-success show" v-if="success!=null">{{ success }}</div>
                                    <div class="alert alert-warning show" v-if="scenario3">
                                        <input type="checkbox" v-model="scenario3Confirmed">
                                        <b>I confirm that I understand the potential risk of uploading this
                                            data file with existing records.</b></div>
                                    <br/>

                                    <div class="d-flex justify-content-center">

                                        <form :action="'/cancel-upload/'+ upload_id" method="POST">
                                            <input type="hidden" name="_token" :value="csrf"/>
                                            <button class="btn btn-danger my-4" type="submit">
                                                Cancelar
                                            </button>

                                        </form>
                                        &nbsp;
                                        <form :action="'/store-file/'+ upload_id" method="POST">
                                            <input type="hidden" name="_token" :value="csrf"/>
                                            <button class="btn btn-success my-4" type="submit">
                                                Guardar en la base de datos
                                            </button>

                                        </form>
                                    </div>

                                </div>
                            </div>
                        </template>
                    </vue-collapsible-panel>
                </vue-collapsible-panel-group>
            </div>
        </div>
    </div>
</template>

<script>

import _ from "lodash";

const rootUrl = process.env.MIX_APP_URL
import {
    VueCollapsiblePanelGroup,
    VueCollapsiblePanel,
} from '@dafcoe/vue-collapsible-panel'

import '@dafcoe/vue-collapsible-panel/dist/vue-collapsible-panel.css'

import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'

import CustomModal from './Elements/CustomModal.vue'
import FileInput from 'vue3-simple-file-input'
import ProgressBar from './ProgressBar'
import Noty from 'noty'
import axios from 'axios'

Noty.overrideDefaults({
    theme: 'bootstrap-v4',
})

export default {
    components: {
        VueCollapsiblePanelGroup,
        VueCollapsiblePanel,
        vSelect,
        CustomModal,
        FileInput,
        ProgressBar
    },
    data() {
        this.trackProgress = _.debounce(this.trackProgress, 1000);
        return {
            max_temp: null,
            min_temp: null,
            max_daily_rain: null,
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            current_row: null,
            progress: null,
            total_rows: null,
            uploadActive: false,
            currentStep: 1,
            steps: [
                {
                    'id': 1,
                    'title': "Subir el archivo de datos",
                },
                {
                    'id': 2,
                    'title': "Comprobar unidades y guardar datos",
                }
            ],
            // after upgrading from Vue 2 to Vue 3
            // 1. need to use attribute "label" instead of attribute "text"
            unitTemp: [
                {value: 'ºC', label: 'Celsius (ºC)'},
                {value: 'ºF', label: 'Farhenheit (ºF)'}
            ],
            unitPres: [
                {value: 'hpa', label: 'hPa'},
                {value: 'inhg', label: 'inhg'},
                {value: 'mmhg', label: 'mmhg'}
            ],
            unitWind: [
                {value: 'm/s', label: 'm/s'},
                {value: 'km/h', label: 'km/h'},
                {value: 'mph', label: 'mph'}
            ],
            unitRain: [
                {value: 'mm', label: 'mm'},
                {value: 'inch', label: 'inch'}
            ],
            stations: [],
            selectedStation: null,
            // after upgrading from Vue 2 to Vue 3
            // 1. default value has been set as selection box initial selected option, but it has not been assigned to v-model.value
            selectedUnitTemp: 'ºC',
            selectedUnitPres: 'hpa',
            selectedUnitWind: 'm/s',
            selectedUnitRain: 'mm',
            file: null,
            filesObservation: null,
            previewData: null,
            busy: false,
            error_data: null,
            success: null,
            error: null,
            error_temp: false,
            error_press: false,
            error_wind: false,
            error_rain: false,
            uploadError: null,
            upload_id: null,
            scenario3: false,
            scenario3Confirmed: false,
            showUploadFile: false,
            modalShow: false,
            fields: [
                {key: "fecha", stickyColumn: true, label: 'Date', thStyle: {width: '200px'}},
                {key: "time", label: 'Time', thStyle: {width: '200px'}},
                {key: "temperatura_externa", label: 'Temp Out', thStyle: {width: '100px'}},
                {key: "sensacion_termica", label: 'sensacion_termica', thStyle: {width: '100px'}},
                {key: "hi_temp", label: 'Hi Temp', thStyle: {width: '200px'}},
                {key: "low_temp", label: 'Low Temp', thStyle: {width: '100px'}},
                {key: "humedad_externa", label: 'Out Hum', thStyle: {width: '200px'}},
                {key: "punto_rocio", label: 'Dew Pt.', thStyle: {width: '100px'}},
                {key: "velocidad_viento", label: 'Wind Speed', thStyle: {width: '200px'}},
                {key: "direccion_del_viento", label: 'Wind Dir', thStyle: {width: '100px'}},
                {key: "wind_run", label: 'Wind Run', thStyle: {width: '200px'}},
                {key: "hi_speed", label: 'Hi Speed', thStyle: {width: '100px'}},
                {key: "hi_dir", label: 'hi_dir', thStyle: {width: '100px'}},
                {key: "wind_chill", label: 'Wind Chill', thStyle: {width: '200px'}},
                {key: "index_heat", label: 'Heat Index', thStyle: {width: '100px'}},
                {key: "index_thw", label: 'THW Index', thStyle: {width: '100px'}},
                {key: "index_thsw", label: 'THSW Index', thStyle: {width: '100px'}},
                {key: "presion_relativa", label: 'presion_relativa', thStyle: {width: '100px'}},
                {key: "presion_absoluta", label: 'presion_absoluta', thStyle: {width: '100px'}},
                {key: "rafaga", label: 'rafaga', thStyle: {width: '100px'}},
                {key: "rain", label: 'Rain', thStyle: {width: '100px'}},
                {key: "lluvia_hora", label: 'Rain Rate', thStyle: {width: '100px'}},
                {key: "lluvia_24_horas", label: 'lluvia_24_horas', thStyle: {width: '100px'}},
                {key: "lluvia_semana", label: 'lluvia_semana', thStyle: {width: '100px'}},
                {key: "lluvia_mes", label: 'lluvia_mes', thStyle: {width: '100px'}},
                {key: "lluvia_total", label: 'lluvia_total', thStyle: {width: '100px'}},
                {key: "solar_rad", label: 'Solar Rad.', thStyle: {width: '100px'}},
                {key: "solar_energy", label: 'Solar Energy', thStyle: {width: '100px'}},
                {key: "radsolar_max", label: 'Hi Solar Rad.', thStyle: {width: '100px'}},
                {key: "uv_index", label: 'UV Index', thStyle: {width: '100px'}},
                {key: "uv_dose", label: 'UV Dose', thStyle: {width: '100px'}},
                {key: "uv_max", label: 'Hi UV', thStyle: {width: '100px'}},
                {key: "heat_days_d", label: 'Heat D-D', thStyle: {width: '100px'}},
                {key: "cool_days_d", label: 'Cool_D-D', thStyle: {width: '100px'}},
                {key: "temperatura_interna", label: 'In Temp', thStyle: {width: '100px'}},
                {key: "humedad_interna", label: 'In Hum', thStyle: {width: '100px'}},
                {key: "in_dew", label: 'In Dew', thStyle: {width: '100px'}},
                {key: "in_heat", label: 'In Heat', thStyle: {width: '100px'}},
                {key: "in_emc", label: 'In EMC', thStyle: {width: '100px'}},
                {key: "in_air_density", label: 'In Air Density', thStyle: {width: '100px'}},
                {key: "evapotran", label: 'ET', thStyle: {width: '100px'}},
                {key: "soil_1_moist", label: 'Soil 1 Moist.', thStyle: {width: '100px'}},
                {key: "soil_2_moist", label: 'Soil 2 Moist.', thStyle: {width: '100px'}},
                {key: "soil_temp_1", label: 'Soil Temp 1.', thStyle: {width: '100px'}},
                {key: "soil_temp_2", label: 'Soil Temp 2.', thStyle: {width: '100px'}},
                {key: "soil_temp_3", label: 'Soil Temp 3.', thStyle: {width: '100px'}},
                {key: "leaf_wet1", label: 'Leaf Wet 1', thStyle: {width: '100px'}},
                {key: "leaf_wet2", label: 'Leaf Wet 2', thStyle: {width: '100px'}},
                {key: "leaf_wet3", label: 'Leaf Wet 3', thStyle: {width: '100px'}},
                {key: "leaf_temp_1", label: 'leaf_temp_1', thStyle: {width: '100px'}},
                {key: "wind_samp", label: 'Wind Samp', thStyle: {width: '100px'}},
                {key: "wind_tx", label: 'Wind Tx', thStyle: {width: '100px'}},
                {key: "iss_recept", label: 'ISS Recept', thStyle: {width: '100px'}},
                {key: "intervalo", label: 'Arc. Int.', thStyle: {width: '100px'}},
            ],
        }
    },
    props: {
        bgColor: {
            type: String,
            default: 'red'
        },
        userId: {
            type: Number,
            default: null,
        },
    },
    mounted() {

        this.setupEchoListeners()

        axios.get('api/stations').then((response) => {
            this.stations = response.data;
        })
    },
    computed: {
        tempIntBackColor() {
            return {
                "color": this.bgColor,
            };
        },
        visible1: {
            get: function () {
                return this.currentStep === 1
            },
            set: function (newValue) {
                if (newValue) this.currentStep = 1
            }
        },
        visible2: {
            get: function () {
                return this.currentStep === 2
            },
            set: function (newValue) {
                if (newValue) this.currentStep = 2
            }
        },
        visible3: {
            get: function () {
                return this.currentStep === 3
            },
            set: function (newValue) {
                if (newValue) this.currentStep = 3
            }
        },
    },


    methods: {
        submit: function (event) {

            //check form for errors
            this.uploadError = null;

            this.busy = true;
            let formData = new FormData();
            formData.append('station_id', this.selectedStation.id);
            formData.append('data_file', this.file.file);
            if (this.filesObservation) {
                formData.append('observation_file', this.filesObservation);
            }

            // after upgrading from Vue 2 to Vue 3:
            // 1. needs to pass v-model.value instead of passing v-model
            // 2. default value not assigned, add default value as parameter value if user has not selected an option in selection box
            formData.append('selectedUnitTemp', this.selectedUnitTemp.value ?? 'ºC');
            formData.append('selectedUnitPres', this.selectedUnitPres.value ?? 'hpa');
            formData.append('selectedUnitWind', this.selectedUnitWind.value ?? 'm/s');
            formData.append('selectedUnitRain', this.selectedUnitRain.value ?? 'mm');

            axios.post(rootUrl + '/files', formData, {}).then((result) => {
                console.log('done');
            })
                .catch((error) => {
                    this.busy = false;
                    console.log(error);
                    if (error.response && error.response.hasOwnProperty('data')) {

                        this.uploadError = Object.keys(error.response.data.errors).map((key) => error.response.data.errors[key]).join('; ');

                    new Noty({
                        type: "error",
                        text: "There were errors importing the file - see below for details. Please check that the file is correctly formatted",
                        timeout: false,
                    }).show();


                    } else {
                        this.uploadError = "No se pudo subir el archivo. Verifique que esté en el formato correcto o póngase en contacto con el administrador de la plataforma para obtener más información.";
                    }

                })
                .then(() => {
                    this.busy = false;
                })


        },

        storeFile: function () {
            // this.busy = true;
            axios({
                method: 'post',
                url: "/store-file/" + this.upload_id,
            })
                .then((result) => {
                    console.log(result.data.success);
                    this.success = result.data.success
                    this.error = result.data.error
                    this.busy = false;

                    // after moving staging records from table "data_preview" to table "data"
                    // redirect to a upload success static page
                    // window.location.assign('/uploadsuccess')

                }, (error) => {
                    console.log(error);
                    this.error = error
                    this.busy = false;
                });
        },

        stationConfirmed() {
            this.showUploadFile = true
            this.modalShow = false
        },

        stationNotConfirmed() {
            this.modalShow = false
            this.selectedStation = false
            this.showUploadFile = false
        },

        setupEchoListeners() {
            window.Echo
                .private("App.Models.User." + this.userId)
                .listen("HelloWorld", payload => {
                    new Noty({
                        type: "info",
                        text:
                            "<b>Hi</bi>",
                        timeout: false
                    }).show();
                })
                .listen("MetDataImportStarted", payload => {
                    new Noty({
                        type: "info",
                        text: `The import has started. All ${payload.file.total_records_count} entries will be processed.`
                    }).show()

                    this.upload_id = payload.file.upload_id;
                    this.total_rows = payload.file.total_records_count
                    this.uploadActive = true;
                    console.log('upload_ID', this.upload_id)
                    this.trackProgress()
                })
                .listen("MetDataImportCompleted", payload => {

                    if(!payload.success) {
                        this.uploadActive = false;
                        this.success = null;
                        this.error = payload.data.error;
                        return;
                    }

                    new Noty({
                        type: "success",
                        text: `The import is complete!.`
                    }).show()

                    this.trackProgress()
                    this.uploadActive = false;
                    this.total_rows = payload.data.met_data_preview.total;
                    this.previewData = payload.data.met_data_preview.data;
                    this.upload_id = (this.previewData[0]['upload_id']);
                    this.min_temp = payload.data.min_temp;
                    this.max_temp = payload.data.max_temp;
                    this.max_daily_rain = payload.data.max_daily_rain;

                    // show advice message
                    this.error = null;
                    this.success = payload.data.adviceMessage;
                    this.scenario3 = payload.data.scenario === 3;

                    this.$refs.panel1.click()
                    this.$refs.panel2.click()

                })
                .listen("MetDataImportFailed", payload => {
                    console.log('payload event', payload)

                    this.error += '<br/>' + payload.failures.map(failure => {

                        return '<b>' + failure.attribute + '</b> ' + failure.errors.join('; ');
                    }).join(';<br> ')



                })

        },
        async trackProgress() {
            const {data} = await axios.get(`/import-status/${this.upload_id}`)

            if (data.finished) {
                this.current_row = this.total_rows
                this.progress = 100
                return;
            }

            this.current_row = data.current_row;
            this.progress = Math.ceil(this.current_row / this.total_rows * 100);

            //  continue until finished
            this.trackProgress();
        }
    }
}


</script>
