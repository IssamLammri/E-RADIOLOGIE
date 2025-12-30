import apiClient from './axios';
import type { AxiosRequestConfig } from 'axios';

export interface Pathology {
  id: number;
  name: string;

  introduction?: string;
  positiveDiagnosis?: string;
  etiologicalDiagnosis?: string;
  evolutionComplications?: string;
  differentialDiagnosis?: string;
  conclusion?: string;
}

export interface ApiResponse<T> {
  'hydra:member'?: T[];
  member?: T[];
  'hydra:totalItems'?: number;
  totalItems?: number;
}

export default {
  // Récupérer toutes les pathologies (params optionnels)
  getAll(config?: AxiosRequestConfig) {
    return apiClient.get<ApiResponse<Pathology>>('/pathologies', config);
  },

  get(id: number) {
    return apiClient.get<Pathology>(`/pathologies/${id}`);
  },

  create(data: Partial<Pathology>) {
    return apiClient.post<Pathology>('/pathologies', data);
  },

  update(id: number, data: Partial<Pathology>) {
    return apiClient.patch<Pathology>(`/pathologies/${id}`, data, {
      headers: { 'Content-Type': 'application/merge-patch+json' }
    });
  },

  delete(id: number) {
    return apiClient.delete(`/pathologies/${id}`);
  }
};
