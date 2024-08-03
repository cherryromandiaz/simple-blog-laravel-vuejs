import axios from 'axios';

// Create an Axios instance with default settings, Programmer: Cherry Romandiaz
const api = axios.create({
  baseURL: 'http://localhost:8000/api', // Adjust the base URL to your backend, Programmer: Cherry Romandiaz
  headers: {
    'Content-Type': 'application/json',
  },
});

// Add a request interceptor to include the auth token
api.interceptors.request.use(
  config => {
    const token = localStorage.getItem('token'); // Assume token is stored in localStorage, Programmer: Cherry Romandiaz
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  error => Promise.reject(error)
);

export default api;
