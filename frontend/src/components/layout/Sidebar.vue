<script setup lang="ts">
import logoImg from '@/assets/images/logo_e-radiologie.png'; // Assurez-vous que le chemin est bon
</script>

<template>
  <aside class="sidebar">
    <div class="logo-container">
      <img :src="logoImg" alt="E-Radiologie" />
    </div>

    <nav class="nav-links">
      <router-link to="/cas-clinique" class="nav-item">
        <span class="icon">📁</span> Cas Cliniques
      </router-link>

      <router-link to="/exams" class="nav-item">
        <span class="icon">🩻</span> Examens
      </router-link>

      <router-link to="/pathologies" class="nav-item">
        <span class="icon">🦠</span> Pathologies
      </router-link>

      <router-link to="/patients" class="nav-item">
        <span class="icon">👥</span> Patients
      </router-link>

      <router-link to="/modalities" class="nav-item">
        <span class="icon">🏷️</span> Modalités
      </router-link>

      <div class="divider"></div>

      <router-link to="/users" class="nav-item">
        <span class="icon">⚙️</span> Utilisateurs
      </router-link>
    </nav>
  </aside>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables" as *;
@use "sass:color";

.sidebar {
  width: 260px;
  background-color: white;
  height: 100vh;
  border-right: 1px solid #e0e0e0;
  display: flex;
  flex-direction: column;
  position: fixed; // Fixé à gauche
  left: 0;
  top: 0;
}

.logo-container {
  padding: 1.5rem;
  text-align: center;
  border-bottom: 1px solid #f0f0f0;

  img {
    max-width: 120px;
    height: auto;
  }
}

.nav-links {
  padding: 1rem;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.nav-item {
  display: flex;
  align-items: center;
  padding: 0.8rem 1rem;
  text-decoration: none;
  color: $text-color;
  border-radius: 8px;
  font-weight: 500;
  transition: all 0.2s;

  .icon {
    margin-right: 12px;
    font-size: 1.2rem;
  }

  &:hover {
    background-color: color.change($primary, $alpha: 0.05);
    color: $primary;
  }

  // Classe ajoutée automatiquement par Vue Router quand le lien est actif
  &.router-link-active {
    background-color: color.change($primary, $alpha: 0.1);
    color: $primary;
    font-weight: bold;
  }
}

.divider {
  height: 1px;
  background-color: #e0e0e0;
  margin: 1rem 0;
}
</style>
