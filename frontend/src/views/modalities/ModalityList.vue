<script setup lang="ts">
import { ref, onMounted } from 'vue';
import Sidebar from '@/components/layout/Sidebar.vue';
import TopBar from '@/components/layout/TopBar.vue';
import modalityService, { type Modality } from '@/api/modalities';

const modalities = ref<Modality[]>([]);
const isLoading = ref(true);
const error = ref('');

const fetchModalities = async () => {
  isLoading.value = true;
  try {
    const response = await modalityService.getAll();
    const data = response.data as any;
    // Gestion robuste (JSON-LD ou JSON simple)
    modalities.value = data['hydra:member'] || data.member || [];
  } catch (err) {
    console.error(err);
    error.value = "Impossible de charger les modalités.";
  } finally {
    isLoading.value = false;
  }
};

const handleDelete = async (id: number) => {
  if (confirm("Voulez-vous vraiment supprimer cette modalité ?")) {
    try {
      await modalityService.delete(id);
      modalities.value = modalities.value.filter(m => m.id !== id);
    } catch (err) {
      alert("Erreur : Impossible de supprimer (peut-être utilisée par un examen ?)");
    }
  }
};

onMounted(() => fetchModalities());
</script>

<template>
  <div class="dashboard-layout">
    <Sidebar />
    <main class="main-content">
      <TopBar />
      <div class="page-container">

        <div class="page-header">
          <div>
            <h1>Modalités</h1>
            <p class="subtitle">Types d'examens (IRM, Scanner, Radio...)</p>
          </div>
          <router-link to="/modalities/nouveau" class="btn btn-primary">
            + Nouvelle Modalité
          </router-link>
        </div>

        <div v-if="isLoading" class="loading-state">Chargement...</div>
        <div v-else-if="error" class="error-msg">⚠️ {{ error }}</div>

        <div v-else class="table-card">
          <table class="data-table">
            <thead>
            <tr>
              <th>ID</th>
              <th>Nom de la modalité</th>
              <th class="actions-col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="mod in modalities" :key="mod.id">
              <td>#{{ mod.id }}</td>
              <td class="fw-bold">{{ mod.name }}</td>
              <td class="actions">
                <router-link :to="`/modalities/edit/${mod.id}`" class="btn-icon edit">✏️</router-link>
                <button class="btn-icon delete" @click="handleDelete(mod.id)">🗑️</button>
              </td>
            </tr>
            <tr v-if="modalities.length === 0">
              <td colspan="3" class="empty-state">Aucune modalité configurée.</td>
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
// Styles standards dashboard
.dashboard-layout { display: flex; min-height: 100vh; background-color: #f4f6f8; }
.main-content { flex: 1; display: flex; flex-direction: column; }
.page-container { padding: 2rem; margin-left: 260px; }
.page-header { display: flex; justify-content: space-between; margin-bottom: 2rem; h1 { margin-bottom: 0.2rem; } .subtitle { color: $secondary; } }
.table-card { background: white; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); overflow: hidden; }
.data-table { width: 100%; border-collapse: collapse; th, td { padding: 1rem; border-bottom: 1px solid #f0f0f0; text-align: left; } th { background: #fafafa; color: $secondary; font-size: 0.85rem; text-transform: uppercase; } }
.fw-bold { font-weight: 600; color: $primary; }
.actions { text-align: right; }
.btn-icon { background: none; border: none; cursor: pointer; font-size: 1.1rem; padding: 5px; text-decoration: none; display: inline-block; &:hover { background: #eee; } }
.empty-state { text-align: center; padding: 2rem; color: $secondary; }
</style>
