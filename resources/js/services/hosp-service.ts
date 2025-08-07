import api, { API_BASE_URL } from "./api";
import { toast } from 'react-toastify';

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
            // "/api/Account/GetMemberbasicdetailsfromFIDUID",
            "/member-details",
            { ...data },
            // {
            //     baseURL: PPP_BASE_URL, // Custom baseURL for this call
            // }
        );

        return response.data.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};

export const getOTPRequestforMEMID = async (data) => {
    try {
        const response = await api.post(
            // "/api/Account/OTPRequestforMEMID",
            '/otp-request',
            { ...data },
            // {
            //     baseURL: PPP_BASE_URL, // Custom baseURL for this call
            // }
        );

        return response.data.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};

export const verifyOTPRequestforMEMID = async (data) => {
    try {
        console.log('PPP_BASE_URL', PPP_BASE_URL);
        const response = await api.post(
            // "/api/Account/VerifyOTPRequestforMEMID",
            "/verify-otp",
            { ...data },
            // {
            //     baseURL: PPP_BASE_URL, // Custom baseURL for this call
            // }
        );

        return response.data.data;
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
export const updateUserData = async (form_data) => {
    try {
        console.log('API_BASE_URL',API_BASE_URL);
        const response = await api.post("/update-details",form_data,
        );
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        // return error.response.data;
        errorAlert(error.response.data);
        throw error;
       
    }
};
export const updateRole = async (form_data) => {
    try {
        console.log('API_BASE_URL',API_BASE_URL);
        const response = await api.post("/update-role",form_data,
        );
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};


export const fetchUserDetails = async () => {
    try {
        const response = await api.get("/user-details");
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};
export const fetchApplicationPreviewDetails = async (application_id) => {
    try {
        const response = await api.get(`/completed-application-preview/${application_id}`);
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};
export const fetchUserApplications = async () => {
    try {
        const response = await api.get("/application-list");
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};
export const fetchApplicationDetails = async () => {
    try {
        const response = await api.get("/pending-application-details");
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};

export const fetchEducation = async (application_id) => {
    try {
        const response = await api.get(`/education-details/${application_id}`);
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};

export const fetchSportsDiscipline = async (application_id) => {
    try {
        const response = await api.get(`/sports-discipline-details/${application_id}`);
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};

export const fetchGameList = async () => {
    try {
        const response = await api.get(`/games`);
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};

export const saveEducation = async (form_data) => {
    try {
        const response = await api.post("/hosp/education",form_data,
        );
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        errorAlert(error.response.data);
        throw error;
    }
};
export const saveDeclarations = async (form_data) => {
    try {
        const response = await api.post("/hosp/declarations",form_data,
        );
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        errorAlert(error.response.data);
        throw error;
    }
};
export const saveSportsDiscipline = async (form_data) => {
    try {
        const response = await api.post("/hosp/sports-discipline",form_data,
        );
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        errorAlert(error.response.data);
        throw error;
    }
};

export const fetchSchedule12Listing = async (event_type) => {
    try {
        const response = await api.get(`/schedule-list/${event_type}`);
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};
export const fetchDeclarationDetails = async (application_id) => {
    try {
        const response = await api.get(`/declaration-details/${application_id}`);
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};
export const fetchDeclarationsList = async () => {
    try {
        const response = await api.get(`/declarations-list`);
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};

const errorAlert = async (data) => {
    toast.error(data.message);
}