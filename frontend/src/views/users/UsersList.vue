<script setup lang="ts">
import { ref, onMounted } from 'vue';
import Sidebar from '@/components/layout/Sidebar.vue';
import TopBar from '@/components/layout/TopBar.vue';
import userService, { type User } from '@/api/users';

const users = ref<User[]>([]);
const isLoading = ref(true);
const error = ref('');

const fetchUsers = async () => {
  isLoading.value = true;
  error.value = '';
  try {
    const res = await userService.getAll();
    const data = res.data as any;
    users.value = data['hydra:member'] || data.member || [];
  } catch (e) {
    console.error(e);
    error.value = "Impossible de charger les utilisateurs.";
  } finally {
    isLoading.value = false;
  }
};

const handleDelete = async (id: number) => {
  if (!confirm("Supprimer cet utilisateur ?")) return;
  try {
    await userService.delete(id);
    users.value = users.value.filter(u => u.id !== id);
  } catch (e) {
    alert("Erreur lors de la suppression.");
  }
};

const rolesLabel = (roles?: string[]) => roles?.length ? roles.join(', ') : '-';

onMounted(fetchUsers);
</script>

<template>
  <div class="dashboard-layout">
    <Sidebar />
    <main class="main-content">
      <TopBar />
      <div class="page-container">

        <div class="page-header">
          <div>
            <h1>Utilisateurs</h1>
            <p class="subtitle">Gestion des comptes</p>
          </div>
          <router-link to="/users/nouveau" class="btn btn-primary">+ Nouvel Utilisateur</router-link>
        </div>

        <div v-if="isLoading" class="loading-state">Chargement...</div>
        <div v-else-if="error" class="error-msg">⚠️ {{ error }}</div>

        <div v-else class="table-card">
          <table class="data-table">
            <thead>
            <tr>
              <th>Email</th>
              <th>Nom</th>
              <th>Rôles</th>
              <th class="actions-col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="u in users" :key="u.id">
              <td class="fw-bold">{{ u.email }}</td>
              <td>{{ (u.firstName || '') + ' ' + (u.lastName || '') }}</td>
              <td><span class="badge">{{ rolesLabel(u.roles) }}</span></td>
              <td class="actions">
                <router-link class="btn-icon" :to="`/users/edit/${u.id}`">✏️</router-link>
                <button class="btn-icon delete" @click="handleDelete(u.id)">🗑️</button>
              </td>
            </tr>
            <tr v-if="users.length === 0">
              <td colspan="4" class="empty-state">Aucun utilisateur trouvé.</td>
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

.dashboard-layout { display:flex; min-height:100vh; background:#f4f6f8; }
.main-content { flex:1; display:flex; flex-direction:column; }
.page-container { padding:2rem; margin-left:260px; }

.page-header { display:flex; justify-content:space-between; margin-bottom:2rem;
  h1 { margin-bottom:0.2rem; }
  .subtitle { color:$secondary; }
}

.table-card { background:white; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.05); overflow:hidden; }

.data-table { width:100%; border-collapse:collapse;
  th, td { padding:1rem; border-bottom:1px solid #f0f0f0; text-align:left; }
  th { background:#fafafa; color:$secondary; font-size:0.85rem; text-transform:uppercase; }
}

.fw-bold { font-weight:600; color:$primary; }
.badge { background:#eef2f7; padding:4px 8px; border-radius:4px; font-size:0.8rem; }

.actions { text-align:right; display:flex; justify-content:flex-end; gap:10px; }
.btn-icon { background:none; border:none; cursor:pointer; font-size:1.1rem; text-decoration:none; padding:6px 8px; border-radius:6px;
  &:hover { background:#eee; }
}

.loading-state, .empty-state { text-align:center; padding:2rem; color:$secondary; }
.error-msg { color:$danger; }
</style>
