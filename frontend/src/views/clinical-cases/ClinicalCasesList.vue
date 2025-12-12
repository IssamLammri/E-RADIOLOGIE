<script setup lang="ts">
import { ref, onMounted } from 'vue';
import Sidebar from '@/components/layout/Sidebar.vue';
import TopBar from '@/components/layout/TopBar.vue';
import clinicalCaseService, { type ClinicalCase } from '@/api/clinicalCases';

// --- ÉTAT (STATE) ---
const cases = ref<ClinicalCase[]>([]);
const isLoading = ref(true);
const error = ref('');

// --- CHARGEMENT DES DONNÉES ---
const fetchCases = async () => {
  isLoading.value = true;
  error.value = ''; // On reset l'erreur
  try {
    const response = await clinicalCaseService.getAll();

    // --- CORRECTION ICI ---
    // On utilise "|| []" pour garantir que ce soit toujours un tableau
    // Même si l'API renvoie undefined ou null.
    cases.value = response.data['hydra:member'] || [];

    console.log("Données reçues :", cases.value); // Pour vérifier dans la console

  } catch (err) {
    console.error(err);
    error.value = "Impossible de charger les cas cliniques.";
    // En cas d'erreur, on s'assure que la liste reste vide pour ne pas casser l'affichage
    cases.value = [];
  } finally {
    isLoading.value = false;
  }
};

// --- SUPPRESSION ---
const handleDelete = async (id: number) => {
  if (confirm("Êtes-vous sûr de vouloir supprimer ce cas clinique ?")) {
    try {
      await clinicalCaseService.delete(id);
      // On retire l'élément de la liste localement pour éviter de recharger la page
      cases.value = cases.value.filter(c => c.id !== id);
    } catch (err) {
      alert("Erreur lors de la suppression.");
    }
  }
};

// Au chargement du composant, on appelle l'API
onMounted(() => {
  fetchCases();
});
</script>

<template>
  <div class="dashboard-layout">
    <Sidebar />

    <main class="main-content">
      <TopBar />

      <div class="page-container">
        <div class="page-header">
          <div>
            <h1>Cas Cliniques</h1>
            <p class="subtitle">Gérez la bibliothèque des cas cliniques</p>
          </div>
          <router-link to="/cas-clinique/nouveau" class="btn btn-primary">
            + Nouveau Cas
          </router-link>
        </div>

        <div v-if="isLoading" class="loading-state">
          <span class="spinner">⏳</span> Chargement des données...
        </div>

        <div v-else-if="error" class="error-msg">
          ⚠️ {{ error }}
        </div>

        <div v-else class="table-card">
          <table class="data-table">
            <thead>
            <tr>
              <th>ID</th>
              <th>Titre</th>
              <th>Statut</th>
              <th>Date</th>
              <th class="actions-col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in cases" :key="item.id">
              <td>#{{ item.id }}</td>
              <td class="fw-bold">{{ item.title }}</td>
              <td>
                  <span class="badge" :class="item.status === 'Publié' ? 'badge-success' : 'badge-warning'">
                    {{ item.status || 'Brouillon' }}
                  </span>
              </td>
              <td>{{ new Date(item.createdAt).toLocaleDateString() }}</td>
              <td class="actions">
                <button class="btn-icon edit" title="Modifier">✏️</button>
                <button class="btn-icon delete" title="Supprimer" @click="handleDelete(item.id)">🗑️</button>
              </td>
            </tr>

            <tr v-if="cases.length === 0">
              <td colspan="5" class="empty-state">
                Aucun cas clinique trouvé. Commencez par en créer un !
              </td>
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
@use "sass:color";

// Layout Dashboard
.dashboard-layout { display: flex; min-height: 100vh; background-color: #f4f6f8; }
.main-content { flex: 1; display: flex; flex-direction: column; }
.page-container { padding: 2rem; margin-left: 260px; margin-top: 0; }

// En-tête
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;

  h1 { font-size: 1.8rem; margin-bottom: 0.2rem; color: $text-color; }
  .subtitle { color: $secondary; font-size: 0.95rem; }
}

// Carte Tableau
.table-card {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.05);
  overflow: hidden;
}

.data-table {
  width: 100%;
  border-collapse: collapse;

  th, td {
    padding: 1rem 1.5rem;
    text-align: left;
    border-bottom: 1px solid #f0f0f0;
  }

  th {
    background-color: #fafafa;
    font-weight: 600;
    color: $secondary;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  tr:hover { background-color: #f9f9f9; }

  .fw-bold { font-weight: 600; color: $primary; }
  .actions-col { text-align: right; }

  .actions {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
  }
}

// Badges
.badge {
  padding: 4px 10px;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 600;

  &.badge-success { background-color: rgba($success, 0.1); color: $success; }
  &.badge-warning { background-color: rgba(#ffc107, 0.15); color: #d39e00; }
}

// Boutons Icones
.btn-icon {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1.1rem;
  padding: 6px;
  border-radius: 4px;
  transition: background 0.2s;

  &:hover { background-color: #eee; }
  &.delete:hover { background-color: rgba($danger, 0.1); color: $danger; }
}

.empty-state { text-align: center; padding: 3rem; color: $secondary; font-style: italic; }
.loading-state { text-align: center; padding: 3rem; color: $primary; font-size: 1.1rem; }
</style>
