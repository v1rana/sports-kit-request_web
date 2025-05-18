import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
// Import your pages
import HOSPRoutes from './Routes';
import Login from './pages/Login';
import BasicDetails from './pages/BasicDetails';
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
function App() {
    return (
        <>
        <Router>
            <Routes>
            <Route path="/" element={<Login />} />
            {/* <Route path="/login" element={<Login />} /> */}
            <Route path="/basic-details" element={<BasicDetails />} />
            <Route path="/hosp/*" element={<HOSPRoutes />} />
            </Routes>
        </Router>
        <ToastContainer />
        </>
        
    );
}

const root = document.getElementById('app');
if (root) {
    ReactDOM.createRoot(root).render(<App />);
}
