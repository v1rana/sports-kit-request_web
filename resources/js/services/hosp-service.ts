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
export const updateUserData = async (form_data) => {
    try {
        console.log('API_BASE_URL',API_BASE_URL);
        const response = await api.post("/update-details",form_data,
        );
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};
export const saveEvent = async (form_data) => {
    try {
        const response = await api.post("/hosp/event",form_data,
        );
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};
export const updateEvent = async (form_data,id) => {
    try {
        const response = await api.post(`/event/${id}`,form_data,
        );
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};
export const fetchEvent = async () => {
    try {
        const response = await api.get("/event-details");
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};
export const fetchEducation = async () => {
    try {
        const response = await api.get("/education-details");
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};

export const fetchSportsDiscipline = async () => {
    try {
        const response = await api.get("/sports-discipline-details");
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
export const fetchDeclarationDetails = async () => {
    try {
        const response = await api.get(`/declaration-details`);
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