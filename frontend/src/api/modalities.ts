import apiClient from './axios';

export interface Modality {
  id: number;
  name: string;
  '@id': string; // L'IRI (ex: "/api/modalities/1")
}

interface ApiResponse<T> {
  'hydra:member': T[];
  'hydra:totalItems': number;
}

export default {
  // Récupérer toutes les modalités
  getAll() {
    return apiClient.get<ApiResponse<Modality>>('/modalities');
  },

  // Récupérer une seule modalité
  get(id: number) {
    return apiClient.get<Modality>(`/modalities/${id}`);
  },

  // Créer
  create(data: Partial<Modality>) {
    return apiClient.post<Modality>('/modalities', data);
  },

  // Modifier (Attention au header merge-patch)
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
