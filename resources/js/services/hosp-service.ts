import api from "./api";

export const getHOSP = async () => {
    try {
        const response = await api.get("/hosp");
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};

export const login = async (data) => {
    try {
        const response = await api.post("/login",{...data});
        return response.data;
    } catch (error) {
        console.error("Error fetching hosp:", error);
        throw error;
    }
};