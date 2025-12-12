<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Sidebar from '@/components/layout/Sidebar.vue';
import TopBar from '@/components/layout/TopBar.vue';
import patientService from '@/api/patients';

const route = useRoute();
const router = useRouter();

// On récupère l'ID depuis l'URL (ex: /patients/edit/1)
const patientId = parseInt(route.params.id as string);

const form = ref({
  age: '',
  gender: 'Femme',
  history: ''
});

const isLoading = ref(false);
const isFetching = ref(true); // Pour le chargement initial
const error = ref('');

// --- 1. CHARGER LES DONNÉES EXISTANTES ---
const loadPatient = async () => {
  try {
    // 1. On récupère la RÉPONSE complète d'Axios
    const response = await patientService.get(patientId);

    // 2. On extrait les DONNÉES (le patient) qui sont dans .data
    const patient = response.data;

    // 3. On remplit le formulaire (avec une sécurité sur l'âge)
    form.value = {
      // On utilise ?.toString() ou une valeur vide si l'âge est null
      age: patient.age ? patient.age.toString() : '',
      gender: patient.gender,
      history: patient.history || ''
    };
  } catch (err) {
    error.value = "Impossible de charger les données du patient.";
    console.error(err);
  } finally {
    isFetching.value = false;
  }
};

// --- 2. SAUVEGARDER LES MODIFICATIONS ---
const handleSubmit = async () => {
  isLoading.value = true;
  error.value = '';

  try {
    await patientService.update(patientId, {
      age: parseInt(form.value.age),
      gender: form.value.gender,
      history: form.value.history
    });

    // Succès : retour à la liste
    await router.push('/patients');

  } catch (err) {
    console.error(err);
    error.value = "Erreur lors de la modification.";
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => {
  loadPatient();
});
</script>

<template>
  <div class="dashboard-layout">
    <Sidebar />
    <main class="main-content">
      <TopBar />
      <div class="page-container">

        <div class="form-header">
          <h1>Modifier le patient #{{ patientId }}</h1>
        </div>

        <div v-if="isFetching" class="loading-state">
          Chargement des informations...
        </div>

        <div v-else class="form-card">
          <form @submit.prevent="handleSubmit">

            <div class="form-row">
              <div class="form-group half">
                <label>Âge du patient</label>
                <input v-model="form.age" type="number" required />
              </div>

              <div class="form-group half">
                <label>Genre</label>
                <select v-model="form.gender" required>
                  <option value="Femme">Femme</option>
                  <option value="Homme">Homme</option>
                  <option value="Autre">Autre</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label>Antécédents Médicaux</label>
              <textarea v-model="form.history" rows="5"></textarea>
            </div>

            <div v-if="error" class="error-msg">⚠️ {{ error }}</div>

            <div class="form-actions">
              <router-link to="/patients" class="btn btn-secondary">Annuler</router-link>
              <button type="submit" class="btn btn-primary" :disabled="isLoading">
                {{ isLoading ? 'Sauvegarde...' : 'Enregistrer les modifications' }}
              </button>
            </div>

          </form>
        </div>

      </div>
    </main>
  </div>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables" as *;
@use "sass:color";

// (Mêmes styles que PatientForm pour la cohérence)
.dashboard-layout { display: flex; min-height: 100vh; background-color: #f4f6f8; }
.main-content { flex: 1; display: flex; flex-direction: column; }
.page-container { padding: 2rem; margin-left: 260px; max-width: 900px; }
.form-card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
.form-row { display: flex; gap: 20px; .half { flex: 1; } }
.form-actions { display: flex; justify-content: flex-end; gap: 15px; margin-top: 2rem; border-top: 1px solid #f0f0f0; padding-top: 1.5rem; }
.btn-secondary { background-color: #e2e6ea; color: $text-color; text-decoration: none; padding: 0.6rem 1.2rem; border-radius: 6px; font-weight: 600; display: inline-flex; align-items: center; }
textarea { width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 6px; resize: vertical; &:focus { outline: none; border-color: $primary; } }
.loading-state { text-align: center; padding: 3rem; font-size: 1.2rem; color: $secondary; }
.error-msg { color: $danger; background-color: rgba($danger, 0.1); padding: 10px; border-radius: 6px; margin-bottom: 1rem; }
</style>
