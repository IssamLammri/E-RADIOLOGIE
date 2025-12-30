<script setup lang="ts">
import { ref, onMounted } from 'vue';
import Sidebar from '@/components/layout/Sidebar.vue';
import TopBar from '@/components/layout/TopBar.vue';

import clinicalCaseService, { type ClinicalCase } from '@/api/clinicalCases';
import apiClient from '@/api/axios';

// Pour afficher l'image (backend est sur 8090)
const API_HOST = import.meta.env.VITE_API_HOST || 'http://localhost:8090';

const casesList = ref<ClinicalCase[]>([]);
const isLoading = ref(true);
const error = ref('');

// Cache IRI -> label
const iriLabelCache = ref<Record<string, string>>({});

// Helpers
const short = (s?: string, max = 70) =>
  s ? (s.length > max ? s.slice(0, max) + '…' : s) : '-';

const fullImageUrl = (path?: string) => {
  if (!path) return '';
  if (path.startsWith('http://') || path.startsWith('https://')) return path;
  // ex: /uploads/clinical-cases/x.jpg -> http://localhost:8090/uploads/...
  return `${API_HOST}${path}`;
};

// Essaie de récupérer un "label" pour une IRI
// - patient: "Patient #id - gender - age"
// - exam: "name"
// - pathology: "name"
const resolveIriLabel = async (iri: string) => {
  if (!iri) return '';
  if (iriLabelCache.value[iri]) return iriLabelCache.value[iri];

  try {
    // IMPORTANT : iri = "/api/patients/1" ou "/api/exams/1" etc.
    const normalizeIri = (iri: string) => {
      if (!iri) return iri;
      if (iri.startsWith('http://') || iri.startsWith('https://')) return iri;
      return iri.replace(/^\/api\//, '/');
    };

    const res = await apiClient.get(normalizeIri(iri));


    const data = res.data as any;

    let label = iri;

    if (iri.includes('/patients/')) {
      const id = data.id ?? '?';
      const gender = data.gender ?? '';
      const age = data.age ?? '';
      label = `Patient #${id}${gender || age ? ` - ${gender} ${age} ans` : ''}`.trim();
    } else if (iri.includes('/exams/')) {
      label = data.name ?? iri;
    } else if (iri.includes('/pathologies/')) {
      label = data.name ?? iri;
    } else {
      // fallback
      label = data.name ?? data.title ?? iri;
    }

    iriLabelCache.value[iri] = label;
    return label;
  } catch (e) {
    // Si ça échoue, on garde l'IRI
    iriLabelCache.value[iri] = iri;
    return iri;
  }
};

const enrichLabels = async (items: ClinicalCase[]) => {
  // On résout seulement les IRIs uniques
  const iris = new Set<string>();

  items.forEach((c) => {
    if (c.patient) iris.add(c.patient);
    if (c.exam) iris.add(c.exam);
    if (c.pathology) iris.add(c.pathology);
  });

  await Promise.all([...iris].map((iri) => resolveIriLabel(iri)));
};

const fetchCases = async () => {
  isLoading.value = true;
  error.value = '';

  try {
    const response = await clinicalCaseService.getAll();
    const data = response.data as any;
    casesList.value = data['hydra:member'] || data.member || [];

    // Résoudre les labels (patient/exam/pathology)
    await enrichLabels(casesList.value);
  } catch (err) {
    console.error(err);
    error.value = 'Impossible de charger les cas cliniques.';
  } finally {
    isLoading.value = false;
  }
};

const handleDelete = async (id: number) => {
  if (confirm('Supprimer ce cas clinique ?')) {
    try {
      await clinicalCaseService.delete(id);
      casesList.value = casesList.value.filter((c) => c.id !== id);
    } catch (err) {
      alert('Erreur lors de la suppression.');
    }
  }
};

onMounted(fetchCases);
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
            <p class="subtitle">Gestion des dossiers cliniques</p>
          </div>
          <router-link to="/cas-clinique/nouveau" class="btn btn-primary">
            + Nouveau Cas
          </router-link>
        </div>

        <div v-if="isLoading" class="loading-state">Chargement...</div>
        <div v-else-if="error" class="error-msg">⚠️ {{ error }}</div>

        <div v-else class="table-card">
          <table class="data-table">
            <thead>
            <tr>
              <th>ID</th>
              <th>Image</th>
              <th>Patient</th>
              <th>Examen</th>
              <th>Pathologie</th>
              <th>Symptômes</th>
              <th class="actions-col">Actions</th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="c in casesList" :key="c.id">
              <td class="fw-bold">#{{ c.id }}</td>

              <td>
                <div v-if="c.images" class="thumb">
                  <img :src="fullImageUrl(c.images)" alt="clinical image" />
                </div>
                <span v-else class="muted">-</span>
              </td>

              <td>
                <div class="cell-main">
                  {{ iriLabelCache[c.patient] || c.patient }}
                </div>
              </td>

              <td>
                <div class="cell-main">
                  {{ iriLabelCache[c.exam] || c.exam }}
                </div>
              </td>

              <td>
                <div class="cell-main">
                  {{ iriLabelCache[c.pathology] || c.pathology }}
                </div>
              </td>

              <td>{{ short(c.symptoms, 80) }}</td>

              <td class="actions">
                <router-link class="btn-icon" :to="`/cas-clinique/edit/${c.id}`">✏️</router-link>
                <button class="btn-icon delete" @click="handleDelete(c.id)">🗑️</button>
              </td>
            </tr>

            <tr v-if="casesList.length === 0">
              <td colspan="7" class="empty-state">Aucun cas clinique trouvé.</td>
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

.dashboard-layout { display: flex; min-height: 100vh; background-color: #f4f6f8; }
.main-content { flex: 1; display: flex; flex-direction: column; }
.page-container { padding: 2rem; margin-left: 260px; }

.page-header {
  display: flex; justify-content: space-between; margin-bottom: 2rem;
  h1 { margin-bottom: 0.2rem; }
  .subtitle { color: $secondary; }
}

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
    padding: 1rem;
    border-bottom: 1px solid #f0f0f0;
    text-align: left;
    vertical-align: top;
  }

  th {
    background: #fafafa;
    color: $secondary;
    font-size: 0.85rem;
    text-transform: uppercase;
  }
}

.fw-bold { font-weight: 600; color: $primary; }
.muted { color: $secondary; }

.cell-main { font-weight: 600; }
.cell-sub { font-size: 0.8rem; margin-top: 4px; }

.thumb {
  width: 56px;
  height: 56px;
  border-radius: 8px;
  overflow: hidden;
  border: 1px solid #eee;
  background: #fafafa;
  display: inline-flex;
  align-items: center;
  justify-content: center;

  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
}

.actions {
  text-align: right;
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

.btn-icon {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1.1rem;
  text-decoration: none;
  padding: 6px 8px;
  border-radius: 6px;

  &:hover { background: #eee; }
}

.loading-state, .empty-state {
  text-align: center;
  padding: 2rem;
  color: $secondary;
}

.error-msg { color: $danger; }
</style>
