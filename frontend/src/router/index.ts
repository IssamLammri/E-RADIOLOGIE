import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth' // On importe le store qu'on vient de créer

// On importe les futures pages (ça va souligner en rouge, c'est normal pour l'instant !)
import LoginView from '../views/LoginView.vue'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: LoginView
    },
    {
      path: '/',
      name: 'home',
      component: HomeView,
      meta: { requiresAuth: true }
    }
  ]
})

// --- LE GARDIEN (Navigation Guard) ---
// Ce code s'exécute AVANT chaque changement de page
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()

  // Si la page demande une authentification (meta.requiresAuth)
  // ET que l'utilisateur n'est pas connecté (!authStore.isAuthenticated)
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    // Alors on le force à aller sur le login
    next('/login')
  } else {
    // Sinon, on le laisse passer
    next()
  }
})

export default router
