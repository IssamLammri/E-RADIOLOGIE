import apiClient from './axios';

export interface ClinicalCase {
  id: number;

  patient: string;   // IRI: "/api/patients/1"
  exam: string;      // IRI: "/api/exams/1"
  pathology: string; // IRI: "/api/pathologies/1"

  symptoms?: string;
  images?: string;        // string (URL/texte). Pour upload on fera plus tard.
  imageComment?: string;
  conclusion?: string;
}

export default {
  getAll() {
    return apiClient.get('/clinical_cases');
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
  uploadImage(id: number, file: File) {
    const formData = new FormData();
    formData.append('file', file);
    return apiClient.post(`/clinical_cases/${id}/image`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
  }
};
