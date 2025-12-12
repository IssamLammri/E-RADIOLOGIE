import axios from 'axios';

// On utilise import.meta.env pour récupérer la variable VITE_API_URL
// Si la variable n'existe pas, on met une chaîne vide ou une valeur par défaut
const apiClient = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8090/api',
  headers: {
    'Content-Type': 'application/ld+json',
  },
});

apiClient.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default apiClient;
