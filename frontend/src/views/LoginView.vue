<script setup lang="ts">
import { ref } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';

// Variables réactives pour le formulaire
const email = ref('');
const password = ref('');
const errorMessage = ref('');

// On récupère le store et le router
const authStore = useAuthStore();
const router = useRouter();

const handleLogin = async () => {
  errorMessage.value = ''; // Reset de l'erreur

  try {
    // 1. On tente le login via le store
    await authStore.login(email.value, password.value);

    // 2. Si ça marche (pas d'erreur levée), on redirige vers l'accueil
    console.log("Connexion réussie !");
    await router.push('/');

  } catch (error) {
    // 3. Si ça échoue, on affiche un message
    errorMessage.value = "Email ou mot de passe incorrect.";
  }
};
</script>

<template>
  <div class="login-container">
    <div class="card">
      <h1>Connexion</h1>
      <p class="subtitle">E-RADIOLOGIE</p>

      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label>Email</label>
          <input
            v-model="email"
            type="email"
            placeholder="exemple@mail.com"
            required
          />
        </div>

        <div class="form-group">
          <label>Mot de passe</label>
          <input
            v-model="password"
            type="password"
            placeholder="Votre mot de passe"
            required
          />
        </div>

        <p v-if="errorMessage" class="error-msg">{{ errorMessage }}</p>

        <button type="submit" class="btn-primary">Se connecter</button>
      </form>
    </div>
  </div>
</template>

<style scoped>
/* Un peu de style pour que ce soit propre */
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color: #f4f6f8;
}

.card {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
  width: 100%;
  max-width: 400px;
  text-align: center;
}

h1 { margin-bottom: 0.5rem; color: #333; }
.subtitle { color: #666; margin-bottom: 2rem; }

.form-group { margin-bottom: 1rem; text-align: left; }
label { display: block; margin-bottom: 0.5rem; font-size: 0.9rem; font-weight: bold; }
input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 6px;
  box-sizing: border-box;
}

.btn-primary {
  width: 100%;
  padding: 12px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: bold;
  margin-top: 1rem;
}
.btn-primary:hover { background-color: #0056b3; }

.error-msg { color: #dc3545; font-size: 0.9rem; margin-top: 1rem; }
</style>
