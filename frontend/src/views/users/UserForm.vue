<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';

import Sidebar from '@/components/layout/Sidebar.vue';
import TopBar from '@/components/layout/TopBar.vue';

import userService, { type User } from '@/api/users';

const route = useRoute();
const router = useRouter();

const id = computed(() => (route.params.id ? Number(route.params.id) : null));
const isEdit = computed(() => id.value !== null);

// Roles plus user-friendly
const ROLE_OPTIONS: { value: string; label: string; desc: string }[] = [
  { value: 'ROLE_USER', label: 'Utilisateur', desc: 'Accès standard à l’application' },
  { value: 'ROLE_ADMIN', label: 'Administrateur', desc: 'Accès complet (gestion & administration)' }
];

const form = ref<{
  email: string;
  firstName: string;
  lastName: string;
  roles: string[];
  plainPassword: string;
}>({
  email: '',
  firstName: '',
  lastName: '',
  roles: ['ROLE_USER'],
  plainPassword: ''
});

const isLoading = ref(false);
const successMsg = ref('');
const errorMsg = ref('');

// UI helpers
const showPassword = ref(false);

const fieldErrors = ref<Record<string, string>>({});

const isEmailValid = computed(() => {
  // simple regex enough for UI validation
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.value.email.trim());
});

const passwordScore = computed(() => {
  const p = form.value.plainPassword || '';
  if (!p) return 0;

  let score = 0;
  if (p.length >= 8) score++;
  if (p.length >= 12) score++;
  if (/[A-Z]/.test(p)) score++;
  if (/[0-9]/.test(p)) score++;
  if (/[^A-Za-z0-9]/.test(p)) score++;
  return Math.min(score, 5);
});

const passwordHint = computed(() => {
  if (!form.value.plainPassword) return '';
  if (passwordScore.value <= 2) return "Mot de passe faible (ajoute majuscules, chiffres, symboles).";
  if (passwordScore.value <= 4) return "Mot de passe correct.";
  return "Mot de passe fort ✅";
});

const formIsValid = computed(() => {
  if (!form.value.email.trim() || !isEmailValid.value) return false;
  if (!form.value.roles.length) return false;
  if (!isEdit.value && !form.value.plainPassword) return false;
  if (!isEdit.value && form.value.plainPassword.length < 6) return false;
  // en edit, si password renseigné, on impose min 6
  if (isEdit.value && form.value.plainPassword && form.value.plainPassword.length < 6) return false;
  return true;
});

const toggleRole = (role: string) => {
  const roles = new Set(form.value.roles);
  if (roles.has(role)) roles.delete(role);
  else roles.add(role);

  // Toujours garder ROLE_USER (optionnel, mais pratique)
  if (!roles.size) roles.add('ROLE_USER');

  form.value.roles = Array.from(roles);
};

const validate = () => {
  fieldErrors.value = {};
  errorMsg.value = '';
  successMsg.value = '';

  const email = form.value.email.trim();
  if (!email) fieldErrors.value.email = 'Email obligatoire.';
  else if (!isEmailValid.value) fieldErrors.value.email = 'Format email invalide.';

  if (!form.value.roles.length) fieldErrors.value.roles = 'Sélectionne au moins un rôle.';

  if (!isEdit.value) {
    if (!form.value.plainPassword) fieldErrors.value.plainPassword = 'Mot de passe obligatoire à la création.';
    else if (form.value.plainPassword.length < 6) fieldErrors.value.plainPassword = 'Minimum 6 caractères.';
  } else {
    if (form.value.plainPassword && form.value.plainPassword.length < 6) {
      fieldErrors.value.plainPassword = 'Minimum 6 caractères (si tu changes le mot de passe).';
    }
  }

  return Object.keys(fieldErrors.value).length === 0;
};

const loadUser = async () => {
  if (!isEdit.value || !id.value) return;

  isLoading.value = true;
  errorMsg.value = '';
  successMsg.value = '';

  try {
    const res = await userService.get(id.value);
    const data = res.data as any;

    form.value.email = data.email ?? '';
    form.value.firstName = data.firstName ?? '';
    form.value.lastName = data.lastName ?? '';
    form.value.roles = data.roles ?? ['ROLE_USER'];
    form.value.plainPassword = '';
  } catch (e) {
    console.error(e);
    errorMsg.value = "Impossible de charger l'utilisateur.";
  } finally {
    isLoading.value = false;
  }
};

