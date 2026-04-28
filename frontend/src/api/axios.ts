import axios from 'axios';

const apiClient = axios.create({
  baseURL: import.meta.env.VITE_API_HOST || '/api',
  headers: {
    Accept: 'application/ld+json',
  },
});

// Ajoute Content-Type automatiquement quand il y a un body
apiClient.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  config.headers = config.headers ?? {};

  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }

  // Pour les requêtes avec payload (POST/PUT/PATCH), API Platform attend souvent JSON-LD
  const method = (config.method || 'get').toLowerCase();
  if (['post', 'put', 'patch'].includes(method)) {
    // Ne pas écraser si déjà défini au cas par cas
    if (!config.headers['Content-Type'] && !config.headers['content-type']) {
      config.headers['Content-Type'] = 'application/ld+json';
    }
  }

  return config;
});

export default apiClient;
