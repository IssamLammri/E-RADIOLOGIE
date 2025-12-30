<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import Sidebar from '@/components/layout/Sidebar.vue';
import TopBar from '@/components/layout/TopBar.vue';

import patientService from '@/api/patients';
import examService from '@/api/exams';
import modalityService from '@/api/modalities';
import pathologyService from '@/api/pathologies';
import clinicalCaseService from '@/api/clinicalCases';
import userService from '@/api/users';

type HydraCollection<T = any> = {
  'hydra:member'?: T[];
  member?: T[];
  'hydra:totalItems'?: number;
  totalItems?: number;
};

const isLoading = ref(true);
const error = ref('');

const stats = ref({
  patients: 0,
  exams: 0,
  modalities: 0,
  pathologies: 0,
  clinicalCases: 0,
  users: 0
});

// Cas cliniques (raw + UI enrichi)
const recentCasesRaw = ref<any[]>([]);
const recentCasesUi = ref<any[]>([]);

// Cache pour éviter de refaire les mêmes requêtes
const iriCache = new Map<string, string>();

const getTotal = (data: HydraCollection) => {
  if (typeof data['hydra:totalItems'] === 'number') return data['hydra:totalItems'];
  if (typeof data.totalItems === 'number') return data.totalItems;

  const arr = data['hydra:member'] || data.member || [];
  return arr.length;
};

const getMembers = (data: HydraCollection) => {
  return data['hydra:member'] || data.member || [];
};

