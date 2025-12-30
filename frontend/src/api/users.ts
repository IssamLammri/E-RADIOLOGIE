import apiClient from './axios';
import type { AxiosRequestConfig } from 'axios';

export interface User {
  id: number;
  email: string;
  roles: string[];
  firstName?: string;
  lastName?: string;

  // utilisé en POST/PATCH uniquement
  plainPassword?: string;
}

export interface ApiResponse<T> {
  'hydra:member'?: T[];
  member?: T[];
  'hydra:totalItems'?: number;
  totalItems?: number;
}

export default {
  // Récupérer tous les users (avec params optionnels)
  getAll(config?: AxiosRequestConfig) {
    return apiClient.get<ApiResponse<User>>('/users', config);
  },

  get(id: number) {
    return apiClient.get<User>(`/users/${id}`);
  },

  create(data: Partial<User>) {
    return apiClient.post<User>('/users', data);
  },

  update(id: number, data: Partial<User>) {
    return apiClient.patch<User>(`/users/${id}`, data, {
      headers: { 'Content-Type': 'application/merge-patch+json' }
    });
  },

  delete(id: number) {
    return apiClient.delete(`/users/${id}`);
  }
};
