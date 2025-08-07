import axios from "axios";

export const API_BASE_URL = import.meta.env.VITE_API_BASE_URL+'/api';

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
        return response;
    },
    (error) => {
        // console.error("API Error:", error.response?.data || error.message);
        if (error.response?.status === 401) {
            // Handle Unauthenticated Error
            console.warn("User is unauthenticated. Redirecting to login...");
            // For example, redirect or notify the user
            window.location.href = "/"; // or use router.push() if you're using React Router/Next.js
        } else {
            console.error("Error fetching event:", error);
        }
        // throw error; // rethrow for upstream error handling
        return Promise.reject(error);
    }
);

export default api;
