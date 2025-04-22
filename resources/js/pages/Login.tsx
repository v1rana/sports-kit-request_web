import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { getMemberbasicdetailsfromFIDUID, getOTPRequestforMEMID, login, verifyOTPRequestforMEMID } from "../services/hosp-service";

function Login() {
  
    const [pppId, setPppId] = useState("1KQP3440");
    const [otp_message, setOTPMsg] = useState("");
    const [txn, setTxn] = useState("");
    const [selectedMember, setSelectedMember] = useState("");
    const [otp, setOtp] = useState("");
    const [isOtpVisible, setIsOtpVisible] = useState(false);
    const [isMembersVisible, setIsMembersVisible] = useState(false);
    const [members, setMembers] = useState<{ value: string; text: string }[]>([]);
    const basic_data = {
        DeptCode: "NIC",
        ServiceCode: "TestCred",
        DeptKey: "o2etc739ut",
        UIDFID: pppId,
        MemberID:selectedMember,
        Txn:txn,
        OTP:otp,
    };
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

    const displayMembers = async (event: React.FormEvent) => {
        event.preventDefault();
        const error = validatePppId(pppId);
        if (error) {
            setErrors((prev) => ({ ...prev, pppId: error }));
            return;
        }

        setErrors((prev) => ({ ...prev, pppId: "" })); // Clear error
        try {
            basic_data.UIDFID = pppId;
            const response = await getMemberbasicdetailsfromFIDUID(basic_data);

            if (response.status === "Successfull") {
                setMembers(response.result.dropdown);
                setIsMembersVisible(true);
            } else {
                setErrors((prev) => ({ ...prev, pppId: response.message }));
                // alert(response.message || "Failed to fetch members.");
                setIsMembersVisible(false);
                setMembers([]);
            }
        } catch (error) {
            console.error("Error fetching members:", error);
            alert("Login failed. Please try again.");
        }
    };

    const getVerificationCode = async (event: React.FormEvent) => {
        event.preventDefault();
        if (!selectedMember) {
            setErrors((prev) => ({
                ...prev,
                selectedMember: "Please select a member",
            }));
            return;
        }
        setErrors((prev) => ({ ...prev, selectedMember: "" })); // Clear error

        try {
            basic_data.MemberID = selectedMember;
            const response = await getOTPRequestforMEMID(basic_data);

            if (response.status === "Successfull") {
                setOTPMsg(response.result.message)
                setTxn(response.result.txn)
                setIsOtpVisible(true);
            } else {
                setErrors((prev) => ({ ...prev, selectedMember: response.message }));
                // alert(response.message || "Failed to fetch members.");
                setIsOtpVisible(false);
                setMembers([]);
            }
        } catch (error) {
            console.error("Error Sending Code:", error);
            alert("Login failed. Please try again.");
        }
        
    };

    const handleLogin = async (event: React.FormEvent) => {
        event.preventDefault();
        const otpError = validateOtp(otp);
        if (otpError) {
            setErrors((prev) => ({ ...prev, otp: otpError }));
            return;
        }
        setErrors((prev) => ({ ...prev, otp: "" })); // Clear error
        try {
            // const login_data = { pppId, selectedMember };
            // const data = await login(login_data);
            // console.log("Login successful:", data);
            // alert("Login Successful!");
            // navigate("/hosp/dashboard");
            basic_data.Txn = txn;
            basic_data.OTP = otp;
            const response = await verifyOTPRequestforMEMID(basic_data);

            if (response.status === "Successfull") {
                console.log(response.result);
               localStorage.setItem('loginType',loginType);
               const data = await login(response.result);
               console.log('data',data);
               
               localStorage.setItem('user',JSON.stringify(data.user));
               localStorage.setItem('token',data.token);
               navigate("/basic-details");
              
            } else {
                setErrors((prev) => ({ ...prev, otp: response.message }));
               
            }
        } catch (error) {
            console.error("Login failed:", error);
            alert("Login failed. Please try again.");
        }
    };

    const [loginType, setLoginType] = useState('');

    const handleLoginTypeChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setLoginType(event.target.value);
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
                            <h3>Sports Department , Government of Haryana</h3>
                            <h5>Login Form</h5>
                            <p>
                            Let the young minds grow to the full potential
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
                        <div id="qbox-container" >
                            <form onSubmit={handleLogin} >
                                
                                <div id="steps-container">
                                    <div className=" w-100" id="step1">
                                        <div className="row justify-content-between">
                                            {/* <div className="col-xs-12 col-sm-6 col-md-5">							
                                        <h2>Sign in</h2>
                                        <h6>to continue with your application</h6>
                                        
                                    </div> */}
                                     <div className="col-12 mb-4">
                                     <p>Login to</p>
                                     <div className="form-check form-check-inline">
                                                <input
                                                    className="form-check-input"
                                                    type="radio"
                                                    name="loginType"
                                                    id="equipment"
                                                    value="equipment"
                                                    onChange={handleLoginTypeChange}
                                                    checked={loginType === 'equipment'}
                                                    
                                                />
                                                <label
                                                    className="form-check-label"
                                                    htmlFor="equipment"
                                                >
                                                     Haryana Sports Equipment
                                                </label>
                                            </div>
                                            <div className="form-check form-check-inline">
                                                <input
                                                    className="form-check-input"
                                                    type="radio"
                                                    name="loginType"
                                                    id="gradation"
                                                    value="gradation"
                                                    onChange={handleLoginTypeChange}
                                                    checked={loginType === 'gradation'}
                                                />
                                                <label
                                                    className="form-check-label"
                                                    htmlFor="gradation"
                                                >
                                                     Haryana Sports Gradation
                                                </label>
                                            </div>
                                            <div className="form-check form-check-inline">
                                                <input
                                                    className="form-check-input"
                                                    type="radio"
                                                    name="loginType"
                                                    id="hosp"
                                                    value="hosp"
                                                    onChange={handleLoginTypeChange}
                                                    checked={loginType === 'hosp'}
                                                />
                                                <label
                                                    className="form-check-label"
                                                    htmlFor="hosp"
                                                >
                                                    Haryana Outstanding Sports Pserson
                                                </label>
                                            </div>
                                        </div>  
                                            <div className="col-xs-6 col-sm-6 col-md-6">
                                                <div className="form-floating">
                                                    <input
                                                        type="text"
                                                       
                                                        className={`form-control required ${errors.pppId ? 'is-invalid' : ''}`}
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
                                                {/* {errors.pppId && (
                                                    <p className="error">
                                                        {errors.pppId}
                                                    </p>
                                                )} */}
                                                 {errors.pppId && <div className="error">{errors.pppId}</div>}
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
                                                           <option value="">-- Select Member --</option>
                                                            {members.map((member) => (
                                                                <option key={member.value} value={member.value}>
                                                                    {member.text}
                                                                </option>
                                                            ))}
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
                                                   {otp_message}
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
                                                    href="javascript:;"
                                                    className="resendOtp"
                                                    onClick={
                                                        getVerificationCode
                                                    }
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
