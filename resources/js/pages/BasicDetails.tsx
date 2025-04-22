import React from "react";
import { Link } from "react-router-dom";
import { useForm } from "react-hook-form";
import { useNavigate } from "react-router-dom";

const BasicDetails = () => {
    const navigate = useNavigate();
    
    // Get user data from localStorage
    const userData = JSON.parse(localStorage.getItem('user')!);
    const userDetails = userData?.user_details || {};
    
    const save = () => {
      const loginType = localStorage.getItem('loginType');
      if(loginType == 'equipment') {
        navigate("/sports-kit");
        window.location.reload()
      }else if(loginType == 'gradation') {
        navigate("/dashboard");
        window.location.reload()
      }else if(loginType == 'hosp') {
        navigate("/hosp/dashboard");
        // window.location.reload()
      }
    };

    // Helper function to convert gender code to text
    const getGenderText = (genderCode) => {
        switch(genderCode) {
            case 'M': return 'Male';
            case 'F': return 'Female';
            case 'O': return 'Other';
            default: return genderCode;
        }
    };

    // Helper function to convert marital status code to text
    const getMaritalStatusText = (status) => {
        if (!status) return '';
        return status.charAt(0).toUpperCase() + status.slice(1).toLowerCase();
    };

    // Helper function to convert boolean to Yes/No
    const getYesNoText = (value) => {
        if (value === 1 || value === true) return 'Yes';
        if (value === 0 || value === false) return 'No';
        return value || '';
    };

    return (
        <div>
            <div className="container d-flex align-items-center min-vh-100">
                <div className="row g-0 justify-content-center w-100">
               
                    <div className="col-lg-11 offset-lg-1 mx-0 px-0">
                        <div id="title-container">
                       
                            <h2>Sports Department , Government of Haryana</h2>
                            {/* <h3>Recruitment Form</h3> */}
                            <p>
                            Let the young minds grow to the full potential
                            </p>
                            <div className="float-end m-2">
                                <Link to="/hosp/login">
                                    <button className="btn btn-danger me-1">
                                        Logout <i className="fa fa-sign-out"></i>
                                    </button>
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div className="col-lg-12 px-0">
                        <div id="qbox-container">
                            <div>
                                <form
                                    onSubmit={(e) => e.preventDefault()}
                                    className="needs-validation row g-3"
                                >
                                    <h3 className="text-center">
                                        Basic Details
                                    </h3>
                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputFullNameEn"
                                            className="form-label"
                                        >
                                            Full Name (English)
                                        </label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="inputFullNameEn"
                                            placeholder="Full Name"
                                            value={userDetails.full_name_en || ''}
                                            readOnly
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputPassword4"
                                            className="form-label"
                                        >
                                            Full Name (Hindi)
                                        </label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="inputPassword4"
                                            placeholder="Full Name"
                                            value={userDetails.full_name_hi || ''}
                                            readOnly
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputEmail4"
                                            className="form-label"
                                        >
                                            Father Name (English)
                                        </label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="inputEmail4"
                                            placeholder="Father Name"
                                            value={userDetails.father_name_en || ''}
                                            readOnly
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputPassword4"
                                            className="form-label"
                                        >
                                            Father Name (Hindi)
                                        </label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="inputPassword4"
                                            placeholder="Father Name"
                                            value={userDetails.father_name_hi || ''}
                                            readOnly
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputEmail4"
                                            className="form-label"
                                        >
                                            Mother Name (English)
                                        </label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="inputEmail4"
                                            placeholder="Mother Name"
                                            value={userDetails.mother_name_en || ''}
                                            readOnly
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputPassword4"
                                            className="form-label"
                                        >
                                            Mother Name (Hindi)
                                        </label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="inputPassword4"
                                            placeholder="Mother Name"
                                            value={userDetails.mother_name_hi || ''}
                                            readOnly
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputDob"
                                            className="form-label"
                                        >
                                            Date Of Birth
                                        </label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="inputDob"
                                            value={userDetails.date_of_birth || ''}
                                            readOnly
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputAge"
                                            className="form-label"
                                        >
                                            Age
                                        </label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="inputAge"
                                            value={userDetails.age || ''}
                                            readOnly
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputGender"
                                            className="form-label"
                                        >
                                            Gender
                                        </label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="inputGender"
                                            value={getGenderText(userDetails.gender)}
                                            readOnly
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputMarital"
                                            className="form-label"
                                        >
                                            Marital Status
                                        </label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="inputMarital"
                                            value={getMaritalStatusText(userDetails.marital_status)}
                                            readOnly
                                        />
                                    </div>
                                    <hr />
                                    <div className="col-12">
                                        <label
                                            htmlFor="inputAddress"
                                            className="form-label"
                                        >
                                            Address
                                        </label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="inputAddress"
                                            placeholder="1234 Main St"
                                            value={userDetails.address_landMark || ''}
                                            readOnly
                                        />
                                    </div>

                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputDistrict"
                                            className="form-label"
                                        >
                                            District
                                        </label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="inputDistrict"
                                            value={userDetails.district || ''}
                                            readOnly
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputBlock"
                                            className="form-label"
                                        >
                                            Block
                                        </label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="inputBlock"
                                            value={userDetails.block_town || ''}
                                            readOnly
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputVill"
                                            className="form-label"
                                        >
                                            Ward/Village
                                        </label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="inputVill"
                                            value={userDetails.ward_village || ''}
                                            readOnly
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputZip"
                                            className="form-label"
                                        >
                                            Pincode
                                        </label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="inputZip"
                                            value={userDetails.pincode || ''}
                                            readOnly
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputMobile"
                                            className="form-label"
                                        >
                                            Mobile
                                        </label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="inputMobile"
                                            value={userData.mobile || ''}
                                            readOnly
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputEmail"
                                            className="form-label"
                                        >
                                            Email
                                        </label>
                                        <input
                                            type="email"
                                            className="form-control"
                                            id="inputEmail"
                                            value={userData.email || userDetails.email_id || ''}
                                            readOnly
                                        />
                                    </div>
                                    <hr />

                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputBenchmark"
                                            className="form-label"
                                        >
                                            Benchmark Disability
                                        </label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="inputBenchmark"
                                            value={getYesNoText(userDetails.benchmark_disability)}
                                            readOnly
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputCaste"
                                            className="form-label"
                                        >
                                            Caste Category
                                        </label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="inputCaste"
                                            value={userDetails.caste_category || ''}
                                            readOnly
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputHighest"
                                            className="form-label"
                                        >
                                            Highest Qualification
                                        </label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="inputHighest"
                                            value={userDetails.highest_qualification || ''}
                                            readOnly
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputEngagement"
                                            className="form-label"
                                        >
                                            Current Engagement
                                        </label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="inputEngagement"
                                            value={userDetails.current_engagement === 'N' ? 'Not Employed' : 'Employed'}
                                            readOnly
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputIncome"
                                            className="form-label"
                                        >
                                            Total Annual Family Income
                                        </label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="inputIncome"
                                            value={userDetails.annual_income || ''}
                                            readOnly
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputVerified"
                                            className="form-label"
                                        >
                                            Income Verified
                                        </label>
                                        <input
                                            type="email"
                                            className="form-control"
                                            id="inputVerified"
                                            value={getYesNoText(userDetails.income_verified)}
                                            readOnly
                                        />
                                    </div>
                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputAlternateNo"
                                            className="form-label"
                                        >
                                            Alternate Number
                                        </label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            id="inputAlternateNo"
                                            value={userDetails.alternate_number || ''}
                                            readOnly
                                        />
                                    </div>

                                    <div className="col-md-6">
                                        <label
                                            htmlFor="inputAlternateEmail"
                                            className="form-label"
                                        >
                                            Alternate Email
                                        </label>
                                        <input
                                            type="email"
                                            className="form-control"
                                            id="inputAlternateEmail"
                                            value={userDetails.alternate_email || ''}
                                            readOnly
                                        />
                                    </div>
                                </form>
                               
                                <div id="q-box__buttons">
                                    <button
                                        id="next-btn"
                                        type="button"
                                        onClick={save}
                                        className="btn btn-primary me-2"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="preloader-wrapper">
                <div id="preloader"></div>
                <div className="preloader-section section-left"></div>
                <div className="preloader-section section-right"></div>
            </div>
        </div>
    );
};

export default BasicDetails;