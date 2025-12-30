import apiClient from './axios';
import type { AxiosRequestConfig } from 'axios';

export interface ClinicalCase {
  id: number;

  patient: string;   // IRI: "/api/patients/1"
  exam: string;      // IRI: "/api/exams/1"
  pathology: string; // IRI: "/api/pathologies/1"

  symptoms?: string;
  images?: string;        // chemin retourné par backend: "/uploads/clinical-cases/xxx.jpg"
  imageComment?: string;
  conclusion?: string;
}

export interface ApiResponse<T> {
  'hydra:member'?: T[];
  member?: T[];
  'hydra:totalItems'?: number;
  totalItems?: number;
}

export default {
  // ✅ Get collection avec params optionnels
  getAll(config?: AxiosRequestConfig) {
    return apiClient.get<ApiResponse<ClinicalCase>>('/clinical_cases', config);
  },

  get(id: number) {
    return apiClient.get<ClinicalCase>(`/clinical_cases/${id}`);
  },

  create(data: Partial<ClinicalCase>) {
    return apiClient.post<ClinicalCase>('/clinical_cases', data);
  },

  update(id: number, data: Partial<ClinicalCase>) {
    return apiClient.patch<ClinicalCase>(`/clinical_cases/${id}`, data, {
      headers: { 'Content-Type': 'application/merge-patch+json' }
    });
  },

  delete(id: number) {
    return apiClient.delete(`/clinical_cases/${id}`);
  },

  // ✅ Upload image
  // Le backend renvoie généralement: { imageUrl: "/uploads/clinical-cases/xxx.jpg" }
  uploadImage(
    id: number,
    file: File,
    options?: {
      onProgress?: (percent: number) => void;
    }
  ) {
    const formData = new FormData();
    formData.append('file', file);

    return apiClient.post<{ imageUrl: string }>(`/clinical_cases/${id}/image`, formData, {
      // Axios mettra le boundary tout seul, pas obligé, mais OK de le laisser
      headers: { 'Content-Type': 'multipart/form-data' },

      // optionnel: barre de progression
      onUploadProgress: (evt) => {
        if (!options?.onProgress) return;
        const total = evt.total ?? 0;
        if (!total) return;
        const percent = Math.round((evt.loaded * 100) / total);
        options.onProgress(percent);
      }
    });
  }
};
