import apiClient from './axios';

export type MeResponse = {
  id: number;
  email: string;
  firstName: string;
  lastName: string;
  roles: string[];
};

export default {
  me() {
    return apiClient.get<MeResponse>('/me');
  }
};
