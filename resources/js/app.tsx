import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
// Import your pages
import HOSPRoutes from './Routes';
import Login from './pages/Login';
import BasicDetails from './pages/BasicDetails';

function App() {
    return (
        <Router>
            <Routes>
            <Route path="/login" element={<Login />} />
            <Route path="/basic-details" element={<BasicDetails />} />
            <Route path="/hosp/*" element={<HOSPRoutes />} />
            </Routes>
        </Router>
    );
}

const root = document.getElementById('app');
if (root) {
    ReactDOM.createRoot(root).render(<App />);
}
