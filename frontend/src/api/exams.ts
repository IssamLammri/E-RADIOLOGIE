import apiClient from './axios';

export interface Exam {
  id: number;
  name: string;
  description?: string;
  modality?: string; // Ce sera l'IRI (ex: "/api/modalities/1")
}

export default {
  getAll() {
    return apiClient.get('/exams');
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
