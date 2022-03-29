<template>
    <div class="container">
        <progress-bar
            :current-step="currentStep"
            :steps="steps"
        />
        <div class="container my-4">
            <div class="accordion-area" role="tablist">
                <vue-collapsible-panel-group>
                    <vue-collapsible-panel  :expanded="true">
                        <template #title>
                            <span ref="panel1">Paso 1: {{ steps[0].title }}</span>
                        </template>
                        <template #content>
                            <div class="py-4 mx-4">
                                <h3>Sube el archivo de datos</h3>
                                <p>Sube el archivo .csv o .txt que extrajo de la estación meteorológica. Asegúrese de subir
                                    el archivo de datos original sin editar.</p>
                                <div class="row mx-4 justify-content-center">
                                    <label class="control-label col-sm-6" style="color: black"><h5>Seleccione la
                                        estación</h5>
                                        <v-select @change="modalShow = !modalShow" :options="stations" label="label_with_id"
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

                                        <!-- add small map, met station photo, nearby village photo for visual identification -->
                                        <table border="1" width="100%">
                                            <tr>
                                                <td width="33%"><b>Map</b></td>
                                                <td width="33%"><b>Met Station</b></td>
                                                <td width="34%"><b>Nearby Village</b></td>
                                            </tr>
                                            <tr>
                                                <td><img :src="'images/met_station/'+selectedStation.id+'_map.jpg'" width="150" height="100"></td>
                                                <td><img :src="'images/met_station/'+selectedStation.id+'_met_station.jpg'" width="150" height="100"></td>
                                                <td><img :src="'images/met_station/'+selectedStation.id+'_nearby_village.jpg'" width="150" height="100"></td>
                                            </tr>
                                        </table>

                                        <p class="my-4"><b>¿Está seguro de que {{ selectedStation.label }} es la estación
                                            correcta?</b></p>

                                        <!-- Action row -->
                                        <template #actions>
                                            <div class="btn btn-primary mr-2" @click="stationConfirmed">Sí</div>
                                            <div class="btn btn-primary ml-2" @click="stationNotConfirmed">No</div>
                                        </template>
                                    </custom-modal>
                                </div>

                                <div class="row mx-4 justify-content-center" v-show="showUploadFile">
                                    <label class="control-label col-sm-6" style="color: black"><h5>Seleccione el archivo de
                                        datos</h5>
                                        <file-input v-model="file" placeholder="Elija un archivo o suéltelo aquí..."/>
                                    </label>
                                </div>
                                <div class="row mx-4 justify-content-center" v-show="showUploadFile">
                                    <label class="control-label col-sm-6" style="color: black"><h5>Selccione el archivo con
                                        comentarios sobre los datos</h5>
                                        <file-input v-model="filesObservation" placeholder="Elija un archivo o suéltelo aquí..."/>
                                    </label>
                                </div>
                                <h3>Seleccione las unidades</h3>
                                <p class="mt-3">Seleccione las unidades utilizadas en el archivo para los siguientes tipos
                                    de variables:</p>

                                <div class="row py-4 mx-4 justify-content-center">
                                    <label class="control-label col-sm-3" style="color: black"><h5>Temperatura</h5>
                                        <v-select v-model="selectedUnitTemp" :options="unitTemp"></v-select>
                                    </label>
                                    <label class="control-label col-sm-3" style="color: black"><h5>Presión</h5>
                                        <v-select v-model="selectedUnitPres" :options="unitPres"></v-select>
                                    </label>
                                    <label class="control-label col-sm-3" style="color: black"><h5>Velocidad del viento</h5>
                                        <v-select v-model="selectedUnitWind" :options="unitWind"></v-select>
                                    </label>
                                    <label class="control-label col-sm-3" style="color: black"><h5>Precipitación</h5>
                                        <v-select v-model="selectedUnitRain" :options="unitRain"></v-select>
                                    </label>
                                </div>

                                <div style="text-align: center;">
                                    <b-alert show varient="info">Después de subir el archivo, tendrá la oportunidad de
                                        revisar los valores de los datos y confirmar que estas son las unidades correctas
                                        antes de continuar.
                                    </b-alert>
                                    <b-alert show variant="danger" v-if="uploadError!=null">{{ uploadError }}</b-alert>
                                    <br/>
                                    <button class="site-btn my-4" v-on:click="submit();" :disabled="busy">
                                        <b-spinner small v-if="busy" label="Spinning"></b-spinner>
                                        Subir
                                    </button>
                                </div>
                            </div>
                        </template>
                    </vue-collapsible-panel>
                    <vue-collapsible-panel :expanded="false">
                        <template #title>
                            <span ref="panel2">Paso 2: {{ steps[1].title }}</span>
                        </template>

                        <template #content >
                            <div class="py-4 mx-4">
                                <h3>Vista preliminar de datos</h3>
                                <p class="mt-3">Este es un ejemplo de sus datos</p>
                                <div class="alert alert-success show">Verifique que las columnas que espera llenar contengan datos
                                    y que los valores parezcan adecuados para la ubicación seleccionada, la época del año y
                                    las unidades elegidas.
                                </div>
                                <div class="alert alert-secondary" v-if="previewData!=null">Hay {{ total_rows }} filas</div>

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

                                            <td>{{ row.fecha_hora.substring(0, 10) }} </td>
                                            <td>{{ row.fecha_hora.substring(10) }} </td>

                                            <td>{{ row.temperatura_interna }} {{ row.temperatura_interna && row.temperatura_interna != '' ? 'ºC' : '' }}</td>
                                            <td>{{ row.temperatura_externa }} {{ row.temperatura_externa && row.temperatura_externa != '' ? 'ºC' : '' }}</td>
                                            <td>{{ row.sensacion_termica }} {{ row.sensacion_termica && row.sensacion_termica != '' ? 'ºC' : '' }}</td>
                                            <td>{{ row.punto_rocio }} {{ row.punto_rocio && row.punto_rocio != '' ? 'ºC' : '' }}</td>
                                            <td>{{ row.wind_chill }} {{ row.wind_chill && row.wind_chill != '' ? 'ºC' : '' }}</td>
                                            <td>{{ row.hi_temp }} {{ row.hi_temp && row.hi_temp != '' ? 'ºC' : '' }}</td>
                                            <td>{{ row.low_temp }} {{ row.low_temp && row.low_temp != '' ? 'ºC' : '' }}</td>
                                            <td>{{ row.presion_relativa }} {{ row.presion_relativa && row.presion_relativa != '' ? 'hPa' : '' }}</td>
                                            <td>{{ row.presion_absoluta }} {{ row.presion_absoluta && row.presion_absoluta != '' ? 'hPa' : '' }}</td>
                                            <td>{{ row.velocidad_viento }} {{ row.velocidad_viento && row.velocidad_viento != '' ? 'm/s' : '' }}</td>
                                            <td>{{ row.rafaga }} {{ row.rafaga && row.rafaga != '' ? 'm/s' : '' }}</td>
                                            <td>{{ row.hi_speed }} {{ row.hi_speed && row.hi_speed != '' ? 'm/s' : '' }}</td>
                                            <td>{{ row.hi_dir }} </td>
                                            <td>{{ row.lluvia_hora }} {{ row.lluvia_hora && row.lluvia_hora != '' ? 'mm' : '' }}</td>
                                            <td>{{ row.lluvia_24_horas }} {{ row.lluvia_24_horas && row.lluvia_24_horas != '' ? 'mm' : '' }}</td>
                                            <td>{{ row.lluvia_semana }} {{ row.lluvia_semana && row.lluvia_semana != '' ? 'mm' : '' }}</td>
                                            <td>{{ row.lluvia_mes }} {{ row.lluvia_mes && row.lluvia_mes != '' ? 'mm' : '' }}</td>
                                            <td>{{ row.lluvia_total }} {{ row.lluvia_total && row.lluvia_total != '' ? 'mm' : '' }}</td>
                                            <td>{{ row.rain }} {{ row.rain && row.rain != '' ? 'mm' : '' }}</td>
                                        </tr>

                                    </table>

                                </div>

                                <div class="py-4 mx-4 justify-content-center" style="overflow-x: scroll" v-if="error_data!=null">
                                    <div class="alert alert-danger"
                                            v-if="error_temp || error_press || error_wind||error_rain ">Hay algunos valores
                                        con las unidades incorrectas, consulte la siguiente tabla y continúe con
                                        <b>Cancelar</b> para subir un nuevo archivo o haga clic en <b>Guardar en la base de
                                            datos</b> si los valores son correctos.
                                    </div>

                                    <table class="table table-striped">
                                        <tr>
                                            <th v-if="error_temp">temperatura_interna</th>
                                            <th v-if="error_temp">temperatura_externa</th>
                                            <th v-if="error_temp">sensacion_termica</th>
                                            <th v-if="error_temp">punto_rocio</th>
                                            <th v-if="error_temp">wind_chill</th>
                                            <th v-if="error_temp">hi_temp</th>
                                            <th v-if="error_temp">low_temp</th>
                                            <th v-if="error_press">presion_relativa</th>
                                            <th v-if="error_press">presion_absoluta</th>
                                            <th v-if="error_wind">velocidad_viento</th>
                                            <th v-if="error_wind">rafaga</th>
                                            <th v-if="error_wind">hi_speed</th>
                                            <th v-if="error_wind">hi_dir</th>
                                            <th v-if="error_rain">lluvia_hora</th>
                                            <th v-if="error_rain">lluvia_24_horas</th>
                                            <th v-if="error_rain">lluvia_semana</th>
                                            <th v-if="error_rain">lluvia_mes</th>
                                            <th v-if="error_rain">lluvia_total</th>
                                            <th v-if="error_rain">rain</th>
                                        </tr>
                                        <tr v-for="row in error_data" :key="row.id">
                                            <td v-if="error_temp">{{ row.temperatura_interna }} {{ row.temperatura_interna && row.temperatura_interna != '' ? 'ºC' : '' }}</td>
                                            <td v-if="error_temp">{{ row.temperatura_externa }} {{ row.temperatura_externa && row.temperatura_externa != '' ? 'ºC' : '' }}</td>
                                            <td v-if="error_temp">{{ row.sensacion_termica }} {{ row.sensacion_termica && row.sensacion_termica != '' ? 'ºC' : '' }}</td>
                                            <td v-if="error_temp">{{ row.punto_rocio }} {{ row.punto_rocio && row.punto_rocio != '' ? 'ºC' : '' }}</td>
                                            <td v-if="error_temp">{{ row.wind_chill }} {{ row.wind_chill && row.wind_chill != '' ? 'ºC' : '' }}</td>
                                            <td v-if="error_temp">{{ row.hi_temp }} {{ row.hi_temp && row.hi_temp != '' ? 'ºC' : '' }}</td>
                                            <td v-if="error_temp">{{ row.low_temp }} {{ row.low_temp && row.low_temp != '' ? 'ºC' : '' }}</td>
                                            <td v-if="error_press">{{ row.presion_relativa }} {{ row.presion_relativa && row.presion_relativa != '' ? 'hPa' : '' }}</td>
                                            <td v-if="error_press">{{ row.presion_absoluta }} {{ row.presion_absoluta && row.presion_absoluta != '' ? 'hPa' : '' }}</td>
                                            <td v-if="error_wind">{{ row.velocidad_viento }} {{ row.velocidad_viento && row.velocidad_viento != '' ? 'm/s' : '' }}</td>
                                            <td v-if="error_wind">{{ row.rafaga }} {{ row.rafaga && row.rafaga != '' ? 'm/s' : '' }}</td>
                                            <td v-if="error_wind">{{ row.hi_speed }} {{ row.hi_speed && row.hi_speed != '' ? 'm/s' : '' }}</td>
                                            <td v-if="error_wind">{{ row.hi_dir }} </td>
                                            <td v-if="error_rain">{{ row.lluvia_hora }} {{ row.lluvia_hora && row.lluvia_hora != '' ? 'mm' : '' }}</td>
                                            <td v-if="error_rain">{{ row.lluvia_24_horas }} {{ row.lluvia_24_horas && row.lluvia_24_horas != '' ? 'mm' : '' }}</td>
                                            <td v-if="error_rain">{{ row.lluvia_semana }} {{ row.lluvia_semana && row.lluvia_semana != '' ? 'mm' : '' }}</td>
                                            <td v-if="error_rain">{{ row.lluvia_mes }} {{ row.lluvia_mes && row.lluvia_mes != '' ? 'mm' : '' }}</td>
                                            <td v-if="error_rain">{{ row.lluvia_total }} {{ row.lluvia_total && row.lluvia_total != '' ? 'mm' : '' }}</td>
                                            <td v-if="error_rain">{{ row.rain }} {{ row.rain && row.rain != '' ? 'mm' : '' }}</td>
                                        </tr>

                                    </table>

                                </div>


                                <div style="text-align: center;">
                                    <b-alert show variant="danger" v-if="error!=null">{{ error }}</b-alert>
                                    <b-alert show variant="success" v-if="success!=null">{{ success }}</b-alert>
                                    <b-alert show variant="warning" v-if="scenario3"><input type="checkbox" v-model="scenario3Confirmed"><b><font color="red"> I confirm that I understand the potential risk of uploading this data file with existing records.</font></b></b-alert>
                                    <br/>
                                    <button class="site-btn my-4" data-toggle="collapse" href="#collapseThree"
                                            aria-expanded="false" aria-controls="collapseThree" v-on:click="cleanTable"
                                            style="background: red;">
                                        <b-spinner small v-if="busy" label="Spinning"></b-spinner>
                                        Cancelar
                                    </button>
                                    &nbsp;
                                    <button type="submit" class="site-btn my-4" data-toggle="collapse"
                                            href="#collapseThree" id="btnConfirm"
                                            aria-expanded="false" aria-controls="collapseThree" v-on:click="storeFile"
                                            :disabled="error || busy || (scenario3 && !scenario3Confirmed)">
                                        <b-spinner small v-if="busy" label="Spinning"></b-spinner>
                                        Guardar en la base de datos
                                    </button>

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

