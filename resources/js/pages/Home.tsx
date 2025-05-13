import React from 'react';
import { Link } from 'react-router-dom';
import hospList from './hospList';

function Home() {
    return (
        <div>
            <h1>Home Page</h1>
            <Link to="login">Go to Login</Link>
            <hospList />
        </div>
    );
}

export default Home;
