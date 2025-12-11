import { createApp } from 'vue'
import { createPinia } from 'pinia' // <--- Important
import App from './App.vue'
import router from './router'       // <--- Important

const app = createApp(App)

app.use(createPinia()) // <--- Active le store
app.use(router)        // <--- Active le router

app.mount('#app')
