<script setup lang="ts">
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const isDropdownOpen = ref(false);

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};

const handleLogout = () => {
  authStore.logout();
  router.push('/login');
};
</script>

<template>
  <header class="top-bar">
    <div class="search-container">
      <span class="search-icon">🔍</span>
      <input type="text" placeholder="Rechercher un patient, un dossier..." />
    </div>

    <div class="user-profile" @click="toggleDropdown">
      <div class="user-info">
        <span class="name">Dr. Radiologue</span>
        <span class="role">Admin</span>
      </div>
      <div class="avatar">
        <img src="https://ui-avatars.com/api/?name=Dr+Radiologue&background=0D8ABC&color=fff" alt="User" />
      </div>

      <div v-if="isDropdownOpen" class="dropdown-menu">
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
  margin-left: 260px; // Laisse la place à la sidebar
}

.search-container {
  display: flex;
  align-items: center;
  background-color: #f4f6f8;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  width: 400px;

  .search-icon { color: #888; margin-right: 10px; }

  input {
    border: none;
    background: transparent;
    outline: none;
    width: 100%;
    color: $text-color;
  }
}

.user-profile {
  display: flex;
  align-items: center;
  gap: 1rem;
  cursor: pointer;
  position: relative; // Pour positionner le dropdown

  .user-info {
    text-align: right;
    .name { display: block; font-weight: bold; font-size: 0.9rem; }
    .role { display: block; font-size: 0.8rem; color: $secondary; }
  }

  .avatar img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  }
}

.dropdown-menu {
  position: absolute;
  top: 120%; // Juste en dessous
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
