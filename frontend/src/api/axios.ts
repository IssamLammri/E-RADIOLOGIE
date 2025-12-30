import axios from 'axios';

const apiClient = axios.create({
  baseURL: import.meta.env.VITE_API_HOST || 'http://localhost:8090/api',
});

apiClient.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers = config.headers ?? {};
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default apiClient;
