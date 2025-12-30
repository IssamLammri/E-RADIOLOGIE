<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Sidebar from '@/components/layout/Sidebar.vue';
import TopBar from '@/components/layout/TopBar.vue';
import pathologyService from '@/api/pathologies';

const route = useRoute();
const router = useRouter();

const id = computed(() => (route.params.id ? Number(route.params.id) : null));
const isEdit = computed(() => id.value !== null);

const form = ref({
  name: '',
  introduction: '',
  positiveDiagnosis: '',
  etiologicalDiagnosis: '',
  evolutionComplications: '',
  differentialDiagnosis: '',
  conclusion: ''
});

const isLoading = ref(false);
const error = ref('');

const loadPathology = async () => {
  if (!isEdit.value || !id.value) return;
  isLoading.value = true;
  error.value = '';
  try {
    const res = await pathologyService.get(id.value);
    const data = res.data as any;
    form.value = {
      name: data.name ?? '',
      introduction: data.introduction ?? '',
      positiveDiagnosis: data.positiveDiagnosis ?? '',
      etiologicalDiagnosis: data.etiologicalDiagnosis ?? '',
      evolutionComplications: data.evolutionComplications ?? '',
      differentialDiagnosis: data.differentialDiagnosis ?? '',
      conclusion: data.conclusion ?? ''
    };
  } catch (e) {
    console.error(e);
    error.value = "Impossible de charger la pathologie.";
  } finally {
    isLoading.value = false;
  }
};

const handleSubmit = async () => {
  isLoading.value = true;
  error.value = '';
  try {
    if (isEdit.value && id.value) {
      await pathologyService.update(id.value, form.value);
    } else {
      await pathologyService.create(form.value);
    }
    await router.push('/pathologies');
  } catch (e) {
    console.error(e);
    error.value = isEdit.value
      ? "Erreur lors de la mise à jour."
      : "Erreur lors de la création.";
  } finally {
    isLoading.value = false;
  }
};

onMounted(loadPathology);
</script>

<template>
  <div class="dashboard-layout">
    <Sidebar />
    <main class="main-content">
      <TopBar />
      <div class="page-container">

        <div class="form-header">
          <h1>{{ isEdit ? 'Modifier la Pathologie' : 'Nouvelle Pathologie' }}</h1>
        </div>

        <div class="form-card">
          <form @submit.prevent="handleSubmit">

            <div class="form-group">
              <label>Nom *</label>
              <input v-model="form.name" type="text" placeholder="Ex: AVC Ischémique" required />
            </div>

            <div class="form-group">
              <label>Introduction</label>
              <textarea v-model="form.introduction" rows="3" />
            </div>

            <div class="form-group">
              <label>Diagnostic positif</label>
              <textarea v-model="form.positiveDiagnosis" rows="3" />
            </div>

            <div class="form-group">
              <label>Diagnostic étiologique</label>
              <textarea v-model="form.etiologicalDiagnosis" rows="3" />
            </div>

            <div class="form-group">
              <label>Évolution / Complications</label>
              <textarea v-model="form.evolutionComplications" rows="3" />
            </div>

            <div class="form-group">
              <label>Diagnostic différentiel</label>
              <textarea v-model="form.differentialDiagnosis" rows="3" />
            </div>

            <div class="form-group">
              <label>Conclusion</label>
              <textarea v-model="form.conclusion" rows="3" />
            </div>

            <div v-if="error" class="error-msg">⚠️ {{ error }}</div>

            <div class="form-actions">
              <router-link to="/pathologies" class="btn btn-secondary">Annuler</router-link>
              <button type="submit" class="btn btn-primary" :disabled="isLoading">
                {{ isEdit ? 'Mettre à jour' : 'Créer' }}
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

.dashboard-layout { display: flex; min-height: 100vh; background-color: #f4f6f8; }
.main-content { flex: 1; display: flex; flex-direction: column; }
.page-container { padding: 2rem; margin-left: 260px; max-width: 900px; }

.form-card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
.form-group { margin-bottom: 1rem; display: flex; flex-direction: column; gap: 6px; }
textarea, input { width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 6px; }

.form-actions {
  display: flex; justify-content: flex-end; gap: 15px;
  margin-top: 2rem; border-top: 1px solid #f0f0f0; padding-top: 1.5rem;
}

.btn-secondary { background: #e2e6ea; color: $text-color; text-decoration: none; padding: 0.6rem 1.2rem; border-radius: 6px; display: inline-flex; align-items: center; }
.error-msg { color: $danger; background: rgba($danger, 0.1); padding: 10px; border-radius: 6px; margin-bottom: 1rem; }
</style>
