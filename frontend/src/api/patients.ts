import apiClient from './axios';
import type { AxiosRequestConfig } from 'axios';

// Définition du type Patient selon votre API
export interface Patient {
  id: number;
  age: number;
  gender: string; // "Homme", "Femme", "Autre"...
  history?: string; // Antécédents médicaux
  clinicalCases?: string[]; // Liens vers les cas (ex: "/api/clinical_cases/1")
}

// Réponse type d'API Platform (Hydra)
export interface ApiResponse<T> {
  'hydra:member'?: T[];
  member?: T[];
  'hydra:totalItems'?: number;
  totalItems?: number;
}

export default {
  // Récupérer tous les patients (avec params optionnels)
  getAll(config?: AxiosRequestConfig) {
    return apiClient.get<ApiResponse<Patient>>('/patients', config);
  },

  // Récupérer un seul patient
  get(id: number) {
    return apiClient.get<Patient>(`/patients/${id}`);
  },

  // Créer un patient
  create(data: Partial<Patient>) {
    return apiClient.post<Patient>('/patients', data);
  },

  // Modifier un patient
  update(id: number, data: Partial<Patient>) {
    return apiClient.patch<Patient>(`/patients/${id}`, data, {
      headers: {
        'Content-Type': 'application/merge-patch+json'
      }
    });
  },

  // Supprimer un patient
  delete(id: number) {
    return apiClient.delete(`/patients/${id}`);
  }
};
