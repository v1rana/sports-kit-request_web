import React from "react";
import { Routes, Route } from "react-router-dom";

import Login from "./pages/Login";
import HospForm from "./pages/HospForm";
import NotFound from "./pages/NotFound";
import Dashboard from "./pages/Dashboard";
import ApplicationPreview from "./pages/ApplicationPreview";

function HOSPRoutes() {
    return (
        <Routes>
            <Route path="/dashboard" element={<Dashboard />} />
            <Route path="/login" element={<Login />} />
            <Route path="/hosp-form" element={<HospForm />} />
            <Route path="/preview-application/:application_id" element={<ApplicationPreview />} />
            <Route path="*" element={<NotFound />} />
        </Routes>
    );
}

export default HOSPRoutes;
