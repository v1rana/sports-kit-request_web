import axios from "axios";

// const API_BASE_URL = "http://127.0.0.1:8000/api"; // Change this for production
const API_BASE_URL = "http://164.100.137.70/api"; // Change this for production

const api = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        "Content-Type": "application/json",
    },
});

// Request Interceptor: Attach Authorization Token & Dynamic Headers
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem("token");

        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }

        // Set Content-Type for file uploads dynamically
        if (config.data instanceof FormData) {
            config.headers["Content-Type"] = "multipart/form-data";
        }

        return config;
    },
    (error) => Promise.reject(error)
);

// Response Interceptor: Handle Global Errors (Optional)
api.interceptors.response.use(
    (response) => {
        console.log('resp',response.data);
        return response;
    },
    (error) => {
        console.error("API Error:", error.response?.data || error.message);
        return Promise.reject(error);
    }
);

export default api;
