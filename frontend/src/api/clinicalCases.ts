import apiClient from './axios';

// Définition du type selon vos champs backend
export interface ClinicalCase {
  id: number;
  title: string;
  description?: string;
  status: string; // Ex: "Brouillon", "Publié"
  createdAt: string;
}

// Réponse type d'API Platform (Hydra)
interface ApiResponse<T> {
  'hydra:member': T[];
  'hydra:totalItems': number;
}

export default {
  // Récupérer tous les cas
  getAll() {
    return apiClient.get<ApiResponse<ClinicalCase>>('/clinical_cases');
  },

  // Récupérer un seul cas
  get(id: number) {
    return apiClient.get<ClinicalCase>(`/clinical_cases/${id}`);
  },

  // Créer un cas
  create(data: Partial<ClinicalCase>) {
    return apiClient.post<ClinicalCase>('/clinical_cases', data);
  },

  // Modifier un cas
  update(id: number, data: Partial<ClinicalCase>) {
    return apiClient.patch<ClinicalCase>(`/clinical_cases/${id}`, data);
  },

  // Supprimer un cas
  delete(id: number) {
    return apiClient.delete(`/clinical_cases/${id}`);
  }
};