export default {
    components: {
        VueCollapsiblePanelGroup,
        VueCollapsiblePanel,
        vSelect,
        CustomModal,
        FileInput,
    },
    data() {
        return {
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
            unitTemp: [
                {value: 'ºC', text: 'Celsius (ºC)'},
                {value: 'ºF', text: 'Farhenheit (ºF)'}
            ],
            unitPres: [
                {value: 'hpa', text: 'hPa'},
                {value: 'inhg', text: 'inhg'},
                {value: 'mmhg', text: 'mmhg'}
            ],
            unitWind: [
                {value: 'm/s', text: 'm/s'},
                {value: 'km/h', text: 'km/h'},
                {value: 'mph', text: 'mph'}
            ],
            unitRain: [
                {value: 'mm', text: 'mm'},
                {value: 'inch', text: 'inch'}
            ],
            stations: [],
            selectedStation: null,
            selectedUnitTemp: 'ºC',
            selectedUnitPres: 'hpa',
            selectedUnitWind: 'm/s',
            selectedUnitRain: 'mm',
            file: null,
            filesObservation: null,
            previewData: null,
            total_rows: null,
            busy: false,
            error_data: null,
            success: null,
            error: null,
            error_temp: false,
            error_press: false,
            error_wind: false,
            error_rain: false,
            uploadError: null,
            uploader_id: null,
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
            ]
        }
    },
    props: {
        bgColor: {
            type: String,
            default: 'red'
        }
    },
    mounted() {

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

            if (!this.file) {
                this.uploadError = "Elija un archivo para subir";
                return;
            }

            if (!this.selectedStation) {
                this.uploadError = "Seleccione la estación de la que provienen estos datos";
                return;
            }

            this.busy = true;
            let formData = new FormData();
            formData.append('data-file', this.file.file);
            formData.append('data-filesObservation', this.filesObservation ? this.filesObservation.file : null);
            formData.append('selectedStation', this.selectedStation.id);
            formData.append('selectedUnitTemp', this.selectedUnitTemp);
            formData.append('selectedUnitPres', this.selectedUnitPres);
            formData.append('selectedUnitWind', this.selectedUnitWind);
            formData.append('selectedUnitRain', this.selectedUnitRain);


            axios.post(rootUrl + '/files', formData, {}).then((result) => {

                console.log(result)
                this.total_rows = result.data.met_data_preview.total;
                this.previewData = result.data.met_data_preview.data;
                this.uploader_id = (this.previewData[0]['uploader_id']);

                // show advice message
                if (result.data.scenario == 1) {
                    this.success = result.data.adviceMessage;
                    this.scenario3 = false;
                } else if (result.data.scenario == 2) {
                    this.error = result.data.adviceMessage;
                    this.scenario3 = false;
                } else if (result.data.scenario == 3) {
                    this.success = result.data.adviceMessage;
                    this.scenario3 = true;
                }


                // this.error_data = result.data.error_data.original.error_data;
                // this.error_temp = result.data.error_data.original.error_temp;
                // this.error_press = result.data.error_data.original.error_press;
                // this.error_wind = result.data.error_data.original.error_wind;
                // this.error_rain = result.data.error_data.original.error_rain;


                this.$refs.panel1.click()
                this.$refs.panel2.click()




            })
                .catch((error) => {
                    this.busy = false;
                    console.log(error);
                    if (error.response && error.response.hasOwnProperty('data')) {
                        this.uploadError = error.response.data.message;
                    } else {
                        this.uploadError = "No se pudo subir el archivo. Verifique que esté en el formato correcto o póngase en contacto con el administrador de la plataforma para obtener más información.";
                    }
                })
                .then(() => {
                    this.busy = false;
                })


        },

        storeFile: function () {
            this.busy = true;
            axios({
                method: 'post',
                url: "/storeFile/" + this.uploader_id,
            })
                .then((result) => {
                    console.log(result.data.success);
                    this.success = result.data.success
                    this.error = result.data.error
                    this.busy = false;

                    // after moving staging records from table "data_preview" to table "data"
                    // redirect to a upload success static page
                    window.location.assign('/uploadsuccess')

                }, (error) => {
                    console.log(error);
                    this.error = error
                    this.busy = false;
                });
        },

        cleanTable: function () {
            this.busy = true;
            axios({
                method: 'post',
                url: "/cleanTable/" + this.uploader_id,
            })
                .then((result) => {
                    console.log(result);
                    this.busy = false;
                    window.location.reload();

                }, (error) => {
                    console.log(error);
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
        }

    }
}


</script>