const handleSubmit = async () => {
  if (!validate()) return;

  isLoading.value = true;
  errorMsg.value = '';
  successMsg.value = '';

  try {
    const payload: Partial<User> & { plainPassword?: string } = {
      email: form.value.email.trim(),
      firstName: form.value.firstName?.trim(),
      lastName: form.value.lastName?.trim(),
      roles: form.value.roles
    };

    if (form.value.plainPassword) {
      payload.plainPassword = form.value.plainPassword;
    }

    if (isEdit.value && id.value) {
      await userService.update(id.value, payload);
      successMsg.value = "Utilisateur mis à jour ✅";
    } else {
      await userService.create(payload);
      successMsg.value = "Utilisateur créé ✅";
    }

    // petit délai UX puis redirect
    setTimeout(() => router.push('/users'), 400);
  } catch (e: any) {
    console.error(e?.response?.data || e);

    if (e?.response?.data?.violations?.length) {
      errorMsg.value = e.response.data.violations
        .map((v: any) => `${v.propertyPath}: ${v.message}`)
        .join(' | ');
    } else {
      errorMsg.value = e?.response?.data?.detail || "Erreur lors de l'enregistrement de l'utilisateur.";
    }
  } finally {
    isLoading.value = false;
  }
};

onMounted(loadUser);
</script>

<template>
  <div class="dashboard-layout">
    <Sidebar />
    <main class="main-content">
      <TopBar />
      <div class="page-container">

        <div class="form-header">
          <div>
            <h1>{{ isEdit ? 'Modifier utilisateur' : 'Nouvel utilisateur' }}</h1>
            <p class="subtitle">
              {{ isEdit ? "Modifie les informations et les rôles." : "Crée un compte utilisateur." }}
            </p>
          </div>
          <router-link to="/users" class="btn btn-secondary">← Retour</router-link>
        </div>

        <div class="form-card">
          <form @submit.prevent="handleSubmit">

            <div v-if="errorMsg" class="alert error">⚠️ {{ errorMsg }}</div>
            <div v-if="successMsg" class="alert success">✅ {{ successMsg }}</div>

            <!-- Identité -->
            <div class="section">
              <div class="section-title">Informations</div>

              <div class="form-group">
                <label>Email <span class="req">*</span></label>
                <input
                  v-model="form.email"
                  type="email"
                  placeholder="email@exemple.com"
                  :class="{ invalid: !!fieldErrors.email }"
                />
                <small v-if="fieldErrors.email" class="field-error">{{ fieldErrors.email }}</small>
              </div>

              <div class="form-grid">
                <div class="form-group">
                  <label>Prénom</label>
                  <input v-model="form.firstName" type="text" placeholder="Issam" />
                </div>

                <div class="form-group">
                  <label>Nom</label>
                  <input v-model="form.lastName" type="text" placeholder="LAMMRI" />
                </div>
              </div>
            </div>

            <!-- Roles -->
            <div class="section">
              <div class="section-title">Rôles <span class="req">*</span></div>

              <div class="roles">
                <label
                  v-for="r in ROLE_OPTIONS"
                  :key="r.value"
                  class="role-card"
                  :class="{ active: form.roles.includes(r.value) }"
                >
                  <input
                    type="checkbox"
                    class="role-checkbox"
                    :checked="form.roles.includes(r.value)"
                    @change="toggleRole(r.value)"
                  />
                  <div class="role-text">
                    <div class="role-label">{{ r.label }}</div>
                    <div class="role-desc">{{ r.desc }}</div>
                  </div>
                </label>
              </div>

              <small v-if="fieldErrors.roles" class="field-error">{{ fieldErrors.roles }}</small>
              <small class="hint">Astuce : laisse “Utilisateur” pour un accès standard.</small>
            </div>

            <!-- Password -->
            <div class="section">
              <div class="section-title">Sécurité</div>

              <div class="form-group">
                <label>
                  {{ isEdit ? 'Nouveau mot de passe (optionnel)' : 'Mot de passe' }}
                  <span v-if="!isEdit" class="req">*</span>
                </label>

                <div class="password-row">
                  <input
                    v-model="form.plainPassword"
                    :type="showPassword ? 'text' : 'password'"
                    :placeholder="isEdit ? 'Laisse vide pour ne pas changer' : 'Minimum 6 caractères'"
                    :class="{ invalid: !!fieldErrors.plainPassword }"
                  />
                  <button type="button" class="btn btn-ghost" @click="showPassword = !showPassword">
                    {{ showPassword ? 'Masquer' : 'Afficher' }}
                  </button>
                </div>

                <div v-if="form.plainPassword" class="pwd-meter">
                  <div class="pwd-bar">
                    <div class="pwd-fill" :style="{ width: (passwordScore * 20) + '%' }"></div>
                  </div>
                  <small class="hint">{{ passwordHint }}</small>
                </div>

                <small v-if="fieldErrors.plainPassword" class="field-error">
                  {{ fieldErrors.plainPassword }}
                </small>

                <small v-if="isEdit" class="hint">
                  Si tu laisses vide, le mot de passe reste inchangé.
                </small>
              </div>
            </div>

            <!-- Actions -->
            <div class="form-actions">
              <router-link to="/users" class="btn btn-secondary">Annuler</router-link>

              <button type="submit" class="btn btn-primary" :disabled="isLoading || !formIsValid">
                <span v-if="isLoading">Enregistrement...</span>
                <span v-else>{{ isEdit ? 'Mettre à jour' : 'Créer' }}</span>
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

