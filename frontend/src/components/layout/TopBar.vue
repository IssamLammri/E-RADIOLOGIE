<script setup lang="ts">
import { computed, ref, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const isDropdownOpen = ref(false);

const displayName = computed(() => {
  const n = authStore.fullName;
  return n && n.length ? n : (authStore.user?.email ?? 'Utilisateur');
});

const displayRole = computed(() => authStore.mainRole || '');

const avatarUrl = computed(() => {
  const name = encodeURIComponent(displayName.value || 'User');
  return `https://ui-avatars.com/api/?name=${name}&background=0D8ABC&color=fff`;
});

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};

const handleLogout = () => {
  authStore.logout();
  router.push('/login');
};

// Optionnel : si tu arrives sur une page avec token mais user pas chargé
onMounted(() => {
  if (authStore.isAuthenticated && !authStore.user) {
    authStore.fetchMe().catch(() => authStore.logout());
  }
});
</script>

<template>
  <header class="top-bar">
    <div class="search-container">
      <span class="search-icon">🔍</span>
      <input type="text" placeholder="Rechercher un patient, un dossier..." />
    </div>

    <div class="user-profile" @click="toggleDropdown">
      <div class="user-info">
        <span class="name">{{ displayName }}</span>
        <span class="role">{{ displayRole }}</span>
      </div>

      <div class="avatar">
        <img :src="avatarUrl" alt="User" />
      </div>

      <div v-if="isDropdownOpen" class="dropdown-menu" @click.stop>
        <a href="#" class="dropdown-item">Mon Profil</a>
        <a href="#" class="dropdown-item">Paramètres</a>
        <div class="divider"></div>
        <button @click="handleLogout" class="dropdown-item logout">
          Déconnexion
        </button>
      </div>
    </div>
  </header>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables" as *;

.top-bar {
  height: 70px;
  background-color: white;
  border-bottom: 1px solid #e0e0e0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 2rem;
  margin-left: 260px;
}

.search-container {
  display: flex;
  align-items: center;
  background-color: #f4f6f8;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  width: 400px;

  .search-icon { color: #888; margin-right: 10px; }
  input { border: none; background: transparent; outline: none; width: 100%; color: $text-color; }
}

.user-profile {
  display: flex;
  align-items: center;
  gap: 1rem;
  cursor: pointer;
  position: relative;

  .user-info {
    text-align: right;
    .name { display: block; font-weight: bold; font-size: 0.9rem; }
    .role { display: block; font-size: 0.8rem; color: $secondary; }
  }

  .avatar img {
    width: 40px; height: 40px; border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  }
}

.dropdown-menu {
  position: absolute;
  top: 120%;
  right: 0;
  width: 200px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  padding: 0.5rem 0;
  z-index: 100;
  border: 1px solid #eee;

  .dropdown-item {
    display: block;
    padding: 0.8rem 1.2rem;
    color: $text-color;
    text-decoration: none;
    font-size: 0.9rem;
    transition: background 0.2s;
    text-align: left;
    width: 100%;
    border: none;
    background: none;
    cursor: pointer;

    &:hover { background-color: #f4f6f8; }
    &.logout { color: $danger; &:hover { background-color: rgba($danger, 0.05); } }
  }

  .divider { height: 1px; background: #eee; margin: 0.5rem 0; }
}
</style>
