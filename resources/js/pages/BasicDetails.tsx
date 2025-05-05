import React, { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { useForm } from "react-hook-form";
import { useNavigate } from "react-router-dom";
import { updateUserData } from "../services/hosp-service";

const BasicDetails = () => {
    const navigate = useNavigate();
    const userData = JSON.parse(localStorage.getItem("user")!);
    const userDetails = userData?.user_details || {};
    const [userDetailsa, setUserDetails] = useState({
        email_id: userDetails.email_id,
        mobile: userData.mobile,
        aadhaar: userDetails.aadhaar,
        photo: userDetails.photo,
    });
    const [errors, setErrors] = useState({
        email_id: "",
        mobile: "",
        aadhaar: "",
        photo: "",
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
                                {/* <div className="col-md-6">
                <label>Full Name (Hindi)</label>
                <input type="text" className="form-control" placeholder="हिंदी में पूरा नाम दर्ज करें"  value={userDetails.full_name_hi || ''}
                                            readOnly />
              </div>
      
      
              <div className="col-md-6">
                <label>Father Name (English)</label>
                <input type="text" className="form-control" value={userDetails.father_name_en || ''}
                                            readOnly />
              </div>
              <div className="col-md-6">
                <label>Father Name (Hindi)</label>
                <input type="text" className="form-control" value={userDetails.father_name_hi || ''}
                                            readOnly />
              </div>
      
      
              <div className="col-md-6">
                <label>Mother Name (English)</label>
                <input type="text" className="form-control" value={userDetails.mother_name_en || ''}
                                            readOnly/>
              </div>
              <div className="col-md-6">
                <label>Mother Name (Hindi)</label>
                <input type="text" className="form-control" value={userDetails.mother_name_hi || ''}
                                            readOnly />
              </div> */}

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

                                {/* <div className="col-md-6">
                <label>Gender</label>
                <input type="number" className="form-control"  value={getGenderText(userDetails.gender)}
                                            readOnly/>
              </div> */}
                                {/* <div className="col-md-6">
                <label>Marital Status</label>
                <input type="number" className="form-control"  value={userDetails.marital_status || ''}
                                            readOnly/>
              </div>
      
      
              <div className="col-md-12">
                <label>Address</label>
                <input type="text" className="form-control"  value={userDetails.address_landMark || ''}
                                            readOnly/>
              </div>
      
      
              <div className="col-md-6">
                <label>District</label>
                <input type="text" className="form-control" value={userDetails.district || ''}
                                            readOnly/>
              </div>
              <div className="col-md-6">
                <label>Block</label>
                <input type="text" className="form-control" value={userDetails.block_town || ''}
                                            readOnly/>
              </div>
      
      
              <div className="col-md-6">
                <label>Ward/Village</label>
                <input type="text" className="form-control" value={userDetails.ward_village || ''}
                                            readOnly/>
              </div>
              <div className="col-md-6">
                <label>Pincode</label>
                <input type="text" className="form-control" value={userDetails.pincode || ''}
                                            readOnly/>
              </div> */}

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

                                {/* <div className="col-md-6">
                <label>Highest Qualification</label>
                <input type="text" className="form-control" value={userDetails.highest_qualification || ''}
                                            readOnly/>
              </div>
              <div className="col-md-6">
                <label>Current Engagement</label>
                <input type="text" className="form-control"  value={userDetails.current_engagement === 'N' ? 'Not Employed' : 'Employed'}
                                            readOnly/>
              </div>
      
      
              <div className="col-md-6">
                <label>Total Annual Family Income</label>
                <input type="text" className="form-control" value={userDetails.annual_income || ''}
                                            readOnly/>
              </div>
              <div className="col-md-6">
                <label>Income Verified</label>
                <input type="text" className="form-control" value={userDetails.income_verified || ''}
                                            readOnly/>
              </div>
      
      
              <div className="col-md-6">
                <label>Alternate Number</label>
                <input type="text" className="form-control" value={userDetails.alternate_number || ''}
                                            readOnly/>
              </div>
              <div className="col-md-6">
                <label>Alternate Email</label>
                <input type="email" className="form-control"  value={userDetails.alternate_email || ''}
                                            readOnly/>
              </div> */}

                                <div className="text-center mt-4">
                                    <button
                                        type="button"
                                        onClick={save}
                                        className="save-btn"
                                    >
                                        Save
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
