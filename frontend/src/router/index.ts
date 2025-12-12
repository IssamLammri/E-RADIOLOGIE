import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth' // On importe le store qu'on vient de créer

// On importe les futures pages (ça va souligner en rouge, c'est normal pour l'instant !)
import LoginView from '../views/LoginView.vue'
import HomeView from '../views/HomeView.vue'

// Vues Cas Cliniques
import ClinicalCasesList from '../views/clinical-cases/ClinicalCasesList.vue'; // Vérifiez bien ce chemin !
import ClinicalCaseForm from '../views/clinical-cases/ClinicalCaseForm.vue'; // Le nouveau fichier

// Vues Patients
import PatientsList from '../views/patients/PatientsList.vue';
import PatientForm from '../views/patients/PatientForm.vue';
import PatientEdit from '../views/patients/PatientEdit.vue'; // <--- Import

// Vues Examens
import ExamList from '../views/exams/ExamList.vue';
import ExamForm from '../views/exams/ExamForm.vue';

// Vues Modalités
import ModalityList from '../views/modalities/ModalityList.vue';
import ModalityForm from '../views/modalities/ModalityForm.vue';

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
    },
    {
      path: '/cas-clinique',
      name: 'cas-clinique-list',
      component: ClinicalCasesList,
      meta: { requiresAuth: true }
    },
    {
      path: '/cas-clinique/nouveau', // La route manquante !
      name: 'cas-clinique-create',
      component: ClinicalCaseForm,
      meta: { requiresAuth: true }
    },

    {
      path: '/patients',
      name: 'patients-list',
      component: PatientsList,
      meta: { requiresAuth: true }
    },
    {
      path: '/patients/nouveau',
      name: 'patient-create',
      component: PatientForm,
      meta: { requiresAuth: true }
    },

    {
      // Le ":id" signifie que cette partie de l'URL est variable
      path: '/patients/edit/:id',
      name: 'patient-edit',
      component: PatientEdit,
      meta: { requiresAuth: true }
    },

    {
      path: '/exams',
      name: 'exams-list',
      component: ExamList,
      meta: { requiresAuth: true }
    },
    {
      path: '/exams/nouveau',
      name: 'exam-create',
      component: ExamForm,
      meta: { requiresAuth: true }
    },

    {
      path: '/modalities',
      name: 'modality-list',
      component: ModalityList,
      meta: { requiresAuth: true }
    },
    {
      path: '/modalities/nouveau',
      name: 'modality-create',
      component: ModalityForm,
      meta: { requiresAuth: true }
    },
    {
      path: '/modalities/edit/:id',
      name: 'modality-edit',
      component: ModalityForm,
      meta: { requiresAuth: true }
    },


    { path: '/pathologies', name: 'pathologies', component: HomeView, meta: { requiresAuth: true } },
    { path: '/users', name: 'users', component: HomeView, meta: { requiresAuth: true } },
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
