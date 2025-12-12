<script setup lang="ts">
import { ref, onMounted } from 'vue';
import Sidebar from '@/components/layout/Sidebar.vue';
import TopBar from '@/components/layout/TopBar.vue';
import examService, { type Exam } from '@/api/exams';

const exams = ref<Exam[]>([]);
const isLoading = ref(true);
const error = ref('');

const fetchExams = async () => {
  isLoading.value = true;
  try {
    const response = await examService.getAll();
    const data = response.data as any;
    // Gestion robuste (JSON-LD vs JSON simple)
    exams.value = data['hydra:member'] || data.member || [];
  } catch (err) {
    console.error(err);
    error.value = "Impossible de charger les examens.";
  } finally {
    isLoading.value = false;
  }
};

const handleDelete = async (id: number) => {
  if (confirm("Supprimer cet examen ?")) {
    try {
      await examService.delete(id);
      exams.value = exams.value.filter(e => e.id !== id);
    } catch (err) {
      alert("Erreur lors de la suppression.");
    }
  }
};

onMounted(() => fetchExams());
</script>

<template>
  <div class="dashboard-layout">
    <Sidebar />
    <main class="main-content">
      <TopBar />
      <div class="page-container">

        <div class="page-header">
          <div>
            <h1>Examens</h1>
            <p class="subtitle">Catalogue des examens radiologiques</p>
          </div>
          <router-link to="/exams/nouveau" class="btn btn-primary">+ Nouvel Examen</router-link>
        </div>

        <div v-if="isLoading" class="loading-state">Chargement...</div>
        <div v-else-if="error" class="error-msg">⚠️ {{ error }}</div>

        <div v-else class="table-card">
          <table class="data-table">
            <thead>
            <tr>
              <th>Nom</th>
              <th>Description</th>
              <th>Modalité (IRI)</th>
              <th class="actions-col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="exam in exams" :key="exam.id">
              <td class="fw-bold">{{ exam.name }}</td>
              <td>{{ exam.description || '-' }}</td>
              <td>
                <span class="badge">{{ exam.modality }}</span>
              </td>
              <td class="actions">
                <button class="btn-icon delete" @click="handleDelete(exam.id)">🗑️</button>
              </td>
            </tr>
            <tr v-if="exams.length === 0">
              <td colspan="4" class="empty-state">Aucun examen trouvé.</td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables" as *;
// Styles identiques aux précédents
.dashboard-layout { display: flex; min-height: 100vh; background-color: #f4f6f8; }
.main-content { flex: 1; display: flex; flex-direction: column; }
.page-container { padding: 2rem; margin-left: 260px; }
.page-header { display: flex; justify-content: space-between; margin-bottom: 2rem; h1 { margin-bottom: 0.2rem; } .subtitle { color: $secondary; } }
.table-card { background: white; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); overflow: hidden; }
.data-table { width: 100%; border-collapse: collapse; th, td { padding: 1rem; border-bottom: 1px solid #f0f0f0; text-align: left; } th { background: #fafafa; color: $secondary; font-size: 0.85rem; text-transform: uppercase; } }
.fw-bold { font-weight: 600; color: $primary; }
.badge { background: #eef2f7; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; }
.actions { text-align: right; }
.btn-icon { background: none; border: none; cursor: pointer; font-size: 1.1rem; &:hover { background: #eee; } }
.loading-state, .empty-state { text-align: center; padding: 2rem; color: $secondary; }
.error-msg { color: $danger; }
</style>
