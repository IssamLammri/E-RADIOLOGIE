<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';

import Sidebar from '@/components/layout/Sidebar.vue';
import TopBar from '@/components/layout/TopBar.vue';

import clinicalCaseService from '@/api/clinicalCases';
import patientService, { type Patient } from '@/api/patients';
import examService, { type Exam } from '@/api/exams';
import pathologyService, { type Pathology } from '@/api/pathologies';

const route = useRoute();
const router = useRouter();

const API_HOST = import.meta.env.VITE_API_HOST || 'http://localhost:8090';

const id = computed(() => (route.params.id ? Number(route.params.id) : null));
const isEdit = computed(() => id.value !== null);

const form = ref({
  patient: '',
  exam: '',
  pathology: '',
  symptoms: '',
  images: '', // chemin retourné par backend, ex: /uploads/clinical-cases/xxx.jpg
  imageComment: '',
  conclusion: ''
});

const patientsList = ref<Patient[]>([]);
const examsList = ref<Exam[]>([]);
const pathologiesList = ref<Pathology[]>([]);

// Upload (drag & drop)
const selectedFile = ref<File | null>(null);
const isDragging = ref(false);
const inputFileRef = ref<HTMLInputElement | null>(null);
const previewUrl = ref<string>(''); // preview locale

const isLoading = ref(false);
const error = ref('');

// Helpers
const fullImageUrl = (path?: string) => {
  if (!path) return '';
  if (path.startsWith('http://') || path.startsWith('https://')) return path;
  return `${API_HOST}${path}`;
};

const setSelectedFile = (file: File | null) => {
  selectedFile.value = file;

  // reset preview précédente
  if (previewUrl.value) URL.revokeObjectURL(previewUrl.value);
  previewUrl.value = file ? URL.createObjectURL(file) : '';
};

const openFilePicker = () => {
  inputFileRef.value?.click();
};

const validateImageFile = (file: File) => {
  if (!file.type.startsWith('image/')) {
    error.value = "Veuillez sélectionner une image (jpg, png, webp...).";
    return false;
  }
  const MAX = 10 * 1024 * 1024; // 10MB
  if (file.size > MAX) {
    error.value = "Image trop grande (max 10MB).";
    return false;
  }
  return true;
};

const onFileChange = (e: Event) => {
  const input = e.target as HTMLInputElement;
  const file = input.files?.[0] ?? null;
  if (!file) return;

  error.value = '';
  if (!validateImageFile(file)) return;

  setSelectedFile(file);
};

const onDragOver = (e: DragEvent) => {
  e.preventDefault();
  isDragging.value = true;
};

const onDragLeave = () => {
  isDragging.value = false;
};

const onDrop = (e: DragEvent) => {
  e.preventDefault();
  isDragging.value = false;

  const file = e.dataTransfer?.files?.[0] ?? null;
  if (!file) return;

  error.value = '';
  if (!validateImageFile(file)) return;

  setSelectedFile(file);
};

const clearSelectedFile = () => {
  setSelectedFile(null);
  if (inputFileRef.value) inputFileRef.value.value = '';
};

// Data loading
const loadLists = async () => {
  try {
    const [pRes, eRes, paRes] = await Promise.all([
      patientService.getAll(),
      examService.getAll(),
      pathologyService.getAll()
    ]);

    const pData = pRes.data as any;
    const eData = eRes.data as any;
    const paData = paRes.data as any;

    patientsList.value = pData['hydra:member'] || pData.member || [];
    examsList.value = eData['hydra:member'] || eData.member || [];
    pathologiesList.value = paData['hydra:member'] || paData.member || [];
  } catch (e) {
    console.error(e);
    error.value = "Impossible de charger les listes (patients/examens/pathologies).";
  }
};

const loadClinicalCase = async () => {
  if (!isEdit.value || !id.value) return;

  isLoading.value = true;
  error.value = '';

  try {
    const res = await clinicalCaseService.get(id.value);
    const data = res.data as any;

    form.value = {
      patient: data.patient ?? '',
      exam: data.exam ?? '',
      pathology: data.pathology ?? '',
      symptoms: data.symptoms ?? '',
      images: data.images ?? '',
      imageComment: data.imageComment ?? '',
      conclusion: data.conclusion ?? ''
    };
    // NB: previewUrl reste vide, on affiche l'image sauvegardée via form.images
  } catch (e) {
    console.error(e);
    error.value = "Impossible de charger le cas clinique.";
  } finally {
    isLoading.value = false;
  }
};

