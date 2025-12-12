<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import Sidebar from '@/components/layout/Sidebar.vue';
import TopBar from '@/components/layout/TopBar.vue';
import modalityService from '@/api/modalities';

const router = useRouter();
const route = useRoute();

// Si on a un ID dans l'URL, c'est une édition
const isEditMode = route.params.id !== undefined;
const modalityId = isEditMode ? parseInt(route.params.id as string) : null;

const form = ref({
  name: ''
});

const isLoading = ref(false);
const error = ref('');

// Charger les données si on est en mode édition
onMounted(async () => {
  if (isEditMode && modalityId) {
    try {
      const response = await modalityService.get(modalityId);
      form.value.name = response.data.name;
    } catch (err) {
      error.value = "Impossible de charger la modalité.";
    }
  }
});

const handleSubmit = async () => {
  isLoading.value = true;
  error.value = '';

  try {
    if (isEditMode && modalityId) {
      await modalityService.update(modalityId, form.value);
    } else {
      await modalityService.create(form.value);
    }
    await router.push('/modalities');
  } catch (err) {
    console.error(err);
    error.value = "Une erreur est survenue lors de la sauvegarde.";
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
          <h1>{{ isEditMode ? 'Modifier la modalité' : 'Nouvelle Modalité' }}</h1>
        </div>

        <div class="form-card">
          <form @submit.prevent="handleSubmit">

            <div class="form-group">
              <label>Nom de la modalité *</label>
              <input
                v-model="form.name"
                type="text"
                placeholder="Ex: IRM, Scanner, Échographie..."
                required
              />
            </div>

            <div v-if="error" class="error-msg">⚠️ {{ error }}</div>

            <div class="form-actions">
              <router-link to="/modalities" class="btn btn-secondary">Annuler</router-link>
              <button type="submit" class="btn btn-primary" :disabled="isLoading">
                {{ isEditMode ? 'Modifier' : 'Créer' }}
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
// Styles identiques aux autres formulaires
.dashboard-layout { display: flex; min-height: 100vh; background-color: #f4f6f8; }
.main-content { flex: 1; display: flex; flex-direction: column; }
.page-container { padding: 2rem; margin-left: 260px; max-width: 600px; } // Max-width plus petit car formulaire simple
.form-card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
.form-actions { display: flex; justify-content: flex-end; gap: 15px; margin-top: 2rem; border-top: 1px solid #f0f0f0; padding-top: 1.5rem; }
.btn-secondary { background: #e2e6ea; color: $text-color; text-decoration: none; padding: 0.6rem 1.2rem; border-radius: 6px; display: inline-flex; align-items: center; }
.error-msg { color: $danger; background: rgba($danger, 0.1); padding: 10px; border-radius: 6px; margin-bottom: 1rem; }
</style>
