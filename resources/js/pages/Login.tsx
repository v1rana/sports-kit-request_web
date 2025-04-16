import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { login } from "../services/hosp-service";

function Login() {
    const [pppId, setPppId] = useState("");
    const [selectedMember, setSelectedMember] = useState("");
    const [otp, setOtp] = useState("");
    const [isOtpVisible, setIsOtpVisible] = useState(false);
    const [isMembersVisible, setIsMembersVisible] = useState(false);
    const [errors, setErrors] = useState({
        pppId: "",
        selectedMember: "",
        otp: "",
    });
    const navigate = useNavigate();

    const validatePppId = (value: string) => {
        if (!value) return "PPP ID is required";
        if (!/^[a-zA-Z0-9]{6,}$/.test(value))
            return "PPP ID must be at least 6 alphanumeric";
        return "";
    };
    const validateOtp = (value: string) => {
        if (!value) return "OTP is required";
        if (!/^\d{6}$/.test(value)) return "OTP must be 6 digits";
        return "";
    };

    const displayMembers = (event: React.FormEvent) => {
        event.preventDefault();
        const error = validatePppId(pppId);
        if (error) {
            setErrors((prev) => ({ ...prev, pppId: error }));
            return;
        }
        setErrors((prev) => ({ ...prev, pppId: "" })); // Clear error
        setIsMembersVisible(true);
    };

    const getVerificationCode = (event: React.FormEvent) => {
        event.preventDefault();
        if (!selectedMember) {
            setErrors((prev) => ({
                ...prev,
                selectedMember: "Please select a member",
            }));
            return;
        }
        setErrors((prev) => ({ ...prev, selectedMember: "" })); // Clear error
        setIsOtpVisible(true);
    };

    const handleLogin = async (event: React.FormEvent) => {
        event.preventDefault();

        const otpError = validateOtp(otp);
        if (otpError) {
            setErrors((prev) => ({ ...prev, otp: otpError }));
            return;
        }

        try {
            const login_data = { pppId, selectedMember };
            const data = await login(login_data);
            console.log("Login successful:", data);
            alert("Login Successful!");
            navigate("/hosp/dashboard");
        } catch (error) {
            console.error("Login failed:", error);
            alert("Login failed. Please try again.");
        }
    };

    return (
        <div>
            <div className="container d-flex align-items-center min-vh-100">
                <div className="row g-0 justify-content-center">
                    <div className="col-lg-4 offset-lg-1 mx-0 px-0">
                        <div id="title-container">
                            <img
                                className="covid-image"
                                src="/assets/job_app/images/logo-sports.png"
                            />
                            <h3>Haryana Outstanding Sports Persons</h3>
                            <h5>Login Form</h5>
                            <p>
                                Appointed to the Haryana Outstanding Sports
                                Service (Group A, B, and C)
                            </p>
                        </div>
                    </div>
                    <div className="col-lg-7 mx-0 px-0">
                        <div className="progress">
                            <div
                                aria-valuemax="100"
                                aria-valuemin="0"
                                aria-valuenow="50"
                                className="progress-bar progress-bar-striped progress-bar-animated bg-danger"
                                role="progressbar"
                                style={{ width: "0%" }}
                            ></div>
                        </div>
                        <div id="qbox-container">
                            <form onSubmit={handleLogin}>
                                <div id="steps-container">
                                    <div className=" w-100" id="step1">
                                        <div className="row justify-content-between">
                                            {/* <div className="col-xs-12 col-sm-6 col-md-5">							
                                        <h2>Sign in</h2>
                                        <h6>to continue with your application</h6>
                                        
                                    </div> */}
                                            <div className="col-xs-6 col-sm-6 col-md-6">
                                                <div className="form-floating">
                                                    <input
                                                        type="text"
                                                        className="form-control required"
                                                       
                                                        id="pppid"
                                                        name="pppid"
                                                        maxLength={9}
                                                        minLength={6}
                                                        disabled={
                                                            isMembersVisible
                                                        }
                                                        value={pppId}
                                                        onChange={(e) => {
                                                            setPppId(
                                                                e.target.value
                                                            );
                                                            setErrors(
                                                                (prev) => ({
                                                                    ...prev,
                                                                    pppId: validatePppId(
                                                                        e.target
                                                                            .value
                                                                    ),
                                                                })
                                                            );
                                                        }}
                                                    />
                                                    
                                                    <label htmlFor="pppid">
                                                        PPP ID
                                                    </label>
                                                </div>
                                                {errors.pppId && (
                                                    <p className="error">
                                                        {errors.pppId}
                                                    </p>
                                                )}
                                            </div>
                                            <div className="col-xs-6 col-sm-6 col-md-6">
                                                <button
                                                    id="next-btn"
                                                    className="next-btn"
                                                    type="button"
                                                    hidden={isMembersVisible}
                                                    onClick={displayMembers}
                                                >
                                                    Display Members
                                                </button>
                                            </div>
                                        </div>
                                        {/* Step 2: Select Member */}
                                        {isMembersVisible && (
                                            <div className="row mt-4">
                                                <div className="col-8">
                                                    <div className="form-floating">
                                                        <select
                                                            className="form-select"
                                                            aria-label="Default select example"
                                                            disabled={
                                                                isOtpVisible
                                                            }
                                                            value={
                                                                selectedMember
                                                            }
                                                            onChange={(e) => {
                                                                setSelectedMember(
                                                                    e.target
                                                                        .value
                                                                );
                                                                setErrors(
                                                                    (prev) => ({
                                                                        ...prev,
                                                                        selectedMember:
                                                                            "",
                                                                    })
                                                                ); // Clear error
                                                            }}
                                                        >
                                                            <option value="">
                                                                Select
                                                            </option>
                                                            <option value="1">
                                                                Member 1
                                                            </option>
                                                            <option value="2">
                                                                Member 2
                                                            </option>
                                                            <option value="3">
                                                                Member 3
                                                            </option>
                                                        </select>
                                                        <label htmlFor="member">
                                                            Select Member
                                                        </label>
                                                    </div>
                                                    {errors.selectedMember && (
                                                        <p
                                                            className="error"
                                                        >
                                                            {
                                                                errors.selectedMember
                                                            }
                                                        </p>
                                                    )}
                                                    {!isOtpVisible && (
                                                        <p className="mt-1 text-secondary">
                                                            We will send you a
                                                            verification code
                                                        </p>
                                                    )}
                                                </div>
                                                <div className="col-4">
                                                    <button
                                                        id="next-btn"
                                                        className="next-btn"
                                                        type="button"
                                                        hidden={isOtpVisible}
                                                        onClick={
                                                            getVerificationCode
                                                        }
                                                    >
                                                        Get Verification Code
                                                    </button>
                                                </div>
                                            </div>
                                        )}
                                         {/* Step 3: Enter OTP */}
                                {isOtpVisible && (
                                   
                                        <div className="row justify-content-between">
                                           
                                            <div className="col-9 mt-4">
                                            <small>
                                                    OTP Sent to your registered
                                                    mobile No. ******1219. It is
                                                    Valid for 10 min
                                                </small>
                                                <input
                                                    type="text"
                                                    className="form-control mb-3 required"
                                                    maxLength={6}
                                                    id="otp"
                                                    name="otp"
                                                    placeholder="Enter OTP"
                                                    value={otp}
                                                    onChange={(e) =>
                                                        setOtp(e.target.value)
                                                    }
                                                    required
                                                />
                                                {errors.otp && (
                                                    <p className="error">
                                                        {errors.otp}
                                                    </p>
                                                )}
                                                Didn't receive OTP?{" "}
                                                <a
                                                    href="#"
                                                    className="resendOtp"
                                                >
                                                    Resend code
                                                </a>
                                            </div>
                                            <div className="col-3 mt-5">
                                                <button
                                                   id="next-btn"
                                                    className="next-btn"
                                                    type="submit"
                                                   
                                                >
                                                    Verify OTP
                                                </button>
                                                {/* <button
                                                    id="verifybtn"
                                                    type="button"
                                                    className="btn btn-info"
                                                >
                                                    Resend OTP
                                                </button> */}
                                            </div>
                                        </div>
                                    
                                )}
                                    </div>
                                </div>

                               
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
    );
}

export default Login;
