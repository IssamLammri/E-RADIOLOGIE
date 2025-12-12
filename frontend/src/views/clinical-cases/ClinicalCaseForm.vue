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
        <h1>Nouveau Cas Clinique</h1>
        <p>Le formulaire sera ici bientôt...</p>
        <router-link to="/cas-clinique" class="btn btn-secondary">Retour</router-link>
      </div>
    </main>
  </div>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables" as *;
.dashboard-layout { display: flex; min-height: 100vh; background-color: #f4f6f8; }
.main-content { flex: 1; display: flex; flex-direction: column; }
.page-container { padding: 2rem; margin-left: 260px; }
.btn-secondary { margin-top: 20px; display: inline-block; text-decoration: none; color: $text-color; }
</style>
