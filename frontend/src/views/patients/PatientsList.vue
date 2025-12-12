<script setup lang="ts">
import { ref, onMounted } from 'vue';
import Sidebar from '@/components/layout/Sidebar.vue';
import TopBar from '@/components/layout/TopBar.vue';
import patientService, { type Patient } from '@/api/patients';

// --- ÉTAT ---
const patients = ref<Patient[]>([]);
const isLoading = ref(true);
const error = ref('');

// --- CHARGEMENT ---
const fetchPatients = async () => {
  isLoading.value = true;
  error.value = '';
  try {
    const response = await patientService.getAll();

    // --- CORRECTION ICI ---
    // On regarde ce que contient response.data
    const data = response.data;

    // On vérifie les deux cas possibles (avec ou sans 'hydra:')
    if (data.member) {
      patients.value = data.member;
    } else if (data['hydra:member']) {
      patients.value = data['hydra:member'];
    } else {
      patients.value = [];
    }

    console.log("Patients chargés :", patients.value);

  } catch (err) {
    console.error(err);
    error.value = "Impossible de charger la liste des patients.";
    patients.value = [];
  } finally {
    isLoading.value = false;
  }
};

// --- SUPPRESSION ---
const handleDelete = async (id: number) => {
  if (confirm("Voulez-vous vraiment supprimer ce patient ? Cette action est irréversible.")) {
    try {
      await patientService.delete(id);
      // Mise à jour locale de la liste
      patients.value = patients.value.filter(p => p.id !== id);
    } catch (err) {
      alert("Erreur lors de la suppression.");
    }
  }
};

onMounted(() => {
  fetchPatients();
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
            <h1>Patients</h1>
            <p class="subtitle">Gestion des dossiers patients</p>
          </div>
          <router-link to="/patients/nouveau" class="btn btn-primary">
            + Nouveau Patient
          </router-link>
        </div>

        <div v-if="isLoading" class="loading-state">
          <span class="spinner">⏳</span> Chargement des patients...
        </div>

        <div v-else-if="error" class="error-msg">
          ⚠️ {{ error }}
        </div>

        <div v-else class="table-card">
          <table class="data-table">
            <thead>
            <tr>
              <th>ID</th>
              <th>Genre</th>
              <th>Âge</th>
              <th>Antécédents</th>
              <th>Cas liés</th>
              <th class="actions-col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="patient in patients" :key="patient.id">
              <td>#{{ patient.id }}</td>
              <td>
                  <span class="gender-tag" :class="patient.gender?.toLowerCase()">
                    {{ patient.gender }}
                  </span>
              </td>
              <td class="fw-bold">{{ patient.age }} ans</td>

              <td class="truncate-text" :title="patient.history">
                {{ patient.history || 'Aucun antécédent noté' }}
              </td>

              <td>
                  <span class="badge-count">
                    {{ patient.clinicalCases?.length || 0 }} cas
                  </span>
              </td>

              <td class="actions">
                <router-link
                  :to="{ name: 'patient-edit', params: { id: patient.id } }"
                  class="btn-icon edit"
                  title="Modifier"
                >
                  ✏️
                </router-link>
                <button class="btn-icon delete" title="Supprimer" @click="handleDelete(patient.id)">🗑️</button>
              </td>
            </tr>

            <tr v-if="patients && patients.length === 0">
              <td colspan="6" class="empty-state">
                Aucun patient enregistré pour le moment.
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

// On reprend les mêmes styles globaux pour la cohérence
.dashboard-layout { display: flex; min-height: 100vh; background-color: #f4f6f8; }
.main-content { flex: 1; display: flex; flex-direction: column; }
.page-container { padding: 2rem; margin-left: 260px; }

.page-header {
  display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;
  h1 { font-size: 1.8rem; margin-bottom: 0.2rem; color: $text-color; }
  .subtitle { color: $secondary; font-size: 0.95rem; }
}

.table-card {
  background: white; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); overflow: hidden;
}

.data-table {
  width: 100%; border-collapse: collapse;
  th, td { padding: 1rem 1.5rem; text-align: left; border-bottom: 1px solid #f0f0f0; }
  th { background-color: #fafafa; font-weight: 600; color: $secondary; font-size: 0.85rem; text-transform: uppercase; }
  tr:hover { background-color: #f9f9f9; }
  .actions { display: flex; justify-content: flex-end; gap: 8px; }
}

// Styles spécifiques Patients
.gender-tag {
  font-weight: 600;
  &.homme, &.male { color: #007bff; } // Bleu
  &.femme, &.female { color: #e83e8c; } // Rose
}

.truncate-text {
  max-width: 250px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  color: #666;
}

.badge-count {
  background-color: #eef2f7;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 0.8rem;
  font-weight: 600;
  color: $text-color;
}

.btn-icon {
  background: none; border: none; cursor: pointer; font-size: 1.1rem; padding: 6px; border-radius: 4px; transition: background 0.2s;
  &:hover { background-color: #eee; }
  &.delete:hover { background-color: rgba($danger, 0.1); color: $danger; }
}

.empty-state { text-align: center; padding: 3rem; color: $secondary; font-style: italic; }
.loading-state { text-align: center; padding: 3rem; color: $primary; }

.gender-tag {
  font-weight: 600;
  // Ajoutez &.women ici 👇
  &.homme, &.male, &.men { color: #007bff; } // Bleu
  &.femme, &.female, &.women { color: #e83e8c; } // Rose
}
</style>
