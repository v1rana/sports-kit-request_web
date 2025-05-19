import React, { useEffect, useRef, useState } from "react";
import { Link } from "react-router-dom";
import { useForm } from "react-hook-form";
import { useNavigate } from "react-router-dom";
import { fetchUserDetails, updateUserData } from "../services/hosp-service";
import { toast } from 'react-toastify';
const BasicDetails = () => {
    const navigate = useNavigate();
    let userData = JSON.parse(localStorage.getItem("user")!);
    let userDetails = userData?.user_details || {};

    console.log('userDetails',userDetails.date_of_birth);


    const dob = userDetails.date_of_birth; // dd-mm-yyyy
    const birthDate = new Date(dob);

    const today = new Date();
    userDetails.age = today.getFullYear() - birthDate.getFullYear();

    // const indianStates = [
    //     "Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar", "Chhattisgarh",
    //     "Goa", "Gujarat", "Himachal Pradesh", "Jharkhand",
    //     "Karnataka", "Kerala", "Madhya Pradesh", "Maharashtra", "Manipur",
    //     "Meghalaya", "Mizoram", "Nagaland", "Odisha", "Punjab",
    //     "Rajasthan", "Sikkim", "Tamil Nadu", "Telangana", "Tripura",
    //     "Uttar Pradesh", "Uttarakhand", "West Bengal",
    //     "Andaman and Nicobar Islands", "Chandigarh", "Dadra and Nagar Haveli and Daman and Diu",
    //     "Delhi", "Jammu and Kashmir", "Ladakh", "Lakshadweep", "Puducherry"
    //   ];
    
    const [userDetailsa, setUserDetails] = useState({
        email_id: userData.email??'',
        mobile: userData.mobile,
        aadhaar: userDetails.aadhaar??'',
        photo: userDetails.photo,
        dob_doc: userDetails.dob_doc,
        domicile: userDetails.domicile??null,
        domicile_doc: userDetails.domicile_doc,
        caste_category: userDetails.caste_category,
        age: userDetails.age,
        // other_state: userDetails.other_state,
        played_national_level:userDetails.played_national_level??'',
        national_level_doc:userDetails.national_level_doc,
        organisation_represented:userDetails.organisation_represented,
        organisation_doc:userDetails.organisation_doc,
    });
    const [errors, setErrors] = useState({
        email_id: "",
        mobile: "",
        aadhaar: "",
        photo: "",
        dob_doc: "",
        domicile: "",
        domicile_doc: "",
        played_national_level: "",
        national_level_doc: "",
        organisation_represented: "",
        organisation_doc: "",
        caste_category: "",
        // other_state: "",
    });
    const domicileFileRef = useRef(null);
    const nationalCerFileRef = useRef(null);
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
        if(value == '2') {
            toast.error("Ineligible to apply")
            return 'Ineligible to apply';
        }

        if (!value) return "Please select the haryana resident/domicile";
        return "";
    };

    const validateDobDoc = (value: string) => {
        if (!value)
            return "Please upload birth certificate";
        return "";
    };
    const validateCasteAge = (value: string) => {
        if (!value) {
            return "Please enter caste category";
        }
    
        const age = parseInt(userDetailsa.age); // make sure it's a number
    
        if (value === 'general') {
            if (!age || age < 18 || age > 42) {
                toast.error("Ineligible to apply (Age must be between 18 and 42 for General category)");
                return "Ineligible to apply (Age must be between 18 and 42 for General category)";
            }
        }else if(!age || age < 18) {
            toast.error("Ineligible to apply (Age must be greater than 18 )");
            return "Ineligible to apply (Age must be greater than 18 )";
        }
    
        return "";
    };
     const validateDomicleDoc = (value: string) => {
        if (!value && userDetailsa.domicile == "1")
            return "Please upload haryana resident/domicile";
        return "";
    };
    const validateNationalLevel = (value: string) => {
        if (!value )
            return "Please select national level for haryana";
        return "";
    };
    const validateNationalDoc = (value: string) => {
        if (!value && userDetailsa.played_national_level == "1")
            return "Please upload file";
        return "";
    };
    
     const validateCentralOrg = (value: string) => {
        if (!value && userDetailsa.played_national_level == "2")
            return "Central organisation represented is required";
        return "";
    };
    const validateOrgDoc = (value: string) => {
        if (!value && userDetailsa.played_national_level == "2")
            return "Please upload file";
        return "";
    };

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

    const save = async (is_save = false) => {
        const newErrors: any = {};

        const castError = validateCasteAge(userDetailsa.caste_category);
        if (castError) newErrors.caste_category = castError;

        const emailError = validateEmail(userDetailsa.email_id);
        if (emailError) newErrors.email_id = emailError;

        const mobileError = validateMobile(userDetailsa.mobile);
        if (mobileError) newErrors.mobile = mobileError;

        const aadhaarError = validateAadhaar(userDetailsa.aadhaar);
        if (aadhaarError) newErrors.aadhaar = aadhaarError;

        const dobCerError = validateDobDoc(userDetailsa.dob_doc);
        if (dobCerError) newErrors.dob_doc = dobCerError;
        
        const domicleError = validateDomicle(userDetailsa.domicile);
        if (domicleError) newErrors.domicile = domicleError;

        const domicleDocError = validateDomicleDoc(userDetailsa.domicile_doc);
        if (domicleDocError) newErrors.domicile_doc = domicleDocError;


        const nationLevelError = validateNationalLevel(userDetailsa.played_national_level);
        if (nationLevelError) newErrors.played_national_level = nationLevelError;

        const nationalDocError = validateNationalDoc(userDetailsa.national_level_doc);
        if (nationalDocError) newErrors.national_level_doc = nationalDocError;

        const centralOrgError = validateCentralOrg(userDetailsa.organisation_represented);
        if (centralOrgError) newErrors.organisation_represented = centralOrgError;

        const orgDocError = validateOrgDoc(userDetailsa.organisation_doc);
        if (orgDocError) newErrors.organisation_doc = orgDocError;

        if (!userDetailsa.photo) newErrors.photo = "Profile photo is required";
        setErrors(newErrors);

        // Block save if any error exists
        if (Object.keys(newErrors).length > 0) return;
        // Build FormData for API
        const formData = new FormData();
        formData.append("email_id", userDetailsa.email_id);
        formData.append("mobile", userDetailsa.mobile);
        formData.append("age", userDetailsa.age);
        formData.append("aadhaar", userDetailsa.aadhaar);
        formData.append("photo", userDetailsa.photo); // This must be a File
        formData.append("domicile", userDetailsa.domicile);
        formData.append("played_national_level", userDetailsa.played_national_level);
        formData.append("organisation_represented", userDetailsa.organisation_represented);
        formData.append("dob_doc", userDetailsa.dob_doc); // This must be a File
        formData.append("domicile_doc", userDetailsa.domicile== '1'? userDetailsa.domicile_doc : null); // This must be a File
        formData.append("national_level_doc", userDetailsa.played_national_level== '1'? userDetailsa.national_level_doc : null); // This must be a File
        formData.append("organisation_doc", userDetailsa.played_national_level== '2'? userDetailsa.organisation_doc : null); // This must be a File
        const response = await updateUserData(formData);
        if (response.status === "success") {
            localStorage.setItem("user", JSON.stringify(response.user));
            if(!is_save) {
                navigate("/hosp/hosp-form");
            }
            
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
                    <header className="hero-section">
                        <div className="hero-content">
                            <img
                                src="./assets/images/logo-sports.png"
                                alt="Sports Department Logo"
                                className="header-logo mx-3"
                            />
                            <div className="hero-text">
                                <h1>
                                    Sports Department, Government of Haryana
                                </h1>
                                <p>
                                    Let the young minds grow to the full
                                    potential
                                </p>
                            </div>
                        </div>

                        <div className="hero-wave">
                            <svg
                                viewBox="0 0 500 150"
                                preserveAspectRatio="none"
                            >
                                <path
                                    d="M0.00,49.98 C157.87,179.29 349.61,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z"
                                    style={{ stroke: "none", fill: "#f0f0f0" }}
                                ></path>
                            </svg>
                        </div>
                    </header>

                    <div className="container form-container">
                    <div className="progress">
                    <ol>
                    <li
                            className="progress-active"
                        >
                            <span>1. Basic Details</span>
                        </li>
                        
                        <li
                           
                        >
                            <span>2. Education Details</span>
                        </li>
                        <li
                           
                        >
                            <span>3. Best Sports Achievement </span>
                        </li>
                        <li
                        >
                            <span>4. Declaration</span>
                        </li>
                    </ol>
                    <div
                        className="progress-bar progress-bar-striped progress-bar-animated bg-success"
                        role="progressbar"
                        style={{ width: `${25}%` }}
                    ></div>
                </div>
                        <h3 className="text-center mt-5">Basic Details Form</h3>
                        <form>
                       
                            <div className="row g-3">
                            
                                
                                <div className="col-md-6">
                                    <label>Parivar Pehchan Patra ID</label>
                                    <input
                                        type="text"
                                        className="form-control"
                                        value={userDetails.family_id || ""}
                                        readOnly
                                        placeholder="Enter full name in English"
                                    />
                                </div>
                                <div className="col-md-6">
                                    <label>Name</label>
                                    <input
                                        type="text"
                                        className="form-control"
                                        value={userDetails.full_name_en || ""}
                                        readOnly
                                        placeholder="Enter full name in English"
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
                                    <label>Date Of Birth</label>
                                    <input
                                        type="date"
                                        className="form-control"
                                        value={userDetails.date_of_birth || ""}
                                        readOnly
                                    />
                                </div>
                                <div className="col-md-6">
                                    <label>Upload Profile Pic</label>
                                    <input
                                        type="file"
                                        className={`form-control required ${
                                            errors.photo ? "is-invalid" : ""
                                        }`}
                                        accept="image/*"
                                        onChange={(e) => {
                                            const file = e.target.files?.[0] || null;
                                    
                                            // Update photo
                                            setUserDetails((prev) => ({
                                                ...prev,
                                                photo: file,
                                            }));
                                    
                                            // Clear photo error if file is selected
                                            if (file) {
                                                setErrors((prev:any) => ({
                                                    ...prev,
                                                    photo: null,
                                                }));
                                            }
                                    
                                            
                                        }}
                                    />
                                    {errors.photo && (
                                        <div className="text-danger">
                                            {errors.photo}
                                        </div>
                                    )}
                                     {userDetails.photo && 
                                        <div className="mt-1">
                                            <a
                                                href={`/storage/photo/${encodeURIComponent(
                                                    userDetailsa.photo
                                                )}`}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                            >
                                                Click here to view uploaded photo
                                            </a>
                                        </div>
                                    }
                                </div>
                             

                              
                                <div className="col-md-6">
                                    <label>Upload Birth Certificate or Matriculation Certificate</label>
                                    <input
                                        type="file"
                                        className={`form-control required ${
                                            errors.dob_doc ? "is-invalid" : ""
                                        }`}
                                        accept="application/pdf"
                                        onChange={(e) => {
                                            const file = e.target.files?.[0] || null;
                                    
                                            // Update photo
                                            setUserDetails((prev) => ({
                                                ...prev,
                                                dob_doc: file,
                                            }));
                                    
                                            // Clear photo error if file is selected
                                            if (file) {
                                                setErrors((prev:any) => ({
                                                    ...prev,
                                                    dob_doc: null,
                                                }));
                                            }
                                    
                                            
                                        }}
                                    />
                                    {errors.dob_doc && (
                                        <div className="text-danger">
                                            {errors.dob_doc}
                                        </div>
                                    )}
                                     {userDetails.dob_doc && 
                                        <div className="mt-1">
                                            <a
                                               href={`/api/certificates/${encodeURIComponent(
                                                userDetailsa.dob_doc
                                            )}/certificates`}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                            >
                                                Click here to view uploaded photo
                                            </a>
                                        </div>
                                    }
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
                                    <label>Mailing Address</label>
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
                                    <label>Haryana Resident/Domicile</label>
                                    <select
                                        value={userDetailsa.domicile}
                                        className={`form-select required ${
                                            errors.domicile ? "is-invalid" : ""
                                        }`}
                                        onChange={(e) => {
                                            const value = e.target.value;
                                            setUserDetails((d) => ({
                                                ...d,
                                                domicile: value,
                                                domicile_doc: value === "2" ? null : d.domicile_doc, // Clear if "No"
                                            }));
                                            setErrors((err) => ({
                                                ...err,
                                                domicile: validateDomicle(
                                                    e.target.value
                                                ),
                                            }));
                                            // Clear the file input if "No" is selected
                                        if ((value == '' || value === "2") && domicileFileRef.current) {
                                            domicileFileRef.current.value = null;
                                        }
                                        }}
                                    >
                                        <option value="">
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
                                     ref={domicileFileRef}
                                        type="file"
                                        accept="application/pdf"
                                        className={`form-control`}
                                        onChange={(e) => {
                                            setUserDetails((d) => ({
                                                ...d,
                                                domicile_doc:
                                                    e.target.files?.[0] || null,
                                            }));
                                        }}

                                        disabled={ !userDetailsa.domicile ||  userDetailsa.domicile == '2'}
                                    />

                                    {errors.domicile_doc &&
                                        userDetailsa.domicile == "1" && (
                                            <div className="text-danger">
                                                {errors.domicile_doc}
                                            </div>
                                        )}
                                    {userDetails.domicile_doc && userDetailsa.domicile_doc && 
                                    <div className="mt-1">
                                        <a
                                            href={`/api/certificates/${encodeURIComponent(
                                                userDetailsa.domicile_doc
                                            )}/certificates`}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            Click here to view uploaded file
                                        </a>
                                    </div>
                                    }
                                </div>
                                {/* <div className="col-md-6">
                                    <label>Select which State are you a domicile/resident of</label>
                                    <select
                                        value={userDetailsa.other_state}
                                        className={`form-select required ${
                                            errors.other_state ? "is-invalid" : ""
                                        }`}
                                        onChange={(e) => {
                                            const value = e.target.value;
                                            setUserDetails((d) => ({
                                                ...d,
                                                other_state: value,
                                            }));
                                            setErrors((err) => ({
                                                ...err,
                                                other_state: validateDomicle(
                                                    e.target.value
                                                ),
                                            }));
                                            
                                      
                                        }}
                                    >
                                        <option value="">
                                            Select
                                        </option>
                                        {indianStates.map((state, index) => (
                                        <option key={index} value={state}>{state}</option>
                                        ))}
                                    </select>
                                    {errors.other_state && (
                                        <div className="text-danger">
                                            {errors.other_state}
                                        </div>
                                    )}
                                </div> */}



<div className="col-md-6">
                                    <label>
                                        Played at National Level for Haryana
                                    </label>
                                    <select
                                        value={userDetailsa.played_national_level}
                                        className={`form-select required ${
                                            errors.played_national_level ? "is-invalid" : ""
                                        }`}
                                        onChange={(e) => {
                                            const value = e.target.value;
                                            setUserDetails((d) => ({
                                                ...d,
                                                played_national_level: value,
                                                national_level_doc: value === "2" ? null : d.national_level_doc, // Clear if "No"
                                            }));
                                            setErrors((err) => ({
                                                ...err,
                                                played_national_level: validateNationalLevel(
                                                    e.target.value
                                                ),
                                            }));
                                            // Clear the file input if "No" is selected
                                        if ((value == '' || value === "2") && nationalCerFileRef.current) {
                                            nationalCerFileRef.current.value = null;
                                        }
                                        }}
                                    >
                                        <option value="0" selected disabled>
                                            Select
                                        </option>
                                        <option value="1">Yes</option>
                                        <option value="2">No</option>
                                    </select>
                                    {errors.played_national_level && (
                                        <div className="invalid-feedback">
                                            {
                                                errors.played_national_level
                                            }
                                        </div>
                                    )}
                                </div>

                                <div className="col-md-6">
                                    <label>
                                        Attach Certificate (National Level)
                                    </label>
                                    <input
                                        type="file"
                                        accept="application/pdf"
                                        className={`form-control ${
                                            errors.national_level_doc
                                                ? "is-invalid"
                                                : ""
                                        }`}
                                       
                                        onChange={(e) => {
                                            setUserDetails((d) => ({
                                                ...d,
                                                national_level_doc:
                                                    e.target.files?.[0] || null,
                                            }));
                                        }}

                                        disabled={ !userDetailsa.played_national_level ||  userDetailsa.played_national_level == '2'}
                                    />

                                    {errors.national_level_doc &&
                                        userDetailsa.played_national_level == "1" && (
                                            <div className="text-danger">
                                                {errors.national_level_doc}
                                            </div>
                                        )}
                                    {userDetails.national_level_doc && userDetailsa.national_level_doc && 
                                    <div className="mt-1">
                                        <a
                                            href={`/api/certificates/${encodeURIComponent(
                                                userDetailsa.national_level_doc
                                            )}/certificates`}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            Click here to view uploaded file
                                        </a>
                                    </div>
                                    }
                                </div>
                                {userDetailsa.played_national_level == "2" && (
                                    <div className="col-md-6">
                                        <label>
                                            Name of Central Organisation
                                            Represented
                                        </label>
                                        <input
                                            type="text"
                                            className={`form-control required ${
                                                errors.organisation_represented ? "is-invalid" : ""
                                            }`}
                                            value={userDetailsa?.organisation_represented}
                                            onChange={(e) => {
                                                setUserDetails((d) => ({
                                                    ...d,
                                                    organisation_represented: e.target.value,
                                                }));
                                                setErrors((err) => ({
                                                    ...err,
                                                    organisation_represented: validateCentralOrg(
                                                        e.target.value
                                                    ),
                                                }));
                                            }}
                                        />
                                        {errors.organisation_represented && (
                                            <div className="invalid-feedback">
                                                {errors.organisation_represented}
                                            </div>
                                        )}
                                    </div>
                                )}
                                {userDetailsa.played_national_level == "2" && (
                                    <div className="col-md-6">
                                        <label>
                                            Attach Certificate (Organisation
                                            Represented)
                                        </label>
                                        <input
                                            type="file"
                                            accept="application/pdf"
                                            className={`form-control ${
                                                errors.organisation_doc
                                                    ? "is-invalid"
                                                    : ""
                                            }`}
                                           
                                            onChange={(e) => {
                                                setUserDetails((d) => ({
                                                    ...d,
                                                    organisation_doc:
                                                        e.target.files?.[0] || null,
                                                }));
                                            }}
    
                                            disabled={ !userDetailsa.played_national_level ||  userDetailsa.played_national_level == '1'}
                                        />
    
                                        {errors.organisation_doc &&
                                            userDetailsa.played_national_level == "2" && (
                                                <div className="text-danger">
                                                    {errors.organisation_doc}
                                                </div>
                                            )}
                                        {userDetails.organisation_doc && userDetailsa.organisation_doc && 
                                        <div className="mt-1">
                                            <a
                                                href={`/api/certificates/${encodeURIComponent(
                                                    userDetailsa.organisation_doc
                                                )}/certificates`}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                            >
                                                Click here to view uploaded file
                                            </a>
                                        </div>
                                        }
                                    </div>
                                )}
                                <hr />
                                <div className="text-center mt-1">
                                    <button
                                        type="button"
                                        onClick={() => save(true)}
                                        className="btn btn-primary"
                                    >
                                        Save
                                    </button>
                                    <button
                                        type="button"
                                        onClick={() => save(false)}
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
