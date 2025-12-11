<script setup lang="ts">
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const handleLogout = () => {
  // 1. On nettoie le token
  authStore.logout();
  // 2. On redirige vers le login
  router.push('/login');
};
</script>

<template>
  <div class="home-container">
    <h1>Bienvenue sur E-Radiologie 🏥</h1>
    <p>Vous êtes connecté en tant qu'utilisateur sécurisé.</p>

    <div class="status">
      Statut du Token : <span class="valid">VALIDE</span>
    </div>

    <button @click="handleLogout" class="btn-logout">Se déconnecter</button>
  </div>
</template>

<style scoped>
.home-container {
  max-width: 800px;
  margin: 50px auto;
  text-align: center;
  font-family: sans-serif;
}
.status { margin: 20px 0; font-size: 1.2rem; }
.valid { color: #28a745; font-weight: bold; }

.btn-logout {
  padding: 10px 20px;
  background-color: #dc3545;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}
.btn-logout:hover { background-color: #c82333; }
</style>
