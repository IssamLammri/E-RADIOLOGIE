// frontend/src/stores/auth.ts
import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import apiClient from '../api/axios';
import { useRouter } from 'vue-router';

export const useAuthStore = defineStore('auth', () => {
  // On initialise le token avec ce qui est stocké dans le navigateur (s'il y en a un)
  const token = ref<string | null>(localStorage.getItem('token'));

  // Cette variable calculée nous dit instantanément si l'user est connecté
  const isAuthenticated = computed(() => !!token.value);

  // --- ACTION : Se connecter ---
  async function login(email: string, password: string) {
    try {
      // On envoie la requête (l'URL de base est déjà configurée dans axios)
      const response = await apiClient.post('/login_check', {
        email,
        password
      });

      const newToken = response.data.token;

      // On met à jour le store ET le stockage local
      token.value = newToken;
      localStorage.setItem('token', newToken);

      return true; // Succès
    } catch (error) {
      console.error('Erreur lors du login :', error);
      throw error; // On renvoie l'erreur pour l'afficher dans le formulaire
    }
  }

  // --- ACTION : Se déconnecter ---
  function logout() {
    token.value = null;
    localStorage.removeItem('token');
    // Note : La redirection se fera souvent côté composant ou router
  }

  return {
    token,
    isAuthenticated,
    login,
    logout
  };
});