// Submit
const handleSubmit = async () => {
  isLoading.value = true;
  error.value = '';

  if (!form.value.patient || !form.value.exam || !form.value.pathology) {
    error.value = "Patient, Examen et Pathologie sont obligatoires.";
    isLoading.value = false;
    return;
  }

  try {
    let clinicalCaseId: number;

    // 1) Create or Update
    if (isEdit.value && id.value) {
      await clinicalCaseService.update(id.value, {
        patient: form.value.patient,
        exam: form.value.exam,
        pathology: form.value.pathology,
        symptoms: form.value.symptoms,
        imageComment: form.value.imageComment,
        conclusion: form.value.conclusion
        // images géré par upload
      });
      clinicalCaseId = id.value;
    } else {
      const created = await clinicalCaseService.create({
        patient: form.value.patient,
        exam: form.value.exam,
        pathology: form.value.pathology,
        symptoms: form.value.symptoms,
        imageComment: form.value.imageComment,
        conclusion: form.value.conclusion
      });
      clinicalCaseId = created.data.id;
    }

    // 2) Upload image si sélectionnée
    if (selectedFile.value) {
      const uploadRes = await clinicalCaseService.uploadImage(clinicalCaseId, selectedFile.value);
      const imageUrl = (uploadRes.data as any).imageUrl;
      if (imageUrl) {
        form.value.images = imageUrl;
      }
    }

    await router.push('/cas-clinique');
  } catch (e: any) {
    console.error(e?.response?.data || e);
    error.value = e?.response?.data?.detail
      ? String(e.response.data.detail)
      : (isEdit.value ? "Erreur lors de la mise à jour du cas clinique." : "Erreur lors de la création du cas clinique.");
  } finally {
    isLoading.value = false;
  }
};

onMounted(async () => {
  await loadLists();
  await loadClinicalCase();
});
</script>


<template>
  <div class="dashboard-layout">
    <Sidebar />
    <main class="main-content">
      <TopBar />
      <div class="page-container">

        <div class="form-header">
          <h1>{{ isEdit ? 'Modifier un Cas Clinique' : 'Nouveau Cas Clinique' }}</h1>
        </div>

        <div class="form-card">
          <form @submit.prevent="handleSubmit">

            <div class="form-grid">
              <div class="form-group">
                <label>Patient *</label>
                <select v-model="form.patient" required>
                  <option value="" disabled>-- Choisir un patient --</option>
                  <option
                    v-for="p in patientsList"
                    :key="p.id"
                    :value="(p as any)['@id']"
                  >
                    Patient #{{ p.id }} - {{ p.gender }} - {{ p.age }} ans
                  </option>
                </select>
                <small v-if="patientsList.length === 0" class="hint warning">
                  ⚠️ Aucun patient trouvé. Ajoute d’abord un patient.
                </small>
              </div>

              <div class="form-group">
                <label>Examen *</label>
                <select v-model="form.exam" required>
                  <option value="" disabled>-- Choisir un examen --</option>
                  <option
                    v-for="e in examsList"
                    :key="e.id"
                    :value="(e as any)['@id']"
                  >
                    {{ e.name }}
                  </option>
                </select>
                <small v-if="examsList.length === 0" class="hint warning">
                  ⚠️ Aucun examen trouvé. Ajoute d’abord un examen.
                </small>
              </div>

              <div class="form-group">
                <label>Pathologie *</label>
                <select v-model="form.pathology" required>
                  <option value="" disabled>-- Choisir une pathologie --</option>
                  <option
                    v-for="pa in pathologiesList"
                    :key="pa.id"
                    :value="(pa as any)['@id']"
                  >
                    {{ pa.name }}
                  </option>
                </select>
                <small v-if="pathologiesList.length === 0" class="hint warning">
                  ⚠️ Aucune pathologie trouvée. Ajoute d’abord une pathologie.
                </small>
              </div>
            </div>

            <div class="form-group">
              <label>Symptômes</label>
              <textarea v-model="form.symptoms" rows="3" placeholder="Décrire les symptômes..." />
            </div>

            <div class="form-group">
              <label>Image (upload)</label>

              <!-- input caché -->
              <input
                ref="inputFileRef"
                type="file"
                accept="image/*"
                class="hidden-file-input"
                @change="onFileChange"
              />

              <!-- zone drag & drop -->
              <div
                class="dropzone"
                :class="{ dragging: isDragging }"
                @dragover="onDragOver"
                @dragleave="onDragLeave"
                @drop="onDrop"
                @click="openFilePicker"
              >
                <div class="dropzone-content">
                  <div class="dropzone-title">
                    Glisse-dépose une image ici
                  </div>
                  <div class="dropzone-sub">
                    ou clique pour sélectionner un fichier
                  </div>

                  <div v-if="selectedFile" class="file-pill">
                    <span class="file-name">{{ selectedFile.name }}</span>
                    <button type="button" class="file-remove" @click.stop="clearSelectedFile">✕</button>
                  </div>
                </div>
              </div>

              <!-- aperçu (priorité au fichier en cours) -->
              <div class="preview" v-if="previewUrl || form.images">
                <img
                  v-if="previewUrl"
                  :src="previewUrl"
                  alt="preview local"
                />

                <img
                  v-else
                  :src="fullImageUrl(form.images)"
                  alt="preview saved"
                />

                <div class="preview-path" v-if="form.images && !previewUrl">
                  {{ form.images }}
                </div>
              </div>
            </div>


            <div class="form-group">
              <label>Commentaire image</label>
              <textarea v-model="form.imageComment" rows="2" />
            </div>

            <div class="form-group">
              <label>Conclusion</label>
              <textarea v-model="form.conclusion" rows="3" />
            </div>

            <div v-if="error" class="error-msg">⚠️ {{ error }}</div>

            <div class="form-actions">
              <router-link to="/cas-clinique" class="btn btn-secondary">Annuler</router-link>
              <button type="submit" class="btn btn-primary" :disabled="isLoading">
                {{ isEdit ? 'Mettre à jour' : 'Créer' }}
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

