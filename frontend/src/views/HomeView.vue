<script setup lang="ts">
import Sidebar from '@/components/layout/Sidebar.vue';
import TopBar from '@/components/layout/TopBar.vue';
</script>

<template>
  <div class="dashboard-layout">
    <Sidebar />

    <main class="main-content">
      <TopBar />

      <div class="page-container">
        <h1>Tableau de bord</h1>
        <p>Bienvenue sur votre espace E-RADIOLOGIE.</p>

        <div class="content-placeholder">
          <p>Sélectionnez un menu à gauche pour commencer.</p>
        </div>
      </div>
    </main>
  </div>
</template>

<style scoped lang="scss">
.dashboard-layout {
  display: flex;
  min-height: 100vh;
  background-color: #f4f6f8; // Fond gris clair global
}

.main-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  margin-left: 0; // La marge est gérée par le padding du TopBar ou ici
}

.page-container {
  padding: 2rem;
  margin-left: 260px; // Indispensable pour ne pas passer sous la sidebar
  margin-top: 0;
}

.content-placeholder {
  margin-top: 2rem;
  padding: 3rem;
  border: 2px dashed #ccc;
  border-radius: 8px;
  text-align: center;
  color: #888;
}
</style>
