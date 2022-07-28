
import {createApp} from 'vue'
import UploaderWeatherData from "./components/UploaderWeatherData";
import axios from 'axios';
import Echo from 'laravel-echo';
window.Noty = require('noty');

window.pusher = require('pusher-js')

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    wsHost: process.env.MIX_PUSHER_HOST,
    wsPort: process.env.MIX_PUSHER_PORT,
    wssPort: process.env.MIX_PUSHER_PORT,
    forceTLS: false,
    encrypted: process.env.MIX_APP_ENV !== "local",
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});

createApp({
    components: {
        UploaderWeatherData
    }
}).mount('#app');
