import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { login } from "../services/hosp-service";

function Login() {
    const [pppId, setPppId] = useState("");
    const [selectedMember, setSelectedMember] = useState("");
    const [otp, setOtp] = useState("");
    const [isOtpVisible, setIsOtpVisible] = useState(false);
    const [isMembersVisible, setIsMembersVisible] = useState(false);
    const [errors, setErrors] = useState({ pppId: "", selectedMember: "", otp: "" });
    const navigate = useNavigate();

    const validatePppId = (value: string) => {
        if (!value) return "PPP ID is required";
        if (!/^\d{6,9}$/.test(value)) return "PPP ID must be atleast 6 digits";
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
            setErrors((prev) => ({ ...prev, selectedMember: "Please select a member" }));
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
            <h1>Login Page</h1>
            <section className="container login-area">
                <div className="row justify-content-center">
                    <div className="col-10 bg-white border">
                        <div className="content">
                            <form onSubmit={handleLogin}>
                                {/* Step 1: Enter PPP ID */}
                                <div className="step w-100" id="step1">
                                    <div className="row justify-content-between">
                                    <div className="col-xs-12 col-sm-6 col-md-5">							
                                        <h2>Sign in</h2>
                                        <h6>to continue with your application</h6>
                                        
                                    </div>
                                        <div className="col-xs-8 col-sm-6 col-md-4">
                                            <div className="form-floating">
                                                <input
                                                    type="text"
                                                    className="form-control required"
                                                    id="pppid"
                                                    name="pppid"
                                                    maxLength={9}
                                                    minLength={6}
                                                    disabled={isMembersVisible}
                                                    value={pppId}
                                                    onChange={(e) => {
                                                        setPppId(e.target.value);
                                                        setErrors((prev) => ({ ...prev, pppId: validatePppId(e.target.value) }));
                                                    }}
                                                />
                                                <label htmlFor="pppid">PPP ID</label>
                                            </div>
                                            {errors.pppId && <p style={{ color: "red" }}>{errors.pppId}</p>}
                                        </div>
                                        <div className="col-xs-8 col-sm-6 col-md-3">
                                            <button
                                                type="button"
                                                className="btn btn-success"
                                                hidden={isMembersVisible}
                                                onClick={displayMembers}
                                            >
                                                Display Members
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {/* Step 2: Select Member */}
                                {isMembersVisible && (
                                    <div className="step w-100">
                                        <div className="row justify-content-between">
                                        <div className="col-xs-12 col-sm-6 col-md-5">							
                                       
                                        
                                       </div>
                                            <div className="col-xs-8 col-sm-6 col-md-4">
                                                <div className="form-floating">
                                                    <select
                                                        className="form-select"
                                                        aria-label="Default select example"
                                                        disabled={isOtpVisible}
                                                        value={selectedMember}
                                                        onChange={(e) => {
                                                            setSelectedMember(e.target.value);
                                                            setErrors((prev) => ({ ...prev, selectedMember: "" })); // Clear error
                                                        }}
                                                    >
                                                        <option value="">Select</option>
                                                        <option value="1">Member 1</option>
                                                        <option value="2">Member 2</option>
                                                        <option value="3">Member 3</option>
                                                    </select>
                                                    <label htmlFor="member">Select Member</label>
                                                </div>
                                                {errors.selectedMember && <p style={{ color: "red" }}>{errors.selectedMember}</p>}
                                                {!isOtpVisible && <p className="mt-1 text-secondary">We will send you a verification code</p>}
                                            </div>
                                            <div className="col-xs-8 col-sm-6 col-md-3">
                                                <button
                                                    type="button"
                                                    className="btn btn-success"
                                                    hidden={isOtpVisible}
                                                    onClick={getVerificationCode}
                                                >
                                                    Get Verification Code
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                )}

                                {/* Step 3: Enter OTP */}
                                {isOtpVisible && (
                                    <div className="step w-100">
                                        <div className="row justify-content-between">
                                        <div className="col-xs-12 col-sm-6 col-md-5">							
                                       
                                         <p>OTP Sent to your registered mobile No. ******8969. It is Valid for 10 min</p>
                                    </div>
                                            <div className="col-xs-8 col-sm-6 col-md-4">
                                              
                                                <input
                                                    type="text"
                                                    className="form-control mb-3 required"
                                                    maxLength={6}
                                                    id="otp"
                                                    name="otp"
                                                    placeholder="Enter OTP"
                                                    value={otp}
                                                    onChange={(e) => setOtp(e.target.value)}
                                                    required
                                                />
                                                {errors.otp && <p style={{ color: "red" }}>{errors.otp}</p>}
                                                Didn't receive OTP? <a href="#" className="resendOtp">Resend code</a>
                                            </div>
                                            <div className="col-xs-8 col-sm-6 col-md-3">
                                                <button id="submitotpbtn" type="submit" className="btn btn-success m-1">
                                                    Verify OTP
                                                </button>
                                                <button id="verifybtn" type="button" className="btn btn-info">
                                                    Resend OTP
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                )}
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    );
}

export default Login;
