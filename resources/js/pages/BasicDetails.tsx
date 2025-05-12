import React, { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { useForm } from "react-hook-form";
import { useNavigate } from "react-router-dom";
import { fetchUserDetails, updateUserData } from "../services/hosp-service";

const BasicDetails = () => {
    const navigate = useNavigate();
    let  userData = JSON.parse(localStorage.getItem("user")!);
    let  userDetails = userData?.user_details || {};
   
    const [userDetailsa, setUserDetails] = useState({
        email_id: userDetails.email_id,
        mobile: userData.mobile,
        aadhaar: userDetails.aadhaar,
        photo: userDetails.photo,
        domicile: userDetails.domicile,
        domicile_doc: userDetails.domicile_doc,
    });
    const [errors, setErrors] = useState({
        email_id: "",
        mobile: "",
        aadhaar: "",
        photo: "",
        domicile: "",
        domicile_doc: "",
    });
    const validateEmail = (value: string) => {
        if (!value) return "Email is required";
        if (!/^\S+@\S+\.\S+$/.test(value)) return "Invalid email format";
        return "";
    };

    const validateMobile = (value: string) => {
        if (!value) return "Mobile number is required";
        if (!/^\d{10}$/.test(value))
            return "Mobile number must be exactly 10 digits";
        return "";
    };

    const validateAadhaar = (value: string) => {
        if (!value) return "Aadhaar is required";
        if (!/^\d{12}$/.test(value)) return "Aadhaar must be exactly 12 digits";
        return "";
    };
    const validateDomicle = (value: string) => {
        if (!value) return "Please select the haryana resident/domicile";
        return "";
    };

    const validateDomicleDoc = (value: string) => {
        if (!value && userDetailsa.domicile == '1') return "Please upload haryana resident/domicile";
        return "";
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
            }
            fetchUserData();
        }, []);

    useEffect(() => {
        // const userData = JSON.parse(localStorage.getItem("user") || "null");
        if (!userData) {
            localStorage.clear();
            navigate("/login");
        }
    }, [navigate]);
    // Get user data from localStorage

    const logout = () => {
        localStorage.clear();
        navigate("/login");
    };

    const save = async () => {
        const newErrors: any = {};

        const emailError = validateEmail(userDetailsa.email_id);
        if (emailError) newErrors.email_id = emailError;

        const mobileError = validateMobile(userDetailsa.mobile);
        if (mobileError) newErrors.mobile = mobileError;

        const aadhaarError = validateAadhaar(userDetailsa.aadhaar);
        if (aadhaarError) newErrors.aadhaar = aadhaarError;

        const domicleError = validateDomicle(userDetailsa.domicile);
        if (domicleError) newErrors.domicile = domicleError;

        const domicleDocError = validateDomicleDoc(userDetailsa.domicile_doc);
        if (domicleDocError) newErrors.domicile_doc = domicleDocError;

        if (!userDetailsa.photo) newErrors.photo = "Profile photo is required";
        setErrors(newErrors);

        // Block save if any error exists
        if (Object.keys(newErrors).length > 0) return;
        // Build FormData for API
        const formData = new FormData();
        formData.append("email_id", userDetailsa.email_id);
        formData.append("mobile", userDetailsa.mobile);
        formData.append("aadhaar", userDetailsa.aadhaar);
        formData.append("photo", userDetailsa.photo); // This must be a File
        formData.append("domicile", userDetailsa.domicile); 
        formData.append("domicile_doc", userDetailsa.domicile_doc); // This must be a File
        const response = await updateUserData(formData);
        if (response.status === "success") {
            localStorage.setItem("user", JSON.stringify(response.user));
            navigate("/hosp/hosp-form?step=1");
        }
    };

    // Helper function to convert gender code to text
    const getGenderText = (genderCode) => {
        switch (genderCode) {
            case "M":
                return "Male";
            case "F":
                return "Female";
            case "O":
                return "Other";
            default:
                return genderCode;
        }
    };

    // Helper function to convert marital status code to text
    const getMaritalStatusText = (status) => {
        if (!status) return "";
        return status.charAt(0).toUpperCase() + status.slice(1).toLowerCase();
    };

    // Helper function to convert boolean to Yes/No
    const getYesNoText = (value) => {
        if (value === 1 || value === true) return "Yes";
        if (value === 0 || value === false) return "No";
        return value || "";
    };

    return (
        <div>
            {userData && (
                <div>
                    <header className="hero-section d-flex align-items-center">
                        <div className="container text-center">
                            <div className="logo-title-wrapper d-flex justify-content-center align-items-center mb-4">
                                <img
                                    src="./assets/images/logo-sports.png"
                                    alt="Sports Department Logo"
                                    className="header-logo me-3"
                                />
                                <div>
                                    <h1 className="hero-title mb-1">
                                        Sports Department
                                    </h1>
                                    <h2 className="hero-subtitle2">
                                        Government of Haryana
                                    </h2>
                                </div>
                            </div>
                            <p className="hero-tagline">
                                Let the young minds grow to their full potential
                            </p>
                        </div>
                    </header>

                    <div className="container form-container">
                        <h3 className="form-heading">Basic Details Form</h3>
                        <form>
                            <div className="row g-3">
                                <div className="col-md-6">
                                    <label>Full Name (English)</label>
                                    <input
                                        type="text"
                                        className="form-control"
                                        value={userDetails.full_name_en || ""}
                                        readOnly
                                        placeholder="Enter full name in English"
                                    />
                                </div>
           

                                <div className="col-md-6">
                                    <label>Date Of Birth</label>
                                    <input
                                        type="date"
                                        className="form-control"
                                        value={userDetails.date_of_birth || ""}
                                        readOnly
                                    />
                                </div>
                                <div className="col-md-6">
                                    <label>Age</label>
                                    <input
                                        type="number"
                                        className="form-control"
                                        value={userDetails.age || ""}
                                        readOnly
                                    />
                                </div>
                                <div className="col-md-6">
                                    <label>Caste Category</label>
                                    <input
                                        type="text"
                                        className="form-control"
                                        value={userDetails.caste_category || ""}
                                        readOnly
                                    />
                                </div>

           

                                <div className="col-md-6">
                                    <label>Mobile</label>
                                    <input
                                        type="text"
                                        className={`form-control required ${
                                            errors.mobile ? "is-invalid" : ""
                                        }`}
                                        value={userDetailsa?.mobile}
                                        onChange={(e) => {
                                            const value = e.target.value;
                                            if (value.length <= 10) {
                                                setUserDetails((d) => ({
                                                    ...d,
                                                    mobile: value,
                                                }));
                                            }
                                            setErrors((err) => ({
                                                ...err,
                                                mobile: validateMobile(
                                                    e.target.value
                                                ),
                                            }));
                                        }}
                                    />
                                    {errors.mobile && (
                                        <div className="text-danger">
                                            {errors.mobile}
                                        </div>
                                    )}
                                </div>
                                <div className="col-md-6">
                                    <label>Email</label>
                                    <input
                                        type="email"
                                        className={`form-control required ${
                                            errors.email_id ? "is-invalid" : ""
                                        }`}
                                        value={userDetailsa?.email_id}
                                        onChange={(e) => {
                                            setUserDetails((d) => ({
                                                ...d,
                                                email_id: e.target.value,
                                            }));
                                            setErrors((err) => ({
                                                ...err,
                                                email_id: validateEmail(
                                                    e.target.value
                                                ),
                                            }));
                                        }}
                                    />
                                    {errors.email_id && (
                                        <div className="text-danger">
                                            {errors.email_id}
                                        </div>
                                    )}
                                </div>
                                <div className="col-md-6">
                                    <label>Upload Profile Pic</label>
                                    <input
                                        type="file"
                                        className={`form-control required ${
                                            errors.photo ? "is-invalid" : ""
                                        }`}
                                        accept="image/*"
                                        onChange={(e) =>
                                            setUserDetails((d) => ({
                                                ...d,
                                                photo:
                                                    e.target.files?.[0] || null,
                                            }))
                                        }
                                    />
                                    {errors.photo && (
                                        <div className="text-danger">
                                            {errors.photo}
                                        </div>
                                    )}
                                    <div className="mt-1">
                                        <a
                                            href={`/api/certificates/${encodeURIComponent(
                                                userDetailsa.photo
                                            )}/photo`}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            Click here to view uploaded photo
                                        </a>
                                    </div>
                                </div>

                                <div className="col-md-6">
                                    <label>Aadhaar No</label>
                                    <input
                                        type="text"
                                        className={`form-control required ${
                                            errors.aadhaar ? "is-invalid" : ""
                                        }`}
                                        value={userDetailsa.aadhaar}
                                        onChange={(e) => {
                                            setUserDetails((d) => ({
                                                ...d,
                                                aadhaar: e.target.value,
                                            }));
                                            setErrors((err) => ({
                                                ...err,
                                                aadhaar: validateAadhaar(
                                                    e.target.value
                                                ),
                                            }));
                                        }}
                                        maxLength={12}
                                    />
                                    {errors.aadhaar && (
                                        <div className="text-danger">
                                            {errors.aadhaar}
                                        </div>
                                    )}
                                </div>

                                <div className="col-md-6">
                                    <label>Haryana Resident/Domicile</label>
                                    <select
                                        value={userDetails.domicile}
                                        className={`form-select required ${
                                            errors.aadhaar ? "is-invalid" : ""
                                        }`}
                                        onChange={(e) => {
                                            setUserDetails((d) => ({
                                                ...d,
                                                domicile: e.target.value,
                                            }));
                                            setErrors((err) => ({
                                                ...err,
                                                domicile: validateDomicle(
                                                    e.target.value
                                                ),
                                            }));
                                        }}
                                    >
                                        <option value="0" selected disabled>
                                            Select
                                        </option>
                                        <option value="1">Yes</option>
                                        <option value="2">No</option>
                                    </select>
                                    {errors.domicile && (
                                        <div className="text-danger">
                                            {errors.domicile}
                                        </div>
                                    )}
                                    
                                </div>

                                <div className="col-md-6">
                                    <label>Attach Certificate (Domicile)</label>
                                    <input
                                        type="file"
                                        accept="application/pdf"
                                        className={`form-control`}

                                        onChange={(e) => {
                                            setUserDetails((d) => ({
                                                ...d,
                                                domicile_doc:  e.target.files?.[0] || null,
                                            }));
                                        }}
                                    />
                                  
                                  {errors.domicile_doc && userDetailsa.domicile == '1' && (
                                        <div className="text-danger">
                                            {errors.domicile_doc}
                                        </div>
                                    )}

                                            <div className="mt-1">
                                                <a
                                                    href={`/api/certificates/${encodeURIComponent(
                                                        userDetailsa.domicile_doc
                                                    )}/photo`}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                >
                                                    Click here to view uploaded
                                                    file
                                                </a>
                                            </div>
                                </div>
                                <div className="text-center mt-4">
                                    <button
                                        type="button"
                                        onClick={save}
                                        className="btn btn-primary"
                                    >
                                        Save
                                    </button>
                                    <button
                                        type="button"
                                        onClick={save}
                                        className="btn btn-primary mx-2"
                                    >
                                        Next
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            )}
            {/* <div id="preloader-wrapper">
                <div id="preloader"></div>
                <div className="preloader-section section-left"></div>
                <div className="preloader-section section-right"></div>
            </div> */}
        </div>
    );
};

export default BasicDetails;
