import apiClient from './axios';
import type { AxiosRequestConfig } from 'axios';

export interface Modality {
  id: number;
  name: string;
  '@id': string; // IRI (ex: "/api/modalities/1")
}

export interface ApiResponse<T> {
  'hydra:member'?: T[];
  member?: T[];
  'hydra:totalItems'?: number;
  totalItems?: number;
}

export default {
  // Récupérer toutes les modalités (params optionnels)
  getAll(config?: AxiosRequestConfig) {
    return apiClient.get<ApiResponse<Modality>>('/modalities', config);
  },

  // Récupérer une seule modalité
  get(id: number) {
    return apiClient.get<Modality>(`/modalities/${id}`);
  },

  // Créer
  create(data: Partial<Modality>) {
    return apiClient.post<Modality>('/modalities', data);
  },

  // Modifier (merge-patch)
  update(id: number, data: Partial<Modality>) {
    return apiClient.patch<Modality>(`/modalities/${id}`, data, {
      headers: { 'Content-Type': 'application/merge-patch+json' }
    });
  },

  // Supprimer
  delete(id: number) {
    return apiClient.delete(`/modalities/${id}`);
  }
};
