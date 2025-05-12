import React from "react";
import { Routes, Route } from "react-router-dom";

import Login from "./pages/Login";
import HospForm from "./pages/HospForm";
import NotFound from "./pages/NotFound";
import Home from "./pages/Home";
import ApplicationPreview from "./pages/ApplicationPreview";

function HOSPRoutes() {
    return (
        <Routes>
            <Route path="/" element={<Home />} />
            <Route path="/login" element={<Login />} />
            <Route path="/hosp-form" element={<HospForm />} />
            <Route path="/download-application" element={<ApplicationPreview />} />
            <Route path="*" element={<NotFound />} />
        </Routes>
    );
}

export default HOSPRoutes;
