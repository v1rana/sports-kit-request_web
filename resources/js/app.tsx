import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
// Import your pages
import JobRoutes from './Routes';

function App() {
    return (
        <Router>
            <Routes>
            <Route path="/hosp/*" element={<JobRoutes />} />
            </Routes>
        </Router>
    );
}

const root = document.getElementById('app');
if (root) {
    ReactDOM.createRoot(root).render(<App />);
}
