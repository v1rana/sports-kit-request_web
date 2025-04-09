import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { useForm, useFormState } from 'react-hook-form';
const stepsTotal = 5;

const Dashboard = () => {
    const params = new URLSearchParams(window.location.search);
    const step:any =  params.get('step') ? parseInt(params.get('step')!) : 1;
    // alert(step)
  const [currentStep, setCurrentStep] = useState(step);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [isSubmitted, setIsSubmitted] = useState(false);

    const {
      register,
      handleSubmit,
      watch,
      formState: { errors },
    } = useForm();
  
    // Watch individual fields for conditional logic
    const playedNational = watch('played_national');
    const domicile = watch('domicile');
  
    const onSubmit = (data) => {
      console.log('Form Data:', data);
      // Here you can handle the form submission (e.g. send to API)
    };
  const progressPercent = (100 / stepsTotal) * currentStep;

  
  const nextStep = () => {
    if (currentStep < stepsTotal) setCurrentStep(prev => prev + 1);
  };

  const prevStep = () => {
    if (currentStep > 0) setCurrentStep(prev => prev - 1);
  };

  const handleSubmit1 = async () => {
    document.body.classList.add('loaded');
    setIsSubmitting(true);
    await new Promise(res => setTimeout(res, 3000));
    document.body.classList.remove('loaded'); 
    setIsSubmitting(false);
    setIsSubmitted(true);
  };

  return (
    <div>
    <div className="container d-flex align-items-center min-vh-100">
      <div className="row g-0 justify-content-center w-100">
      <div className="col-lg-12 offset-lg-1 mx-0 px-0">
          <div id="title-container">
            {/* <img className="covid-image" src="/assets/job_app/img/covid-check.png" /> */}
            <h2>Haryana Outstanding Sports Persons</h2>
            <h3>Recruitment Form</h3>
            <p>
            Appointed to the Haryana Outstanding Sports Service (Group A, B, and C)
            </p>
          </div>
        </div>

        <div className="col-lg-12 px-0">
          <div className="progress">
            <div
              className="progress-bar progress-bar-striped progress-bar-animated bg-danger"
              role="progressbar"
              style={{ width: `${progressPercent}%` }}
            ></div>
          </div>
          <div id="qbox-container">
          {isSubmitting && (
            // <div className="d-block text-center mt-5">Submitting...</div>
            <div id="preloader-wrapper">
                <div id="preloader"></div>
                <div className="preloader-section section-left"></div>
                <div className="preloader-section section-right"></div>
          </div>
          )}

          {!isSubmitted && !isSubmitting && (
           <div>
             <form onSubmit={e => e.preventDefault()}  className="needs-validation row g-3" hidden={currentStep === 1 ? false : true}>
                 <h3 className='text-center'>Basic Details</h3>
             <div className="col-md-6">
               <label htmlFor="inputFullNameEn" className="form-label">Full Name (English)</label>
               <input type="text" className="form-control" id="inputFullNameEn" placeholder="Full Name" />
             </div>
             <div className="col-md-6">
               <label htmlFor="inputPassword4" className="form-label">Full Name (Hindi)</label>
               <input type="text" className="form-control" id="inputPassword4" placeholder="Full Name" />
             </div>
            <div className="col-md-6">
               <label htmlFor="inputEmail4" className="form-label">Father Name (English)</label>
               <input type="text" className="form-control" id="inputEmail4" placeholder="Father Name"/>
             </div>
             <div className="col-md-6">
               <label htmlFor="inputPassword4" className="form-label">Father Name (Hindi)</label>
               <input type="text" className="form-control" id="inputPassword4" placeholder="Father Name" />
             </div>
             <div className="col-md-6">
               <label htmlFor="inputEmail4" className="form-label">Mother Name (English)</label>
               <input type="text" className="form-control" id="inputEmail4" placeholder="Mother Name"/>
             </div>
             <div className="col-md-6">
               <label htmlFor="inputPassword4" className="form-label">Mother Name (Hindi)</label>
               <input type="text" className="form-control" id="inputPassword4" placeholder="Mother Name"/>
             </div>
              <div className="col-md-6">
               <label htmlFor="inputDob" className="form-label">Date Of Birth</label>
               <input type="text" className="form-control" id="inputDob" />
             </div>
             <div className="col-md-6">
               <label htmlFor="inputAge" className="form-label">Age</label>
               <input type="text" className="form-control" id="inputAge" />
             </div>
             <div className="col-md-6">
               <label htmlFor="inputGender" className="form-label">Gender</label>
               <input type="text" className="form-control" id="inputGender" />
             </div>
             <div className="col-md-6">
               <label htmlFor="inputMarital" className="form-label">Marital Status</label>
               <input type="text" className="form-control" id="inputMarital" />
             </div>
             <hr />
             <div className="col-12">
               <label htmlFor="inputAddress" className="form-label">Address</label>
               <input type="text" className="form-control" id="inputAddress" placeholder="1234 Main St" />
             </div>
             
             <div className="col-md-6">
               <label htmlFor="inputDistrict" className="form-label">District</label>
               <input type="text" className="form-control" id="inputDistrict" />
             </div>
             <div className="col-md-6">
               <label htmlFor="inputBlock" className="form-label">Block</label>
               <input type="text" className="form-control" id="inputBlock" />
             </div>
             <div className="col-md-6">
               <label htmlFor="inputVill" className="form-label">Ward/Village</label>
               <input type="text" className="form-control" id="inputVill" />
             </div>
             <div className="col-md-6">
               <label htmlFor="inputZip" className="form-label">Pincode</label>
               <input type="text" className="form-control" id="inputZip" />
             </div>
             <div className="col-md-6">
               <label htmlFor="inputMobile" className="form-label">Mobile</label>
               <input type="text" className="form-control" id="inputMobile" />
             </div>
             <div className="col-md-6">
               <label htmlFor="inputEmail" className="form-label">Email</label>
               <input type="email" className="form-control" id="inputEmail" />
             </div>
             <hr />

             <div className="col-md-6">
               <label htmlFor="inputBenchmark" className="form-label">Benchmark Disability</label>
               <input type="text" className="form-control" id="inputBenchmark" />
             </div>
             <div className="col-md-6">
               <label htmlFor="inputCaste" className="form-label">Caste Category</label>
               <input type="text" className="form-control" id="inputCaste" />
             </div>
             <div className="col-md-6">
               <label htmlFor="inputHighest" className="form-label">Highest Qualification</label>
               <input type="text" className="form-control" id="inputHighest" />
             </div>
             <div className="col-md-6">
               <label htmlFor="inputEngagement" className="form-label">Current Engagement</label>
               <input type="text" className="form-control" id="inputEngagement" />
             </div>
             <div className="col-md-6">
               <label htmlFor="inputIncome" className="form-label">Total Annual Family Income</label>
               <input type="text" className="form-control" id="inputIncome" />
             </div>
             <div className="col-md-6">
               <label htmlFor="inputVerified" className="form-label">Income Verified</label>
               <input type="email" className="form-control" id="inputVerified" />
             </div>
             <div className="col-md-6">
               <label htmlFor="inputAlternateNo" className="form-label">Alternate Number</label>
               <input type="text" className="form-control" id="inputAlternateNo" />
             </div>
           
             <div className="col-md-6">
               <label htmlFor="inputAlternateEmail" className="form-label">Alternate Email</label>
               <input type="email" className="form-control" id="inputAlternateEmail" />
             </div>
             </form>
             <form onSubmit={handleSubmit(onSubmit)} className="needs-validation row g-3">
      <h3 className="text-center">Event</h3>

      <div className="col-md-6">
        <label className="form-label">Select Event</label>
        <select {...register('event_type')} className="form-select">
          <option value="">Choose...</option>
          <option value="1">Individual Event</option>
          <option value="2">Team Event</option>
        </select>
      </div>

      <div className="col-md-6">
        <label className="form-label">Aadhar No.</label>
        <input type="text" className="form-control" {...register('aadhar')} />
      </div>

      <div className="col-md-6">
        <label className="form-label">Select Tournament</label>
        <select {...register('tournament')} className="form-select">
          <option value="">Choose...</option>
          <option value="1">Tournament 1</option>
          <option value="2">Tournament 2</option>
        </select>
      </div>

      <div className="col-md-6">
        <label className="form-label">Haryana Resident/Domicile</label>
        <select {...register('domicile')} className="form-select">
          <option value="">Choose...</option>
          <option value="1">Yes</option>
          <option value="2">No</option>
        </select>
      </div>

      <div className="col-md-6">
        <label className="form-label">Attach Certificate (Domicile)</label>
        <input
          type="file"
          className="form-control"
          {...register('domicile_certificate')}
          disabled={domicile !== '1'}
        />
      </div>

      <div className="col-md-6">
        <label className="form-label">Played at National Level for Haryana</label>
        <select {...register('played_national')} className="form-select">
          <option value="">Choose...</option>
          <option value="1">Yes</option>
          <option value="2">No</option>
        </select>
      </div>

      <div className="col-md-6">
        <label className="form-label">Attach Certificate (National Level)</label>
        <input
          type="file"
          className="form-control"
          {...register('national_certificate')}
          disabled={playedNational !== '1'}
        />
      </div>

      <div className="col-md-6">
        <label className="form-label">Name of Central Organisation Represented</label>
        <input
          type="text"
          className="form-control"
          {...register('central_org_name')}
          disabled={playedNational !== '1'}
        />
      </div>

      <div className="col-md-6">
        <label className="form-label">Attach Certificate (Organisation Represented)</label>
        <input
          type="file"
          className="form-control"
          {...register('org_certificate')}
          disabled={playedNational !== '1'}
        />
      </div>

      <div className="col-12 text-center">
        <button type="submit" className="btn btn-primary">
          Submit
        </button>
      </div>
    </form>
              <div id="q-box__buttons">
                {currentStep > 1 && (
                  <button id="prev-btn"
                    type="button"
                    onClick={prevStep}
                    className="btn btn-primary me-2"
                  >
                    Previous
                  </button>
                )}

                {currentStep < stepsTotal && (
                  <button
                  id="next-btn"
                    type="button"
                    onClick={nextStep}
                    className="btn btn-primary me-2"
                  >
                    Next
                  </button>
                )}

                {currentStep === stepsTotal && (
                  <button
                  id="submit-btn"
                    type="button"
                    onClick={handleSubmit1}
                    className="btn btn-success"
                  >
                    Submit
                  </button>
                )}
              </div>
              </div>
            
          )}

          {isSubmitted && (
            <div className="mt-5">
              <h4>Success! We'll get back to you ASAP!</h4>
              <p>
                Stay safe: wash your hands, maintain distance, and wear a mask.
              </p>
              <Link to="/">Go back from the beginning ➜</Link>
            </div>
          )}
          </div>
        </div>
      </div>
    </div>
    l<div id="preloader-wrapper">
        <div id="preloader"></div>
        <div className="preloader-section section-left"></div>
        <div className="preloader-section section-right"></div>
    </div>
    </div>
  );
};

export default Dashboard;