.dashboard-layout { display:flex; min-height:100vh; background:#f4f6f8; }
.main-content { flex:1; display:flex; flex-direction:column; }
.page-container { padding:2rem; margin-left:260px; max-width:980px; }

.form-header {
  display:flex;
  justify-content:space-between;
  align-items:flex-start;
  gap:16px;
  margin-bottom: 1rem;
}

.subtitle { color: $secondary; margin-top: 6px; }

.form-card {
  background:white;
  padding: 1.8rem;
  border-radius: 12px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}

.section {
  padding: 14px 0;
  border-bottom: 1px solid #f0f0f0;
}
.section:last-child { border-bottom: none; }

.section-title {
  font-weight: 700;
  margin-bottom: 10px;
  color: $text-color;
}

.form-group { margin-bottom: 1rem; display:flex; flex-direction:column; gap:6px; }

.form-grid {
  display:grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}
@media (max-width: 900px) { .form-grid { grid-template-columns: 1fr; } }

.req { color: $danger; font-weight: 700; }

input, select {
  width:100%;
  padding: 10px 12px;
  border:1px solid #ced4da;
  border-radius: 10px;
  transition: 0.15s ease;
  background: #fff;
}

input:focus {
  outline: none;
  border-color: rgba(90, 135, 239, 0.7);
  box-shadow: 0 0 0 4px rgba(90, 135, 239, 0.12);
}

.invalid {
  border-color: rgba($danger, 0.6) !important;
  box-shadow: 0 0 0 4px rgba($danger, 0.08);
}

.hint { font-size: 0.85rem; color: $secondary; }
.field-error { font-size: 0.85rem; color: $danger; }

.alert {
  padding: 10px 12px;
  border-radius: 10px;
  margin-bottom: 12px;
  font-weight: 600;
}
.alert.error { background: rgba($danger, 0.10); color: $danger; }
.alert.success { background: rgba(#2e7d32, 0.10); color: #2e7d32; }

.roles {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
}
@media (max-width: 900px) { .roles { grid-template-columns: 1fr; } }

.role-card {
  display:flex;
  gap: 10px;
  align-items:flex-start;
  padding: 12px;
  border-radius: 12px;
  border: 1px solid #e8edf3;
  background: #fafbfc;
  cursor: pointer;
  transition: 0.15s ease;
}

.role-card:hover { background: #f3f6fb; }
.role-card.active {
  border-color: rgba(90, 135, 239, 0.55);
  background: rgba(90, 135, 239, 0.08);
}

.role-checkbox { margin-top: 3px; }
.role-label { font-weight: 700; }
.role-desc { font-size: 0.85rem; color: $secondary; margin-top: 2px; }

.password-row {
  display:flex;
  gap: 10px;
  align-items:center;
}

.btn {
  border:none;
  cursor:pointer;
  border-radius: 10px;
  padding: 0.65rem 1.1rem;
  font-weight: 700;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  text-decoration:none;
}

.btn-primary { background: $primary; color: white; }
.btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }

.btn-secondary {
  background:#e2e6ea;
  color:$text-color;
}

.btn-ghost {
  background: #eef2f7;
  color: $text-color;
  white-space: nowrap;
}

.pwd-meter { margin-top: 8px; }
.pwd-bar {
  height: 8px;
  border-radius: 999px;
  background: #eef2f7;
  overflow: hidden;
}
.pwd-fill {
  height: 100%;
  background: rgba(90, 135, 239, 0.9);
  transition: 0.2s ease;
}

.form-actions {
  display:flex;
  justify-content:flex-end;
  gap: 12px;
  margin-top: 1.2rem;
  padding-top: 1rem;
}
</style>
