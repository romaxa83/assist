import './bootstrap';
import App from './components/App.vue';
import { createApp } from 'vue'
import router from './router/router.js';
import * as bootstrap from 'bootstrap/dist/js/bootstrap.bundle';


const app = createApp(App);

app
    .provide('bootstrap', bootstrap)
    .use(router)
    .mount('#app');