const normalizeIri = (iri: string) => {
  if (!iri) return iri;
  // URL complète => OK
  if (iri.startsWith('http://') || iri.startsWith('https://')) return iri;
  // baseURL axios = http://localhost:8090/api, donc éviter /api/api/...
  return iri.replace(/^\/api\//, '/');
};

const pickLabelFromResource = (res: any) => {
  // Patient => label humain
  if (res?.age !== undefined && res?.gender !== undefined) {
    const g = String(res.gender).toLowerCase();
    const gender = g.startsWith('m') ? 'Homme' : g.startsWith('f') ? 'Femme' : res.gender;
    return `Patient #${res.id} • ${gender} • ${res.age} ans`;
  }

  // Exam / Modality / Pathology => souvent "name"
  if (res?.name) return String(res.name);

  // fallback
  if (res?.id) return `#${res.id}`;
  return '—';
};

const resolveIriLabel = async (iri: string) => {
  if (!iri) return '—';
  if (iriCache.has(iri)) return iriCache.get(iri)!;

  try {
    const fixed = normalizeIri(iri);
    const { default: apiClient } = await import('@/api/axios');
    const res = await apiClient.get(fixed);

    const label = pickLabelFromResource(res.data);
    iriCache.set(iri, label);
    return label;
  } catch (e) {
    console.warn('IRI resolve failed:', iri, e);
    const fallback = iri.split('/').filter(Boolean).slice(-2).join(' #') || iri;
    iriCache.set(iri, fallback);
    return fallback;
  }
};

const enrichRecentCases = async () => {
  const items = recentCasesRaw.value || [];

  const enriched = await Promise.all(
    items.map(async (cc: any) => {
      const [patientLabel, examLabel, pathologyLabel] = await Promise.all([
        resolveIriLabel(cc.patient),
        resolveIriLabel(cc.exam),
        resolveIriLabel(cc.pathology)
      ]);

      return {
        ...cc,
        patientLabel,
        examLabel,
        pathologyLabel
      };
    })
  );

  recentCasesUi.value = enriched;
};

const fetchDashboard = async () => {
  isLoading.value = true;
  error.value = '';

  try {
    // Tip perf : on demande 1 item pour totalItems
    const [
      patientsRes,
      examsRes,
      modalitiesRes,
      pathologiesRes,
      casesRes,
      usersRes
    ] = await Promise.all([
      patientService.getAll({ params: { page: 1, itemsPerPage: 1 } } as any),
      examService.getAll({ params: { page: 1, itemsPerPage: 1 } } as any),
      modalityService.getAll({ params: { page: 1, itemsPerPage: 1 } } as any),
      pathologyService.getAll({ params: { page: 1, itemsPerPage: 1 } } as any),
      clinicalCaseService.getAll({ params: { page: 1, itemsPerPage: 8 } } as any),
      userService.getAll({ params: { page: 1, itemsPerPage: 1 } } as any)
    ]);

    const patientsData = patientsRes.data as HydraCollection;
    const examsData = examsRes.data as HydraCollection;
    const modalitiesData = modalitiesRes.data as HydraCollection;
    const pathologiesData = pathologiesRes.data as HydraCollection;
    const casesData = casesRes.data as HydraCollection;
    const usersData = usersRes.data as HydraCollection;

    stats.value = {
      patients: getTotal(patientsData),
      exams: getTotal(examsData),
      modalities: getTotal(modalitiesData),
      pathologies: getTotal(pathologiesData),
      clinicalCases: getTotal(casesData),
      users: getTotal(usersData)
    };

    recentCasesRaw.value = getMembers(casesData).slice(0, 8);
    await enrichRecentCases();
  } catch (e) {
    console.error(e);
    error.value = "Impossible de charger les données du tableau de bord.";
  } finally {
    isLoading.value = false;
  }
};

const cards = computed(() => [
  { label: 'Patients', value: stats.value.patients, icon: '🧑‍🤝‍🧑', to: '/patients' },
  { label: 'Examens', value: stats.value.exams, icon: '🧪', to: '/exams' },
  { label: 'Modalités', value: stats.value.modalities, icon: '🧭', to: '/modalities' },
  { label: 'Pathologies', value: stats.value.pathologies, icon: '🩺', to: '/pathologies' },
  { label: 'Cas cliniques', value: stats.value.clinicalCases, icon: '📁', to: '/cas-clinique' },
  { label: 'Utilisateurs', value: stats.value.users, icon: '🔐', to: '/users' }
]);

onMounted(fetchDashboard);
</script>

<template>
  <div class="dashboard-layout">
    <Sidebar />

    <main class="main-content">
      <TopBar />

      <div class="page-container">
        <div class="page-header">
          <div>
            <h1>Tableau de bord</h1>
            <p class="subtitle">Vue d’ensemble de E-RADIOLOGIE</p>
          </div>

          <button class="btn btn-secondary" @click="fetchDashboard" :disabled="isLoading">
            ↻ Actualiser
          </button>
        </div>

        <div v-if="isLoading" class="loading">Chargement des données…</div>
        <div v-else-if="error" class="alert error">⚠️ {{ error }}</div>

        <template v-else>
          <!-- Stats cards -->
          <div class="cards-grid">
            <router-link
              v-for="c in cards"
              :key="c.label"
              :to="c.to"
              class="stat-card"
            >
              <div class="stat-left">
                <div class="stat-icon">{{ c.icon }}</div>
                <div>
                  <div class="stat-label">{{ c.label }}</div>
                  <div class="stat-value">{{ c.value }}</div>
                </div>
              </div>
              <div class="stat-cta">→</div>
            </router-link>
          </div>

          <!-- Quick actions -->
          <div class="section">
            <div class="section-title">Actions rapides</div>
            <div class="actions">
              <router-link class="action" to="/patients/nouveau">+ Ajouter un patient</router-link>
              <router-link class="action" to="/exams/nouveau">+ Ajouter un examen</router-link>
              <router-link class="action" to="/modalities/nouveau">+ Ajouter une modalité</router-link>
              <router-link class="action" to="/cas-clinique/nouveau">+ Nouveau cas clinique</router-link>
              <router-link class="action" to="/users/nouveau">+ Nouvel utilisateur</router-link>
              <router-link class="action" to="/pathologies">Voir les pathologies</router-link>
            </div>
          </div>

          <!-- Recent clinical cases -->
          <div class="section">
            <div class="section-title">Derniers cas cliniques</div>

            <div v-if="recentCasesUi.length === 0" class="empty">
              Aucun cas clinique pour le moment.
            </div>

            <div v-else class="table-card">
              <table class="data-table">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Patient</th>
                  <th>Examen</th>
                  <th>Pathologie</th>
                  <th>Conclusion</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="cc in recentCasesUi" :key="cc.id">
                  <td class="fw-bold">#{{ cc.id }}</td>
                  <td><span class="chip">{{ cc.patientLabel }}</span></td>
                  <td><span class="chip">{{ cc.examLabel }}</span></td>
                  <td><span class="chip">{{ cc.pathologyLabel }}</span></td>
                  <td class="truncate">{{ cc.conclusion || '-' }}</td>
                </tr>
                </tbody>
              </table>

              <div class="table-footer">
                <router-link class="btn btn-primary" to="/cas-clinique">Voir tous les cas</router-link>
              </div>
            </div>
          </div>
        </template>
      </div>
    </main>
  </div>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables" as *;

.dashboard-layout { display: flex; min-height: 100vh; background-color: #f4f6f8; }
.main-content { flex: 1; display: flex; flex-direction: column; }
.page-container { padding: 2rem; margin-left: 260px; max-width: 1200px; }

.page-header {
  display: flex; justify-content: space-between; align-items: flex-start;
  gap: 12px; margin-bottom: 1.2rem;
}
.subtitle { color: $secondary; margin-top: 6px; }

.loading { padding: 1.5rem; color: $secondary; }
.alert { padding: 10px 12px; border-radius: 10px; font-weight: 700; }
.alert.error { background: rgba($danger, 0.10); color: $danger; }

.btn {
  border: none; cursor: pointer; border-radius: 10px;
  padding: 0.65rem 1.1rem; font-weight: 800;
  display: inline-flex; align-items: center; justify-content: center;
  text-decoration: none;
}
.btn-secondary { background: #e2e6ea; color: $text-color; }
.btn-primary { background: $primary; color: #fff; }

.cards-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 14px;
  margin: 1rem 0 1.6rem;
}
@media (max-width: 1100px) { .cards-grid { grid-template-columns: 1fr 1fr; } }
@media (max-width: 700px) { .cards-grid { grid-template-columns: 1fr; } }

.stat-card {
  background: white;
  border-radius: 14px;
  padding: 14px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.06);
  display: flex;
  justify-content: space-between;
  align-items: center;
  text-decoration: none;
  color: $text-color;
  border: 1px solid #eef2f6;
  transition: 0.15s ease;
}
.stat-card:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 16px rgba(0,0,0,0.08);
}
.stat-left { display: flex; align-items: center; gap: 12px; }
.stat-icon { font-size: 1.7rem; }
.stat-label { color: $secondary; font-weight: 700; }
.stat-value { font-size: 1.5rem; font-weight: 900; margin-top: 4px; }
.stat-cta { color: $secondary; font-weight: 900; }

.section { margin-top: 1.4rem; }
.section-title { font-weight: 900; margin-bottom: 10px; }

.actions {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}
.action {
  background: white;
  border: 1px solid #eef2f6;
  padding: 10px 12px;
  border-radius: 12px;
  text-decoration: none;
  color: $text-color;
  font-weight: 800;
  box-shadow: 0 1px 6px rgba(0,0,0,0.04);
  transition: 0.15s ease;
}
.action:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(0,0,0,0.06); }

.table-card {
  background: white;
  border-radius: 14px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.06);
  border: 1px solid #eef2f6;
  overflow: hidden;
}

.data-table { width: 100%; border-collapse: collapse; }
.data-table th, .data-table td { padding: 12px 14px; border-bottom: 1px solid #f0f0f0; text-align: left; }
.data-table th { background: #fafafa; color: $secondary; font-size: 0.85rem; text-transform: uppercase; }
.fw-bold { font-weight: 900; }

.chip {
  display: inline-block;
  padding: 4px 8px;
  border-radius: 999px;
  background: #eef2f7;
  font-size: 0.8rem;
  border: 1px solid #e6edf5;
}

.truncate {
  max-width: 420px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.table-footer {
  display: flex;
  justify-content: flex-end;
  padding: 12px 14px;
}

.empty { color: $secondary; padding: 12px 0; }
.hint { display: block; margin-top: 10px; color: $secondary; font-size: 0.85rem; }
</style>
