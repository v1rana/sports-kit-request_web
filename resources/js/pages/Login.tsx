import React, { useEffect, useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import {
    getMemberbasicdetailsfromFIDUID,
    getOTPRequestforMEMID,
    login,
    updateRole,
    verifyOTPRequestforMEMID,
} from "../services/hosp-service";
import { toast } from 'react-toastify';
function Login() {
    
    const isLocalhost = ["localhost", "127.0.0.1", "::1"].includes(window.location.hostname);
    const [pppId, setPppId] = useState(isLocalhost ? "1KQP3440" : "");
    const [userId, setUserId] = useState("");
    const [otp_message, setOTPMsg] = useState("");
    const [txn, setTxn] = useState("");
    const [selectedMember, setSelectedMember] = useState("");
    const [otp, setOtp] = useState("");
    const [isOtpVisible, setIsOtpVisible] = useState(false);
    const [otpVerified, setIsOtpVerified] = useState(false);
    const [isMembersVisible, setIsMembersVisible] = useState(false);
    const [members, setMembers] = useState<{ value: string; text: string }[]>(
        []
    );
    const [counter, setCounter] = useState(0); // Countdown in seconds
    const [loginType, setLoginType] = useState("");
    const [btn_disabled, setBtnDisabled] = useState(false);
    const basic_data = {
        // stage server
        // DeptCode: "NIC",  
        // ServiceCode: "TestCred",
        // DeptKey: "o2etc739ut",

        //  live server 
        // DeptCode: "SPT",  
        // ServiceCode: "CAW",
        // DeptKey: "0A5CDE2406",

        UIDFID: pppId,
        MemberID: selectedMember,
        Txn: txn,
        OTP: otp,
    };
    const [errors, setErrors] = useState({
        pppId: "",
        selectedMember: "",
        otp: "",
        loginType: "",
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

    const displayMembers = async (event: React.FormEvent) => {
        event.preventDefault();
        const error = validatePppId(pppId);
        // if (!loginType) {
        //     setErrors((prev) => ({
        //         ...prev,
        //         loginType: "Please select login type",
        //     }));
        //     return;
        // }
        if (error) {
            setErrors((err) => ({ ...err, pppId: error }));
            return;
        }

        setErrors((err) => ({ ...err, pppId: "" })); // Clear error
        setErrors((err) => ({ ...err, loginType: "" })); // Clear error
        setBtnDisabled(true);
        try {
            basic_data.UIDFID = pppId;
            const response = await getMemberbasicdetailsfromFIDUID(basic_data);
            
            if (response.status === "Successfull") {
                setMembers(response.result.dropdown);
                setIsMembersVisible(true);
            } else {
                setErrors((err) => ({ ...err, pppId: response.message }));
                // alert(response.message || "Failed to fetch members.");
                setIsMembersVisible(false);
                setMembers([]);
            }
            setBtnDisabled(false);
        } catch (error) {
            setBtnDisabled(false);
            toast.success("Login failed. Please try again.")
            console.error("Error fetching members:", error);
            // alert("Login failed. Please try again.");
        }
    };
    useEffect(() => {
        let timer: NodeJS.Timeout;
    
        if (counter > 0) {
          timer = setTimeout(() => {
            setCounter(prev => prev - 1);
          }, 1000);
        }
    
        return () => clearTimeout(timer);
      }, [counter]);

    const getVerificationCode = async (event: React.FormEvent) => {
        event.preventDefault();
        if (!selectedMember) {
            setErrors((err) => ({
                ...err,
                selectedMember: "Please select a member",
            }));
            return;
        }
        setErrors((err) => ({ ...err, selectedMember: "" })); // Clear error

        try {
            setBtnDisabled(true);
            basic_data.MemberID = selectedMember;
            const response = await getOTPRequestforMEMID(basic_data);
           
            if (response.status === "Successfull") {
                setOTPMsg(response.result.message);
                setTxn(response.result.txn);
                setIsOtpVisible(true);
            } else {
                setErrors((err) => ({
                    ...err,
                    selectedMember: response.message,
                }));
                // alert(response.message || "Failed to fetch members.");
                setIsOtpVisible(false);
                setMembers([]);
            }
            setCounter(60);
            setBtnDisabled(false);
        } catch (error) {
            setBtnDisabled(false);
            toast.success("Login failed. Please try again.")
            console.error("Error Sending Code:", error);
            // alert("Login failed. Please try again.");
        }
    };

    const verifyOTP = async (event: React.FormEvent) => {
        event.preventDefault();
        const otpError = validateOtp(otp);
        if (otpError) {
            setErrors((err) => ({ ...err, otp: otpError }));
            return;
        }
        setErrors((err) => ({ ...err, otp: "" })); // Clear error
        try {
            // const login_data = { pppId, selectedMember };
            // const data = await login(login_data);
            // console.log("Login successful:", data);
            // alert("Login Successful!");
            // navigate("/hosp/dashboard");
            basic_data.Txn = txn;
            basic_data.OTP = otp;
            setBtnDisabled(true);
            const response = await verifyOTPRequestforMEMID(basic_data);
           
            if (response.status === "Successfull") {
                console.log(response.result);

                const data = await login(response.result);
                console.log("data", data);
                data.user.user_details.mobile = data.user.mobile;
                localStorage.setItem("user", JSON.stringify(data.user));
                localStorage.setItem("token", data.token);
                setUserId(data.userId);
                setIsOtpVerified(true);
                // navigate("/basic-details");
            } else {
                setErrors((err) => ({ ...err, otp: response.message }));
            }
            setBtnDisabled(false);
        } catch (error) {
            setBtnDisabled(false);
            toast.success("Login failed. Please try again.")
            console.error("Login failed:", error);
            // alert("Login failed. Please try again.");
        }
    };
    const handleLogin = async (event: React.FormEvent) => {
        event.preventDefault();
        if (!loginType) {
            setErrors((prev) => ({
                ...prev,
                loginType: "Please select login type",
            }));
            return;
        }
        localStorage.setItem("loginType", loginType);
        setBtnDisabled(true);
        const response = await updateRole({role_id:loginType});
        
        // role/loginType 1 for equipment, 2 for gradation, 3 for hosp
        toast.success("You’ve logged in successfully.");
        setBtnDisabled(false);
        if (loginType == "3") {
            // navigate("/basic-details");
            navigate("/hosp/dashboard");
        }
        if (loginType == "1") {
            navigate("/registration-form/" + encodeURIComponent(userId));
            window.location.reload();
        } else if (loginType == "2") {
            navigate("/apply.certificate.form/" + encodeURIComponent(userId));
            window.location.reload();
        }
    };

    const handleLoginTypeChange = (
        event: React.ChangeEvent<HTMLInputElement>
    ) => {
        setLoginType(event.target.value);
    };

    return (
        <div>
            <div className="background-image"></div>

            <div className="d-flex justify-content-center align-items-center min-vh-100">
                <form onSubmit={handleLogin}>
                    <div className="row login-container">
                        {/* Left Side Form  */}
                        <div className="col-md-5 left-form bg-white">
                            <div className="text-center logo-title mb-4">
                                <img
                                    src="/assets/images/logo-sports.png"
                                    alt="Logo"
                                />
                                <h1>Sports Department</h1>
                                <p className="tagline">
                                    Let the young minds grow to the full
                                    potential
                                </p>
                            </div>

                            <div className="section-title text-center">
                          
                               <a href="/" className="active-login">Applicant Login</a> 
                           
                              
                                <a href="/login"> Offical Login</a>
                            </div>
                          <div className="clearfix"></div>

                            <div className="mb-3">
                                <label htmlFor="pppId" className="form-label">
                                    PPP ID
                                </label>
                                <input
                                    type="text"
                                    className={`form-control required ${
                                        errors.pppId ? "is-invalid" : ""
                                    }`}
                                    id="pppid"
                                    name="pppid"
                                    maxLength={9}
                                    minLength={6}
                                    disabled={isMembersVisible}
                                    value={pppId}
                                    onChange={(e) => {
                                        setPppId(e.target.value);
                                        setErrors((err) => ({
                                            ...err,
                                            pppId: validatePppId(
                                                e.target.value
                                            ),
                                        }));
                                    }}
                                />
                                {errors.pppId && (
                                    <div className="error">{errors.pppId}</div>
                                )}

                                <button
                                    className="btn btn-custom mt-2"
                                    hidden={isMembersVisible}
                                    onClick={displayMembers}
                                    disabled={btn_disabled}
                                >
                                    Display Members
                                </button>
                            </div>
                            {isMembersVisible && (
                                <div className="mb-3">
                                    <label
                                        htmlFor="memberSelect"
                                        className="form-label"
                                    >
                                        Choose Member
                                    </label>
                                    <select
                                        className="form-control"
                                        id="memberSelect"
                                        name="memberSelect"
                                        disabled={isOtpVisible}
                                        value={selectedMember}
                                        onChange={(e) => {
                                            setSelectedMember(e.target.value);
                                            setErrors((err) => ({
                                                ...err,
                                                selectedMember: "",
                                            })); // Clear error
                                        }}
                                    >
                                        <option value={""} disabled>
                                            Select a member
                                        </option>
                                        {members.map((member) => (
                                            <option
                                                key={member.value}
                                                value={member.value}
                                            >
                                                {member.text}
                                            </option>
                                        ))}
                                        {/* <!-- Add more members as needed --> */}
                                    </select>
                                    {errors.selectedMember && (
                                        <p className="error">
                                            {errors.selectedMember}
                                        </p>
                                    )}
                                    {isMembersVisible && (
                                        <button
                                            className="btn btn-custom mt-2"
                                            hidden={isOtpVisible}
                                            onClick={getVerificationCode}
                                            disabled={btn_disabled}
                                        >
                                            Send OTP
                                        </button>
                                    )}
                                </div>
                            )}
                            {isOtpVisible && (
                                <div className="mb-3">
                                    <input
                                        type="text"
                                        className="form-control required"
                                        maxLength={6}
                                        id="otp"
                                        name="otp"
                                        placeholder="Enter OTP"
                                        value={otp}
                                        disabled={otpVerified}
                                        onChange={(e) => setOtp(e.target.value)}
                                        required
                                    />
                                    {errors.otp && (
                                        <p className="error">{errors.otp}</p>
                                    )}
                                    {!otpVerified && (
                                         <div>
                                         {!counter ? (
                                           <a
                                             href="javascript:;"
                                             className="resendOtp"
                                             onClick={getVerificationCode}
                                           >
                                             Resend code
                                           </a>
                                         ) : (
                                           <span className="resendOtpDisabled">
                                             Resend code in {counter}s
                                           </span>
                                         )}
                                       </div>
                                    )}
                                     {!otpVerified && (
                                            <button
                                                onClick={verifyOTP}
                                                className="btn btn-custom mt-1"
                                                type="button"
                                                disabled={btn_disabled}
                                            >
                                                Verify OTP
                                            </button>
                                        )}
                                </div>
                            )}
                             {otpVerified && (
                                <div>
                            <div className="mb-3 form-check">
                                <input
                                    className="form-check-input"
                                    type="radio"
                                    name="loginType"
                                    id="equipment"
                                    value="1"
                                    onChange={handleLoginTypeChange}
                                    checked={loginType === "1"}
                                    required
                                />
                                <label
                                    className="form-check-label"
                                    htmlFor="equipment"
                                >
                                   Haryana Provision of Sports Equipment 
                                </label>
                            </div>
                            <div className="mb-3 form-check">
                                <input
                                    className="form-check-input"
                                    type="radio"
                                    name="loginType"
                                    id="gradation"
                                    value="2"
                                    onChange={handleLoginTypeChange}
                                    checked={loginType === "2"}
                                />
                                <label
                                    className="form-check-label"
                                    htmlFor="gradation"
                                >
                                    Haryana Sports Gradation Certificate
                                </label>
                            </div>
                            <div className="mb-3 form-check">
                                <input
                                    className="form-check-input"
                                    type="radio"
                                    name="loginType"
                                    id="hosp"
                                    value="3"
                                    onChange={handleLoginTypeChange}
                                    checked={loginType === "3"}
                                />
                                <label
                                    className="form-check-label"
                                    htmlFor="person"
                                >
                                    Haryana Outstanding Sportspersons
                                </label>
                            </div>
                            {errors.loginType && (
                                <div className="error">{errors.loginType}</div>
                            )}
                             <button
                                        className="btn btn-custom mt-1"
                                        type="submit"
                                        disabled={btn_disabled}
                                    >
                                        Submit
                                    </button>
                            </div>
                            
                        )}
                        
                        </div>

                        {/* Right Side Content */}
                        <div className="col-md-7 right-side">
                            <div className="testimonial-text">
                                "Empowering athletes through seamless digital
                                access and support."
                            </div>
                            <div className="testimonial-author">
                                 Sports Department, Haryana
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div className="background-image"></div>
            <div className="background-overlay"></div>
        </div>
    );
}

export default Login;
