<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import Sidebar from '@/components/layout/Sidebar.vue';
import TopBar from '@/components/layout/TopBar.vue';
import patientService from '@/api/patients';

const router = useRouter();

// --- ÉTAT DU FORMULAIRE ---
const form = ref({
  age: '',
  gender: 'Femme', // Valeur par défaut
  history: ''
});

const isLoading = ref(false);
const error = ref('');

// --- SOUMISSION ---
const handleSubmit = async () => {
  isLoading.value = true;
  error.value = '';

  try {
    // On appelle le service de création
    // Note : On convertit l'âge en nombre entier pour être sûr
    await patientService.create({
      age: parseInt(form.value.age),
      gender: form.value.gender,
      history: form.value.history
    });

    // Succès : on redirige vers la liste
    await router.push('/patients');

  } catch (err) {
    console.error(err);
    error.value = "Une erreur est survenue lors de la création du patient.";
  } finally {
    isLoading.value = false;
  }
};
</script>

<template>
  <div class="dashboard-layout">
    <Sidebar />

    <main class="main-content">
      <TopBar />

      <div class="page-container">

        <div class="form-header">
          <h1>Nouveau Patient</h1>
          <p>Créez un nouveau dossier patient pour commencer à y ajouter des examens.</p>
        </div>

        <div class="form-card">
          <form @submit.prevent="handleSubmit">

            <div class="form-row">
              <div class="form-group half">
                <label>Âge du patient *</label>
                <input
                  v-model="form.age"
                  type="number"
                  min="0"
                  max="120"
                  required
                  placeholder="Ex: 45"
                />
              </div>

              <div class="form-group half">
                <label>Genre *</label>
                <select v-model="form.gender" required>
                  <option value="Femme">Femme</option>
                  <option value="Homme">Homme</option>
                  <option value="Autre">Autre</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label>Antécédents Médicaux / Historique</label>
              <textarea
                v-model="form.history"
                rows="5"
                placeholder="Ex: Diabète de type 2, hypertension, allergie à l'iode..."
              ></textarea>
              <small class="hint">Soyez précis, ces informations seront visibles sur les rapports.</small>
            </div>

            <div v-if="error" class="error-msg">
              ⚠️ {{ error }}
            </div>

            <div class="form-actions">
              <router-link to="/patients" class="btn btn-secondary">
                Annuler
              </router-link>

              <button type="submit" class="btn btn-primary" :disabled="isLoading">
                <span v-if="!isLoading">Créer le patient</span>
                <span v-else>Enregistrement...</span>
              </button>
            </div>

          </form>
        </div>

      </div>
    </main>
  </div>
</template>

<style scoped lang="scss">
@use "@/assets/scss/variables" as *;
@use "sass:color";

// Layout global
.dashboard-layout { display: flex; min-height: 100vh; background-color: #f4f6f8; }
.main-content { flex: 1; display: flex; flex-direction: column; }
.page-container { padding: 2rem; margin-left: 260px; max-width: 900px; }

.form-header {
  margin-bottom: 2rem;
  h1 { font-size: 1.8rem; color: $text-color; margin-bottom: 0.5rem; }
  p { color: $secondary; }
}

// Carte du formulaire
.form-card {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

// Mise en page du formulaire
.form-row {
  display: flex;
  gap: 20px;

  .half { flex: 1; }
}

// Styles spécifiques (les inputs sont déjà stylisés par _forms.scss globalement normalement)
// Mais on s'assure que textarea et select sont beaux
textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ced4da;
  border-radius: 6px;
  font-family: inherit;
  resize: vertical;

  &:focus {
    outline: none;
    border-color: $primary;
    box-shadow: 0 0 0 3px color.change($primary, $alpha: 0.2);
  }
}

.hint {
  display: block;
  margin-top: 5px;
  font-size: 0.85rem;
  color: #888;
}

// Actions (Boutons en bas)
.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 15px;
  margin-top: 2rem;
  padding-top: 1.5rem;
  border-top: 1px solid #f0f0f0;
}

.btn-secondary {
  background-color: #e2e6ea;
  color: $text-color;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.6rem 1.2rem;
  border-radius: 6px;
  font-weight: 600;

  &:hover { background-color: #dbe0e5; }
}

.error-msg {
  color: $danger;
  background-color: rgba($danger, 0.1);
  padding: 10px;
  border-radius: 6px;
  margin-bottom: 1rem;
}
</style>
