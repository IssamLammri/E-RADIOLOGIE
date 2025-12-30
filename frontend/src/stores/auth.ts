// frontend/src/stores/auth.ts
import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import apiClient from '../api/axios';

export type AuthUser = {
  id?: number;
  email?: string;
  firstName?: string;
  lastName?: string;
  roles?: string[];
};

export const useAuthStore = defineStore('auth', () => {
  // --- STATE ---
  const token = ref<string | null>(localStorage.getItem('token'));
  const user = ref<AuthUser | null>(
    localStorage.getItem('auth_user')
      ? JSON.parse(localStorage.getItem('auth_user') as string)
      : null
  );

  // --- GETTERS ---
  const isAuthenticated = computed(() => !!token.value);

  const fullName = computed(() => {
    if (!user.value) return '';
    const fn = user.value.firstName ?? '';
    const ln = user.value.lastName ?? '';
    const name = `${fn} ${ln}`.trim();
    return name.length ? name : (user.value.email ?? '');
  });

  const mainRole = computed(() => {
    const roles = user.value?.roles ?? [];
    if (roles.includes('ROLE_ADMIN')) return 'Admin';
    if (roles.includes('ROLE_USER')) return 'Utilisateur';
    return roles[0] ? roles[0].replace('ROLE_', '') : '';
  });

  // --- HELPERS ---
  function setToken(newToken: string | null) {
    token.value = newToken;
    if (newToken) localStorage.setItem('token', newToken);
    else localStorage.removeItem('token');
  }

  function setUser(newUser: AuthUser | null) {
    user.value = newUser;
    if (newUser) localStorage.setItem('auth_user', JSON.stringify(newUser));
    else localStorage.removeItem('auth_user');
  }

  // --- ACTIONS ---
  async function login(email: string, password: string) {
    try {
      const response = await apiClient.post('/login_check', { email, password });

      const newToken = response.data.token as string;
      setToken(newToken);

      // ✅ récupérer les infos user connecté
      await fetchMe();

      return true;
    } catch (error) {
      console.error('Erreur lors du login :', error);
      throw error;
    }
  }

  async function fetchMe() {
    // nécessite un backend /api/me
    const res = await apiClient.get('/me');
    setUser(res.data);
    return res.data;
  }

  async function initAuth() {
    // À appeler au démarrage de l'app si token existant
    if (!token.value) {
      setUser(null);
      return;
    }

    // si user déjà en cache local, on peut le garder,
    // mais on tente de le rafraîchir (au cas où roles/champs changent)
    try {
      await fetchMe();
    } catch (e) {
      // token invalide/expiré => on purge tout
      logout();
    }
  }

  function logout() {
    setToken(null);
    setUser(null);
  }

  return {
    // state
    token,
    user,

    // getters
    isAuthenticated,
    fullName,
    mainRole,

    // actions
    login,
    fetchMe,
    initAuth,
    logout
  };
});
