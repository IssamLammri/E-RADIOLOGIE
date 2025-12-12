<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import Sidebar from '@/components/layout/Sidebar.vue';
import TopBar from '@/components/layout/TopBar.vue';
import examService from '@/api/exams';
import modalityService, { type Modality } from '@/api/modalities';

const router = useRouter();

// Données du formulaire
const form = ref({
  name: '',
  description: '',
  modality: '' // Contiendra l'IRI (ex: /api/modalities/3)
});

// Liste pour le select
const modalitiesList = ref<Modality[]>([]);

const isLoading = ref(false);
const error = ref('');

// 1. Charger les modalités au démarrage
onMounted(async () => {
  try {
    const response = await modalityService.getAll();
    const data = response.data as any;
    modalitiesList.value = data['hydra:member'] || data.member || [];
  } catch (err) {
    console.error("Erreur chargement modalités", err);
    error.value = "Impossible de charger la liste des modalités.";
  }
});

// 2. Soumettre le formulaire
const handleSubmit = async () => {
  isLoading.value = true;
  error.value = '';

  try {
    await examService.create(form.value);
    await router.push('/exams');
  } catch (err) {
    console.error(err);
    error.value = "Erreur lors de la création de l'examen.";
  } finally {
    isLoading.value = false;
  }
};
</script>

<template>
  <div class="dashboard-layout">
    <Sidebar />
    <main class="main-content">
      <TopBar />
      <div class="page-container">

        <div class="form-header">
          <h1>Nouvel Examen</h1>
        </div>

        <div class="form-card">
          <form @submit.prevent="handleSubmit">

            <div class="form-group">
              <label>Nom de l'examen *</label>
              <input v-model="form.name" type="text" placeholder="Ex: IRM Cérébrale" required />
            </div>

            <div class="form-group">
              <label>Modalité (Type) *</label>
              <select v-model="form.modality" required>
                <option value="" disabled>-- Choisir une modalité --</option>
                <option
                  v-for="mod in modalitiesList"
                  :key="mod.id"
                  :value="mod['@id']"
                >
                  {{ mod.name }}
                </option>
              </select>
              <small v-if="modalitiesList.length === 0" style="color: orange">
                ⚠️ Aucune modalité trouvée. Veuillez en ajouter en base de données.
              </small>
            </div>

            <div class="form-group">
              <label>Description</label>
              <textarea v-model="form.description" rows="4" placeholder="Détails techniques..."></textarea>
            </div>

            <div v-if="error" class="error-msg">⚠️ {{ error }}</div>

            <div class="form-actions">
              <router-link to="/exams" class="btn btn-secondary">Annuler</router-link>
              <button type="submit" class="btn btn-primary" :disabled="isLoading">
                Créer l'examen
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
// Styles standards (identiques PatientForm)
.dashboard-layout { display: flex; min-height: 100vh; background-color: #f4f6f8; }
.main-content { flex: 1; display: flex; flex-direction: column; }
.page-container { padding: 2rem; margin-left: 260px; max-width: 800px; }
.form-card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
.form-actions { display: flex; justify-content: flex-end; gap: 15px; margin-top: 2rem; border-top: 1px solid #f0f0f0; padding-top: 1.5rem; }
.btn-secondary { background: #e2e6ea; color: $text-color; text-decoration: none; padding: 0.6rem 1.2rem; border-radius: 6px; display: inline-flex; align-items: center; }
textarea, select { width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 6px; }
.error-msg { color: $danger; background: rgba($danger, 0.1); padding: 10px; border-radius: 6px; margin-bottom: 1rem; }
</style>
