import React, { useEffect } from "react";
import { Link } from "react-router-dom";
import { useForm } from "react-hook-form";
import { useNavigate } from "react-router-dom";

const BasicDetails = () => {
    const navigate = useNavigate();
    const userData = JSON.parse(localStorage.getItem('user')!);
    const userDetails = userData?.user_details || {};
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
      }

    const save = () => {
      const loginType = localStorage.getItem('loginType');
      if(loginType == 'equipment') {
        navigate("/sports-kit");
        window.location.reload()
      }else if(loginType == 'gradation') {
        // navigate("/apply.certificate.form/id"+userData?.user_details?.id);
        navigate("/apply.certificate.form?user_id=" + userData?.user_details?.id);
        window.location.reload()
      }else if(loginType == 'hosp') {
        navigate("/hosp/dashboard?step=1");
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
             {userData &&(
    
                <div>
        <header className="hero-section">
          <h1>Sports Department, Government of Haryana</h1>
          <p>Let the young minds grow to the full potential</p>
      
          <div className="hero-wave">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none">
              <path d="M0.00,49.98 C157.87,179.29 349.61,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z"
                style={{stroke: 'none', fill: '#f0f0f0'}}></path>
            </svg>
          </div>
        </header>
      
        <div className="container form-container">
          <h3 className="form-heading">Basic Details Form</h3>
          <form>
            <div className="row g-3">
      
              <div className="col-md-6">
                <label>Full Name (English)</label>
                <input type="text" className="form-control"   value={userDetails.full_name_en || ''}
                                            readOnly placeholder="Enter full name in English" />
              </div>
              <div className="col-md-6">
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
              </div>
      
      
              <div className="col-md-6">
                <label>Date Of Birth</label>
                <input type="date" className="form-control"  value={userDetails.date_of_birth || ''}
                                            readOnly/>
              </div>
              <div className="col-md-6">
                <label>Age</label>
                <input type="number" className="form-control"  value={userDetails.age || ''}
                                            readOnly/>
              </div>
      
      
              <div className="col-md-6">
                <label>Gender</label>
                <input type="number" className="form-control"  value={getGenderText(userDetails.gender)}
                                            readOnly/>
              </div>
              <div className="col-md-6">
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
              </div>
      
      
              <div className="col-md-6">
                <label>Mobile</label>
                <input type="text" className="form-control" value={userDetails.mobile || ''}
                                            readOnly/>
              </div>
              <div className="col-md-6">
                <label>Email</label>
                <input type="email" className="form-control" value={userDetails.email_id || ''}
                                            readOnly/>
              </div>
      
      
              <div className="col-md-6">
                <label>Benchmark Disability</label>
                <input type="text" className="form-control" value={userDetails.benchmark_disability || ''}
                                            readOnly/>
              </div>
              <div className="col-md-6">
                <label>Caste Category</label>
                <input type="text" className="form-control" value={userDetails.caste_category || ''}
                                            readOnly/>
              </div>
      
      
              <div className="col-md-6">
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
              </div>
      
      
              <div className="text-center mt-4">
                <button type="button"  onClick={save} className="save-btn">Save</button>
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