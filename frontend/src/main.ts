import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import router from './router';

import './assets/scss/main.scss';

import { useAuthStore } from './stores/auth'; // ✅ import

const app = createApp(App);

const pinia = createPinia();     // ✅ créer pinia
app.use(pinia);                  // ✅ installer pinia
app.use(router);                 // ✅ installer router

// ✅ maintenant on peut utiliser le store
const auth = useAuthStore();
if (auth.token && !auth.user) {
  auth.fetchMe().catch(() => auth.logout());
}


app.mount('#app');
