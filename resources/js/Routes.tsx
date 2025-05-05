import React from "react";
import { Routes, Route } from "react-router-dom";

import Login from "./pages/Login";
import HospForm from "./pages/HospForm";
import NotFound from "./pages/NotFound";
import Home from "./pages/Home";

function HOSPRoutes() {
    return (
        <Routes>
            <Route path="/" element={<Home />} />
            <Route path="/login" element={<Login />} />
            <Route path="/hosp-form" element={<HospForm />} />
            <Route path="*" element={<NotFound />} />
        </Routes>
    );
}

export default HOSPRoutes;
