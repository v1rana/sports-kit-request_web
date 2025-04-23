import api, { API_BASE_URL } from "./api";

export const PPP_BASE_URL = import.meta.env.VITE_PPP_BASE_URL;

export const getHOSP = async () => {
    try {
        const response = await api.get("/hosp");
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};

export const getMemberbasicdetailsfromFIDUID = async (data) => {
    try {
        console.log('PPP_BASE_URL', PPP_BASE_URL);
        const response = await api.post(
            "/PPPapi/api/Account/GetMemberbasicdetailsfromFIDUID",
            { ...data },
            {
                baseURL: PPP_BASE_URL, // Custom baseURL for this call
            }
        );

        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};

export const getOTPRequestforMEMID = async (data) => {
    try {
        console.log('PPP_BASE_URL', PPP_BASE_URL);
        const response = await api.post(
            "/PPPapi/api/Account/OTPRequestforMEMID",
            { ...data },
            {
                baseURL: PPP_BASE_URL, // Custom baseURL for this call
            }
        );

        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};

export const verifyOTPRequestforMEMID = async (data) => {
    try {
        console.log('PPP_BASE_URL', PPP_BASE_URL);
        const response = await api.post(
            "/PPPapi/api/Account/VerifyOTPRequestforMEMID",
            { ...data },
            {
                baseURL: PPP_BASE_URL, // Custom baseURL for this call
            }
        );

        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};

export const login = async (data) => {
    try {
        console.log('API_BASE_URL',API_BASE_URL);
        
        const response = await api.post("/login",{...data});
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};

export const saveEvent = async (form_data) => {
    try {
        console.log('API_BASE_URL',API_BASE_URL);
        const token = localStorage.getItem("token");
        const response = await api.post("/hosp/event",form_data,
        );
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};
export const fetchEvent = async () => {
    try {
        console.log('API_BASE_URL',API_BASE_URL);
        const token = localStorage.getItem("token");
        const response = await api.get("/event-details");
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};