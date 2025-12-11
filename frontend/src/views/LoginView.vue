<script setup lang="ts">
import { ref } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';

// 1. IMPORT DU LOGO (Vite va gérer le chemin automatiquement)
import logoImg from '@/assets/images/logo_e-radiologie.png';

// Variables réactives
const email = ref('');
const password = ref('');
const errorMessage = ref('');
const isLoading = ref(false);

const authStore = useAuthStore();
const router = useRouter();

const handleLogin = async () => {
  errorMessage.value = '';
  isLoading.value = true;

  try {
    await authStore.login(email.value, password.value);
    setTimeout(async () => {
      await router.push('/');
    }, 500);
  } catch (error) {
    errorMessage.value = "Identifiants incorrects. Veuillez réessayer.";
    isLoading.value = false;
  }
};
</script>

<template>
  <div class="login-wrapper">

    <div class="login-visual">
      <div class="overlay">
        <div class="testimonial">
          <p class="quote">"E-RADIOLOGIE a révolutionné notre approche du diagnostic. Une plateforme intuitive, fiable et parfaitement adaptée aux besoins des professionnels."</p>
          <div class="author">
            <span class="name">Mounib BOUMARTA</span>
            <span class="role">Radiologue, CHU d'Alger</span>
          </div>
        </div>
      </div>
    </div>

    <div class="login-form-container">
      <div class="form-content">
        <div class="header">
          <img :src="logoImg" alt="Logo E-Radiologie" class="logo-img" />
          <p class="subtitle">Portail de connexion sécurisé</p>
        </div>

        <form @submit.prevent="handleLogin">
          <div class="form-group">
            <label>Adresse Email</label>
            <input
              v-model="email"
              type="email"
              placeholder="nom@hopital.fr"
              required
              :disabled="isLoading"
            />
          </div>

          <div class="form-group">
            <div class="label-row">
              <label>Mot de passe</label>
              <a href="#" class="forgot-password">Oublié ?</a>
            </div>
            <input
              v-model="password"
              type="password"
              placeholder="••••••••"
              required
              :disabled="isLoading"
            />
          </div>

          <p v-if="errorMessage" class="error-msg">
            ⚠️ {{ errorMessage }}
          </p>

          <button type="submit" class="btn btn-primary btn-block" :disabled="isLoading">
            <span v-if="!isLoading">Se connecter au portail</span>
            <span v-else>Connexion en cours...</span>
          </button>
        </form>

        <div class="footer">
          <p>Vous n'avez pas de compte ? <a href="#">Contacter l'administrateur</a></p>
          <p class="security-note">🔒 Connexion chiffrée de bout en bout</p>
        </div>
      </div>
    </div>

  </div>
</template>

<style scoped lang="scss">
.header {
  .logo-img {
    max-width: 150px; // Ajustez cette valeur selon la taille souhaitée
    height: auto;
    margin-bottom: 1rem;
    display: block;
    margin-left: auto;
    margin-right: auto;
  }
}
</style>
