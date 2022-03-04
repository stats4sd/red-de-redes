require('./bootstrap')

import { createApp } from 'vue'
import UploaderWeatherData from "./components/UploaderWeatherData";

createApp({
    components: {
        UploaderWeatherData
    }
}).mount('#app');