.dashboard-layout { display: flex; min-height: 100vh; background-color: #f4f6f8; }
.main-content { flex: 1; display: flex; flex-direction: column; }
.page-container { padding: 2rem; margin-left: 260px; max-width: 980px; }

.form-card {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.form-header h1 { margin-bottom: 1rem; }

.form-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
  margin-bottom: 1rem;
}

@media (max-width: 1100px) {
  .form-grid { grid-template-columns: 1fr; }
}

.form-group { margin-bottom: 1rem; display: flex; flex-direction: column; gap: 6px; }
textarea, input, select { width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 6px; }

.hint { font-size: 0.85rem; color: $secondary; }
.warning { color: orange; }

.preview {
  margin-top: 10px;
  border: 1px solid #eee;
  background: #fafafa;
  border-radius: 8px;
  padding: 10px;
  max-width: 420px;
}
.preview-title { font-weight: 600; margin-bottom: 8px; }
.preview img { width: 100%; height: auto; border-radius: 6px; border: 1px solid #eee; }
.preview-path { margin-top: 6px; font-size: 0.8rem; color: $secondary; word-break: break-all; }

.form-actions {
  display: flex; justify-content: flex-end; gap: 15px;
  margin-top: 2rem; border-top: 1px solid #f0f0f0; padding-top: 1.5rem;
}

.btn-secondary {
  background: #e2e6ea;
  color: $text-color;
  text-decoration: none;
  padding: 0.6rem 1.2rem;
  border-radius: 6px;
  display: inline-flex;
  align-items: center;
}

.error-msg {
  color: $danger;
  background: rgba($danger, 0.1);
  padding: 10px;
  border-radius: 6px;
  margin-bottom: 1rem;
}
.hidden-file-input {
  display: none;
}

.dropzone {
  border: 2px dashed #ced4da;
  border-radius: 10px;
  padding: 18px;
  background: #fafafa;
  cursor: pointer;
  transition: 0.15s ease;
}

.dropzone:hover {
  background: #f3f5f7;
}

.dropzone.dragging {
  border-color: #5b8def;
  background: #eef5ff;
}

.dropzone-content {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.dropzone-title {
  font-weight: 700;
}

.dropzone-sub {
  color: $secondary;
  font-size: 0.9rem;
}

.file-pill {
  margin-top: 8px;
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background: #eef2f7;
  border: 1px solid #e3e7ee;
  border-radius: 999px;
  padding: 6px 10px;
  width: fit-content;
}

.file-name {
  font-size: 0.9rem;
}

.file-remove {
  border: none;
  background: transparent;
  cursor: pointer;
  font-size: 1rem;
  line-height: 1;
  padding: 0 4px;
}

</style>
