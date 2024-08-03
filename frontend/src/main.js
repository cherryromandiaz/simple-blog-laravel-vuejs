import { createApp } from 'vue';  // Use createApp for Vue 3, Programmer: Cherry Romandiaz
import App from './App.vue';
import router from './router';
import '@fortawesome/fontawesome-free/js/all.js';

createApp(App).use(router).mount('#app');
