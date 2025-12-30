import apiClient from './axios';
import type { AxiosRequestConfig } from 'axios';

export interface Exam {
  id: number;
  name: string;
  description?: string;
  modality?: string; // IRI (ex: "/api/modalities/1")
}

export interface ApiResponse<T> {
  'hydra:member'?: T[];
  member?: T[];
  'hydra:totalItems'?: number;
  totalItems?: number;
}

export default {
  // Récupérer tous les examens (params optionnels)
  getAll(config?: AxiosRequestConfig) {
    return apiClient.get<ApiResponse<Exam>>('/exams', config);
  },

  get(id: number) {
    return apiClient.get<Exam>(`/exams/${id}`);
  },

  create(data: Partial<Exam>) {
    return apiClient.post<Exam>('/exams', data);
  },

  update(id: number, data: Partial<Exam>) {
    return apiClient.patch<Exam>(`/exams/${id}`, data, {
      headers: { 'Content-Type': 'application/merge-patch+json' }
    });
  },

  delete(id: number) {
    return apiClient.delete(`/exams/${id}`);
  }
};
