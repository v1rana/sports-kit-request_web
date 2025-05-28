import React, { useEffect } from "react";
import { Link, useNavigate } from "react-router-dom";
import { fetchUserDetails } from "../services/hosp-service";

function Dashboard() {
    const navigate = useNavigate();
    let userData = JSON.parse(localStorage.getItem("user")!);
    let userDetails = userData?.user_details || {};
    let loginType = JSON.parse(localStorage.getItem("loginType")!);
    const logout = () => {
        localStorage.clear();
        navigate("/");
    };
    useEffect(() => {
                const fetchUserData = async () => {
                    try {
                        const data = await fetchUserDetails();
                        localStorage.setItem("user", JSON.stringify(data.user));
                        userData = JSON.parse(localStorage.getItem("user")!);
                        userDetails = userData?.user_details || {};
                    } catch (error) {
                        console.error("Error loading form data", error);
                    }
                };
                fetchUserData();
            }, []); // <-- empty array ensures this only runs once
       useEffect(() => {
            // const userData = JSON.parse(localStorage.getItem("user") || "null");
            if (!userData || !loginType || loginType != '3') {
                localStorage.clear();
                navigate("/");
            }
        }, [navigate]);
    return (
        <div>
            {/* <h1>Home Page</h1>
            <Link to="login">Go to Login</Link> */}
            <header className="hero-section">
                <div className="hero-content">
                    <div className="d-flex">
                        <img
                            src="/assets/images/logo-sports.png"
                            alt="Sports Department Logo"
                            className="header-logo mx-3"
                        />
                        <div className="hero-text text-start">
                            <h2>
                                Haryana Outstanding Sportspersons Application
                                <br />
                                <small>Sports Department, Haryana</small>
                            </h2>
                            <p>
                                Let the young minds grow to the full potential
                            </p>
                        </div>
                    </div>
                    <div className="text-end d-flex align-items-center">
                        {/* <a
                            href=""
                            className="fs-5 text-decoration-none text-light me-3"
                        >
                            Dashboard
                        </a> */}
                        <button
                            className="btn btn-danger me-1"
                            onClick={logout}
                        >
                            <i className="fa-solid fa-power-off"></i> Logout
                        </button>
                    </div>
                </div>

                <div className="hero-wave">
                    <svg viewBox="0 0 500 150" preserveAspectRatio="none">
                        <path
                            d="M0.00,49.98 C157.87,179.29 349.61,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z"
                            style={{ stroke: "none", fill: "#f0f0f0" }}
                        ></path>
                    </svg>
                </div>
            </header>

            <div className="container my-5 pt-5">
                <div className="row justify-content-center">
                    <div className="col-12 col-sm-12 col-md-11 text-center">
                        <h1>
                            Welcome, <strong>{userDetails.full_name_en}</strong>
                        </h1>
                        <p className="fs-5">Let’s make it happen</p>
                        {/* <a href="" className="btn btn-primary my-2">
                            📝 Fill your HOSA Form
                        </a> */}

                        <div className="card shadow p-0 application-status mt-5">
                            <div className="card-body p-0">
                                <table className="table table-striped table-hovered table-bordered">
                                    <thead className="bg-dark text-white">
                                        <tr>
                                            <th>Application ID</th>
                                            <th>Name</th>
                                            <th>Sports Discipline</th>
                                            <th>Name of Tournament</th>
                                            <th>Medal Won</th>

                                            <th>DOB</th>
                                            <th>Haryana Domicile</th>
                                            <th>Caste Category</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <td>
                                            <Link
                                                className=""
                                                to="/hosp/preview-application"
                                            >
                                                {userDetails.application_id}
                                            </Link>
                                            {/* <a href="#">HOSA00001</a> */}
                                        </td>
                                        <td>{userDetails.full_name_en}</td>

                                        <td>
                                            {
                                                userData?.sports_discipline_hosp
                                                    .game.name
                                            }
                                        </td>
                                        <td>
                                            {
                                                userData?.sports_discipline_hosp
                                                    .tournament.tournament
                                            }
                                        </td>
                                        <td>
                                            {
                                                userData?.sports_discipline_hosp
                                                    .medal_won
                                            }
                                        </td>
                                        <td>{userDetails.date_of_birth}</td>
                                        <td>
                                            {userDetails.domicile == 1
                                                ? "Yes"
                                                : "No"}
                                        </td>
                                        <td>{userDetails.caste_category}</td>
                                        <td>
                                            <span
                                                className={`badge rounded-pill ${
                                                    userData?.status === 0
                                                        ? "bg-primary"
                                                        : userData?.status === 1
                                                        ? "bg-success"
                                                        : "bg-danger"
                                                }`}
                                            >
                                                {userData?.status === 0
                                                    ? "In-progress"
                                                    : userData?.status === 1
                                                    ? "Approved"
                                                    : "Rejected"}
                                            </span>
                                        </td>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer>
                <div className="container">
                    <div className="row justify-content-center">
                        <div className="col-12">
                            <p>
                                All rights reserved. Powered by{" "}
                                <strong>
                                    Citizen Resources Information Department,
                                    Haryana
                                </strong>
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    );
}

export default Dashboard;
