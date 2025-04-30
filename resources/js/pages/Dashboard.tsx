import React, { useEffect, useState } from "react";
import { createSearchParams, Link, useNavigate } from "react-router-dom";
import { useForm, useFormState } from "react-hook-form";
import { fetchEvent, saveEvent, updateEvent } from "../services/hosp-service";
const stepsTotal = 5;

const Dashboard = () => {
    const navigate = useNavigate();
    const userData = JSON.parse(localStorage.getItem("user")!);
    const userDetails = userData?.user_details || {};

    // Get user data from localStorage

    const logout = () => {
        localStorage.clear();
        navigate("/login");
    };
    const params = new URLSearchParams(window.location.search);
    const step: any = params.get("step") ? parseInt(params.get("step")!) : 1;
    // alert(step)
    const [currentStep, setCurrentStep] = useState(step);
    const [physicalDisability, setPhysicalDisability] = useState(false);
    const [representedIndia, setRepresentedIndia] = useState("1");
    const [tournamentLevel, setTournamentLevel] = useState("2");
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [isSubmitted, setIsSubmitted] = useState(false);

    const {
        register,
        handleSubmit,
        watch,
        setError,
        setValue,
        getValues,
        formState: { errors },
    } = useForm();

    useEffect(() => {
        const fetchEventData = async () => {
            try {
                const data = await fetchEvent(); // Replace with your endpoint
                // Option 1: Set fields one-by-one
                setValue("id", data.id);
                setValue("aadhaar", data.aadhaar);
                setValue("event_type", data.event_type);
                setValue("tournament", data.tournament_id?.toString());
                setValue("domicile", data.domicile?.toString());
                setValue(
                    "played_national",
                    data.played_national_level?.toString()
                );
                setValue("central_org_name", data.organisation_represented);
                setValue("domicile_doc", data.domicile_doc);
                setValue("national_level_doc", data.national_level_doc);
                setValue("organisation_doc", data.organisation_doc);

                // Option 2: Reset entire form (if keys match)
                // reset(data);
            } catch (error) {
                console.error("Error loading form data", error);
            }
        };

        fetchEventData();
    }, []);
    useEffect(() => {
        // const userData = JSON.parse(localStorage.getItem("user") || "null");
        if (!userData) {
            localStorage.clear();
            navigate("/login");
        }
    }, [navigate]);

    // handle back button
    useEffect(() => {
        const handlePopState = () => {
            // Reduce the step only if greater than 1
            setCurrentStep((prev) => (prev > 1 ? prev - 1 : 1));
            navigate({
                pathname: location.pathname, // or keep current path
                search: createSearchParams({
                    step: String(currentStep),
                }).toString(),
            });
        };

        window.addEventListener("popstate", handlePopState);

        return () => {
            window.removeEventListener("popstate", handlePopState);
        };
    }, []);
    // Watch individual fields for conditional logic
    const playedNational = watch("played_national");
    const domicile = watch("domicile");

    const onSubmit = (data) => {
        console.log("Form Data:", data);
        // Here you can handle the form submission (e.g. send to API)
    };
    const progressPercent = (100 / stepsTotal) * currentStep;

    const nextStep = () => {
        if (currentStep < stepsTotal) setCurrentStep((nxt) => nxt + 1);
    };

    const prevStep = () => {
        if (currentStep > 0) {
            setCurrentStep((prev) => prev - 1);
            navigate({
                pathname: location.pathname, // or keep current path
                search: createSearchParams({
                    step: String(currentStep - 1),
                }).toString(),
            });
        }
    };

    const handleSubmit1 = async () => {
        document.body.classList.add("loaded");
        setIsSubmitting(true);
        await new Promise((res) => setTimeout(res, 1000));
        setCurrentStep((nxt) => nxt + 1);
        document.body.classList.remove("loaded");
        setIsSubmitting(false);
        setIsSubmitted(true);
    };

    const [educationFields, setEducationFields] = useState([
        { qualification: "", certificate: null, otherText: "" },
    ]);
    const handleAddEducation = () => {
        setEducationFields([
            ...educationFields,
            { qualification: "", certificate: null, otherText: "" },
        ]);
    };

    const handleRemoveEducation = (indexToRemove) => {
        setEducationFields((prevFields) =>
            prevFields.filter((_, index) => index !== indexToRemove)
        );
    };

    const handleChange = (index, key, value) => {
        const updated = [...educationFields];
        updated[index][key] = value;
        setEducationFields(updated);
    };
    const handlePhysicalDisability = (value) => {
        setPhysicalDisability(value.checked);
    };
    const handleRepresentedIndia = (value) => {
        console.log("value", value);
        setRepresentedIndia(value);
    };
    const handleTournamentLevel = (value) => {
        console.log("value", value);
        setTournamentLevel(value);
    };

    const [declarations, setDeclarations] = useState({
        d1: false,
        d2: false,
        d3: false,
        d4: false,
        d5: false,
        d6: false,
        d7: false,
    });

    const [acceptAll, setAcceptAll] = useState(false);

    const handleSingleChange = (key: string) => {
        const updated = {
            ...declarations,
            [key]: !declarations[key as keyof typeof declarations],
        };
        setDeclarations(updated);

        // Check if all are now true
        const allChecked = Object.values(updated).every(Boolean);
        setAcceptAll(allChecked);
    };

    const handleAcceptAll = () => {
        const newValue = !acceptAll;
        const updatedDeclarations: any = Object.fromEntries(
            Object.keys(declarations).map((k) => [k, newValue])
        );
        setDeclarations(updatedDeclarations);
        setAcceptAll(newValue);
    };

    const handlePrint = () => {
        const content: any = document.getElementById("print-section");
        const printWindow: any = window.open("", "", "width=800,height=600");
        printWindow.document.write(`
          <html>
            <head>
              <title>Print</title>
              <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
              </style>
            </head>
            <body>
              ${content.innerHTML}
            </body>
          </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    };

    const onEventSubmit = async (data) => {
        const formData = new FormData();

        formData.append("id", data.id ?? null);
        formData.append("event_type", data.event_type);
        formData.append("aadhaar", data.aadhaar);
        formData.append("tournament", data.tournament);
        formData.append("domicile", data.domicile);
        formData.append("played_national", data.played_national);
        if (data.id) {
            formData.append(
                "domicile_certificate",
                data.domicile_doc ? data.domicile_doc : ""
            );
            formData.append(
                "national_certificate",
                data.national_level_doc ? data.national_level_doc : ""
            );
            formData.append(
                "org_certificate",
                data.organisation_doc ? data.organisation_doc : ""
            );
        }

        if (data.played_national == "2") {
            formData.delete("national_certificate");
        }
        if (data.domicile == "2") {
            formData.delete("domicile_certificate");
        }
        if (data.played_national == "1") {
            formData.delete("org_certificate");
        }

        if (data.domicile_certificate?.[0]) {
            formData.append(
                "domicile_certificate",
                data.domicile_certificate[0]
            );
        }

        if (data.national_certificate?.[0]) {
            formData.append(
                "national_certificate",
                data.national_certificate[0]
            );
        }

        if (playedNational === "2") {
            formData.append(
                "central_org_name",
                data.central_org_name ? data.central_org_name : ""
            );
            if (data.org_certificate?.[0]) {
                formData.append("org_certificate", data.org_certificate[0]);
            }
        }

        try {
            const token = localStorage.getItem("token");
            if (data.id) {
                const response = await updateEvent(formData, data.id);
            } else {
                const response = await saveEvent(formData);
            }

            if (currentStep < stepsTotal) setCurrentStep((nxt) => nxt + 1);
            // ✅ Update URL with query param ?step=1
            navigate({
                pathname: location.pathname, // or keep current path
                search: createSearchParams({
                    step: currentStep + 1,
                }).toString(),
            });
            console.log("Event submitted:", response.data);
            // Move to next step or show success
        } catch (error) {
            console.error("Event submission failed", error);
            if (error.response?.status === 422) {
                const backendErrors = error.response.data.errors;
                Object.keys(backendErrors).forEach((field) => {
                    setError(field, {
                        type: "server",
                        message: backendErrors[field][0],
                    });
                });
            }
        }
    };

    const onEducationSubmit = async (data) => {
        if (currentStep < stepsTotal) setCurrentStep((nxt) => nxt + 1);
        navigate({
            pathname: location.pathname, // or keep current path
            search: createSearchParams({ step: currentStep + 1 }).toString(),
        });
    };

    return (
        <div>
            <header className="hero-section">
                <h1>Sports Department, Government of Haryana</h1>
                <p>Let the young minds grow to the full potential</p>

                <div className="hero-wave">
                    <svg viewBox="0 0 500 150" preserveAspectRatio="none">
                        <path
                            d="M0.00,49.98 C157.87,179.29 349.61,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z"
                            style={{ stroke: "none", fill: "#f0f0f0" }}
                        ></path>
                    </svg>
                </div>
            </header>
            <div className="container form-container">
                {/* <div className="col-lg-12 offset-lg-1 mx-0 px-0">
                        <div id="title-container">
                           
                            <h2>Haryana Outstanding Sports Persons</h2>
                            <h3>Recruitment Form</h3>
                            <p>
                                Appointed to the Haryana Outstanding Sports
                                Service (Group A, B, and C)
                            </p>
                            <div className="float-end m-2">
                                <button
                                    className="btn btn-danger me-1"
                                    onClick={logout}
                                >
                                    Logout <i className="fa fa-sign-out"></i>
                                </button>
                            </div>
                        </div>
                    </div> */}

                <div className="progress">
                    <div
                        className="progress-bar progress-bar-striped progress-bar-animated bg-success"
                        role="progressbar"
                        style={{ width: `${progressPercent}%` }}
                    ></div>
                </div>

                {isSubmitting && (
                    // <div className="d-block text-center mt-5">Submitting...</div>
                    <div id="preloader-wrapper">
                        <div id="preloader"></div>
                        <div className="preloader-section section-left"></div>
                        <div className="preloader-section section-right"></div>
                    </div>
                )}
                <div className="float-end m-2" hidden={currentStep !== 7}>
                    <button
                        className="btn btn-primary me-1"
                        onClick={handlePrint}
                    >
                        Print <i className="fa fa-print"></i>
                    </button>
                </div>
                {!isSubmitted && !isSubmitting && (
                    <div>
                        <form
                            onSubmit={handleSubmit(onEventSubmit)}
                            className="needs-validation row g-3"
                            hidden={currentStep === 1 ? false : true}
                        >
                            <div className="row g-3">
                                <h3 className="text-center">Event</h3>

                                <div className="col-md-6">
                                    <label>
                                        Select Event
                                    </label>
                                    <select
                                        {...register("event_type")}
                                        className={`form-select ${
                                            errors.event_type
                                                ? "is-invalid"
                                                : ""
                                        }`}
                                    >
                                        <option value="" disabled selected>
                                            Select
                                        </option>
                                        <option value="1">
                                            Individual Event
                                        </option>
                                        <option value="2">Team Event</option>
                                    </select>
                                    {errors.domicile && (
                                        <div className="invalid-feedback">
                                            {
                                                errors.event_type
                                                    .message as string
                                            }
                                        </div>
                                    )}
                                </div>
                                <div className="col-md-6"></div>

                                <div className="col-md-6">
                                    <label>
                                        Aadhaar No.
                                    </label>
                                    <input
                                        type="text"
                                        className={`form-control ${
                                            errors.aadhaar ? "is-invalid" : ""
                                        }`}
                                        {...register("aadhaar")}
                                        maxLength={12}
                                    />
                                    {errors.aadhaar && (
                                        <div className="invalid-feedback">
                                            {errors.aadhaar.message as string}
                                        </div>
                                    )}
                                </div>

                                <div className="col-md-6">
                                    <label>
                                        Select Tournament
                                    </label>
                                    <select
                                        {...register("tournament")}
                                        className={`form-select ${
                                            errors.tournament
                                                ? "is-invalid"
                                                : ""
                                        }`}
                                    >
                                        <option value="" disabled selected>
                                            Select
                                        </option>
                                        <option value="1">Olympic Games</option>
                                        <option value="2">Paralympics</option>
                                        <option value="3">Asian Games</option>
                                        <option value="4">
                                            4-years World Cup/Championship
                                        </option>
                                        <option value="5">
                                            World Cup/Championship
                                        </option>
                                    </select>
                                    {errors.tournament && (
                                        <div className="invalid-feedback">
                                            {
                                                errors.tournament
                                                    .message as string
                                            }
                                        </div>
                                    )}
                                </div>

                                <div className="col-md-6">
                                    <label>
                                        Haryana Resident/Domicile
                                    </label>
                                    <select
                                        {...register("domicile")}
                                        className={`form-select ${
                                            errors.domicile ? "is-invalid" : ""
                                        }`}
                                    >
                                        <option value="" disabled selected>
                                            Select
                                        </option>
                                        <option value="1">Yes</option>
                                        <option value="2">No</option>
                                    </select>
                                    {errors.domicile && (
                                        <div className="invalid-feedback">
                                            {errors.domicile.message as string}
                                        </div>
                                    )}
                                </div>

                                <div className="col-md-6">
                                    <label>
                                        Attach Certificate (Domicile)
                                    </label>
                                    <input
                                        type="file"
                                        className={`form-control ${
                                            errors.domicile_certificate
                                                ? "is-invalid"
                                                : ""
                                        }`}
                                        {...register("domicile_certificate")}
                                        disabled={domicile !== "1"}
                                    />
                                    {getValues("domicile") == "1" &&
                                        getValues("domicile_doc") && (
                                            <div className="mt-1">
                                                <a
                                                    href={`/api/${encodeURIComponent(
                                                        getValues(
                                                            "domicile_doc"
                                                        )
                                                    )}`}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                >
                                                    Click here to view uploaded
                                                    file
                                                </a>
                                            </div>
                                        )}
                                    {errors.domicile_certificate && (
                                        <div className="invalid-feedback">
                                            {
                                                errors.domicile_certificate
                                                    .message as string
                                            }
                                        </div>
                                    )}
                                </div>

                                <div className="col-md-6">
                                    <label>
                                        Played at National Level for Haryana
                                    </label>
                                    <select
                                        {...register("played_national")}
                                        className={`form-select ${
                                            errors.played_national
                                                ? "is-invalid"
                                                : ""
                                        }`}
                                    >
                                        <option value="" disabled selected>
                                            Select
                                        </option>
                                        <option value="1">Yes</option>
                                        <option value="2">No</option>
                                    </select>
                                    {errors.played_national && (
                                        <div className="invalid-feedback">
                                            {
                                                errors.played_national
                                                    .message as string
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
                                        className={`form-control ${
                                            errors.national_certificate
                                                ? "is-invalid"
                                                : ""
                                        }`}
                                        {...register("national_certificate")}
                                        disabled={playedNational !== "1"}
                                    />
                                    {getValues("played_national") == "1" &&
                                        getValues("national_level_doc") && (
                                            <div className="mt-1">
                                                <a
                                                    href={`/api/${encodeURIComponent(
                                                        getValues(
                                                            "national_level_doc"
                                                        )
                                                    )}`}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                >
                                                    Click here to view uploaded
                                                    file
                                                </a>
                                            </div>
                                        )}
                                    {errors.national_certificate && (
                                        <div className="invalid-feedback">
                                            {
                                                errors.national_certificate
                                                    .message as string
                                            }
                                        </div>
                                    )}
                                </div>

                                <div className="col-md-6">
                                    <label>
                                        Name of Central Organisation Represented
                                    </label>
                                    <input
                                        type="text"
                                        className={`form-control ${
                                            errors.central_org_name
                                                ? "is-invalid"
                                                : ""
                                        }`}
                                        {...register("central_org_name")}
                                        disabled={playedNational !== "2"}
                                    />
                                    {errors.central_org_name && (
                                        <div className="invalid-feedback">
                                            {
                                                errors.central_org_name
                                                    .message as string
                                            }
                                        </div>
                                    )}
                                </div>

                                <div className="col-md-6">
                                    <label>
                                        Attach Certificate (Organisation
                                        Represented)
                                    </label>
                                    <input
                                        type="file"
                                        className={`form-control ${
                                            errors.org_certificate
                                                ? "is-invalid"
                                                : ""
                                        }`}
                                        {...register("org_certificate")}
                                        disabled={playedNational !== "2"}
                                    />
                                    {getValues("played_national") == "2" &&
                                        getValues("organisation_doc") && (
                                            <div className="mt-1">
                                                <a
                                                    href={`/api/${encodeURIComponent(
                                                        getValues(
                                                            "organisation_doc"
                                                        )
                                                    )}`}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                >
                                                    Click here to view uploaded
                                                    file
                                                </a>
                                            </div>
                                        )}
                                    {errors.org_certificate && (
                                        <div className="invalid-feedback">
                                            {
                                                errors.org_certificate
                                                    .message as string
                                            }
                                        </div>
                                    )}
                                </div>

                                <div className="col-12 text-center">
                                    <button
                                        id="next-btn"
                                        type="submit"
                                        className="btn btn-primary me-2"
                                    >
                                        Next
                                    </button>
                                </div>
                            </div>
                        </form>
                        <form
                            className="needs-validation row g-3"
                            hidden={currentStep !== 2}
                            onSubmit={handleSubmit(onEducationSubmit)}
                        >
                            <h3 className="text-center mt-5">
                                Educational Qualification
                            </h3>

                            {educationFields.map((field, index) => (
                                <div
                                    key={index}
                                    className="row  g-3"
                                >
                                    <div className="col-md-6">
                                        <label>
                                            Select Qualification
                                        </label>
                                        <select
                                            className="form-select"
                                            value={field.qualification}
                                            onChange={(e) =>
                                                handleChange(
                                                    index,
                                                    "qualification",
                                                    e.target.value
                                                )
                                            }
                                        >
                                            <option value="">Select</option>
                                            <option value="12">12th</option>
                                            <option value="graduation">
                                                Graduation
                                            </option>
                                            <option value="postgraduation">
                                                Post Graduation
                                            </option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>

                                    {field.qualification === "other" && (
                                        <div className="col-md-6">
                                            <label>
                                                Specify Other Qualification
                                            </label>
                                            <input
                                                type="text"
                                                className="form-control"
                                                value={field.otherText}
                                                onChange={(e) =>
                                                    handleChange(
                                                        index,
                                                        "otherText",
                                                        e.target.value
                                                    )
                                                }
                                            />
                                        </div>
                                    )}

                                    <div className="col-md-6">
                                        <label>
                                            Attach Certificate (pdf)
                                        </label>
                                        <input
                                            type="file"
                                            accept="application/pdf"
                                            className="form-control"
                                            onChange={(e) =>
                                                handleChange(
                                                    index,
                                                    "certificate",
                                                    e.target.files[0]
                                                )
                                            }
                                        />
                                    </div>

                                    <div className="col-md-2">
                                        {educationFields.length > 1 && (
                                            <button
                                                type="button"
                                                className="btn btn-outline-danger"
                                                onClick={() =>
                                                    handleRemoveEducation(index)
                                                }
                                            >
                                                Remove
                                            </button>
                                        )}
                                    </div>
                                </div>
                            ))}

                            <div className="col-12 text-end">
                                <button
                                    type="button"
                                    className="btn btn-outline-primary"
                                    onClick={handleAddEducation}
                                >
                                    + Add Education
                                </button>
                            </div>
                            <div id="text-center mt-4">
                                {currentStep > 1 && (
                                    <button
                                       
                                        type="button"
                                        onClick={prevStep}
                                        className="save-btn m-2"
                                    >
                                        Previous
                                    </button>
                                )}

                                <button
                                  
                                    type="submit"
                                    className="save-btn"
                                >
                                    Next
                                </button>
                            </div>
                        </form>
                        <form
                            className="needs-validation row g-3"
                            hidden={currentStep === 3 ? false : true}
                        >
                            <h3 className="text-center">Sports Discipline</h3>
                            <div className="col-md-12">
                                <div className="form-check">
                                    <input
                                        className="form-check-input"
                                        type="checkbox"
                                        name="medalWon"
                                        id="medalGold"
                                        value=""
                                        onChange={(e) =>
                                            handlePhysicalDisability(e.target)
                                        }
                                    />
                                    <label
                                        className="form-check-label"
                                        htmlFor="medalGold"
                                    >
                                        Physical Disability{" "}
                                    </label>
                                </div>
                            </div>
                            <div className="col-md-6">
                                <label>
                                    Select Disability type
                                </label>
                                <select
                                    className="form-select"
                                    disabled={!physicalDisability}
                                >
                                    <option value="" disabled selected>
                                        Select
                                    </option>
                                    <option value="1">Para</option>
                                    <option value="2">Blind</option>
                                    <option value="3">Deaf</option>
                                    <option value="4">
                                        Special Olympic Sports
                                    </option>
                                </select>
                            </div>
                            <div className="col-md-6">
                                <label>
                                    Attach Certificate
                                </label>
                                <input
                                    type="file"
                                    className="form-control"
                                    disabled={!physicalDisability}
                                />
                            </div>
                            <div className="col-md-6">
                                <label>
                                    Select Tournament
                                </label>
                                <select className="form-select">
                                    <option value="" disabled selected>
                                        Select
                                    </option>
                                    <option value="1">Olympic Games</option>
                                    <option value="2">Paralympics</option>
                                    <option value="3">Asian Games</option>
                                    <option value="4">
                                        4-years World Cup/Championship
                                    </option>
                                    <option value="5">
                                        World Cup/Championship
                                    </option>
                                </select>
                            </div>
                            <div className="col-md-6">
                                <label>
                                    Organizing Committee
                                </label>
                                <input type="text" className="form-control" />
                            </div>
                            <div className="col-md-6">
                                <label>
                                    Level of the Tournament
                                </label>
                                <select
                                    className="form-select"
                                    onChange={(e) =>
                                        handleTournamentLevel(e.target.value)
                                    }
                                >
                                    <option value="" disabled selected>
                                        Select
                                    </option>
                                    <option value="1">National</option>
                                    <option value="2">International</option>
                                </select>
                            </div>
                            <div className="col-md-6">
                                <label>
                                    Represented India in any sports tournament
                                    (Schedule-I and Schedule-II)
                                </label>
                                <select
                                    className="form-select"
                                    onChange={(e) =>
                                        handleRepresentedIndia(e.target.value)
                                    }
                                    disabled={tournamentLevel == "2"}
                                >
                                    <option value="" disabled selected>
                                        Select
                                    </option>
                                    <option value="1">Yes</option>
                                    <option value="2">No</option>
                                </select>
                                <div
                                    className="text-danger"
                                    hidden={representedIndia != "2"}
                                >
                                    You are Ineligibile
                                </div>
                            </div>
                            <div className="col-md-6">
                                <label>
                                    Select Game
                                </label>
                                <select
                                    className="form-select"
                                    onChange={(e) =>
                                        handleTournamentLevel(e.target.value)
                                    }
                                >
                                    <option value="" disabled selected>
                                        Select
                                    </option>
                                    <option value="1">Chess</option>
                                    <option value="2">Athletics</option>
                                    <option value="3">Archery</option>{" "}
                                    <option value="3">Badminton</option>{" "}
                                    <option value="3">Basketball</option>
                                    <option value="3">Boxing</option>
                                    <option value="3">Cycling</option>
                                </select>
                            </div>
                            <div className="col-md-6">
                                <label>
                                    Attach Certificate (pdf)
                                </label>
                                <input
                                    type="file"
                                    className="form-control"
                                    disabled={representedIndia != "1"}
                                />
                            </div>
                            <div className="col-md-6">
                                <label>
                                    Achievement date
                                </label>
                                <input type="date" className="form-control" />
                            </div>
                            <div className="col-md-6">
                                <label>
                                    Tournament Venue
                                </label>
                                <input type="text" className="form-control" />
                            </div>
                            <div className="col-xs-12 col-sm-6 col-md-4">
                                <p>Medal won(if any)</p>
                                <div className="form-check form-check-inline">
                                    <input
                                        className="form-check-input"
                                        type="radio"
                                        name="medalWon"
                                        id="medalGold"
                                        value="medalGold"
                                    />
                                    <label
                                        className="form-check-label"
                                        htmlFor="medalGold"
                                    >
                                        Gold
                                    </label>
                                </div>
                                <div className="form-check form-check-inline">
                                    <input
                                        className="form-check-input"
                                        type="radio"
                                        name="medalWon"
                                        id="medalSilver"
                                        value="medalSilver"
                                    />
                                    <label
                                        className="form-check-label"
                                        htmlFor="medalSilver"
                                    >
                                        Silver
                                    </label>
                                </div>
                                <div className="form-check form-check-inline">
                                    <input
                                        className="form-check-input"
                                        type="radio"
                                        name="medalWon"
                                        id="medalBronze"
                                        value="medalBronze"
                                    />
                                    <label
                                        className="form-check-label"
                                        htmlFor="medalBronze"
                                    >
                                        Bronze
                                    </label>
                                </div>
                            </div>
                            <div className="col-xs-12 col-sm-6 col-md-4">
                                <p>
                                    Participation Level (in case of team game
                                    only)
                                </p>
                                <div className="form-check form-check-inline">
                                    <input
                                        className="form-check-input"
                                        type="radio"
                                        name="inlineRadioOptions"
                                        id="inlineRadio1"
                                        value="option1"
                                    />
                                    <label
                                        className="form-check-label"
                                        htmlFor="inlineRadio1"
                                    >
                                        25% or more{" "}
                                    </label>
                                </div>
                                <div className="form-check form-check-inline">
                                    <input
                                        className="form-check-input"
                                        type="radio"
                                        name="inlineRadioOptions"
                                        id="inlineRadio2"
                                        value="option2"
                                    />
                                    <label
                                        className="form-check-label"
                                        htmlFor="inlineRadio2"
                                    >
                                        Less then 25%
                                    </label>
                                </div>
                            </div>
                        </form>
                        <form
                            className="needs-validation row g-3"
                            hidden={currentStep === 4 ? false : true}
                        >
                            <h3 className="text-center">Personal</h3>
                            <div className="col-md-6">
                                <label>
                                    Upload Profile Pic
                                </label>
                                <input type="file" className="form-control" />
                            </div>
                            <div className="col-md-6">
                                <label>
                                    Upload sign
                                </label>
                                <input type="file" className="form-control" />
                            </div>
                        </form>

                        <form
                            className="needs-validation row g-3"
                            hidden={currentStep === 5 ? false : true}
                        >
                            <h3 className="text-center">Declaration</h3>
                            <div className="">
                                {/* <p className="text-center mb-4">Declaration</p> */}
                                <div className="row">
                                    <div className="col-xs-12 col-sm-12 col-md-12 mb-3">
                                        <p>Declaration by Sportsperson</p>
                                        {[
                                            "1. I have read the Haryana Outstanding Sportspersons (Recruitment and Condition of Service) Rules, 2021 and declare that I am eligible for submission of my application for consideration of appointment under these Rules.",
                                            "2. I have enclosed self-attested copies of all documents in support of my application.",
                                            "3. I have played in 50% or more of the games played by team in the tournament at serial No.12 above.",
                                            "4. I did not represent a State/UT other than Haryana at the national level.",
                                            "5. I am not guilty of doping, sexual harassment and abuse, competitive manipulation like betting, inside information, match fixing, tanking, threatening the integrity and essence of sports.",
                                            "6. If appointment is offered, I undertake that I shall have no subsisting contract for pecuniary gains like commercial endorsement or professional sport before joining the service.",
                                            "7. I forego my earlier claim made under the Haryana Outstanding Sportsperson.",
                                        ].map((label, index) => (
                                            <div
                                                className="form-check"
                                                key={`d${index + 1}`}
                                            >
                                                <input
                                                    className="form-check-input"
                                                    type="checkbox"
                                                    id={`d${index + 1}`}
                                                    checked={
                                                        declarations[
                                                            `d${
                                                                index + 1
                                                            }` as keyof typeof declarations
                                                        ]
                                                    }
                                                    onChange={() =>
                                                        handleSingleChange(
                                                            `d${index + 1}`
                                                        )
                                                    }
                                                />
                                                <label
                                                    className="form-check-label"
                                                    htmlFor={`d${index + 1}`}
                                                >
                                                    {label}
                                                </label>
                                            </div>
                                        ))}

                                        <div className="form-check mt-3">
                                            <input
                                                className="form-check-input"
                                                type="checkbox"
                                                id="acceptAll"
                                                checked={acceptAll}
                                                onChange={handleAcceptAll}
                                            />
                                            <label
                                                className="form-check-label"
                                                htmlFor="acceptAll"
                                            >
                                                Accept all
                                            </label>
                                        </div>
                                    </div>

                                    <div className="col-md-6">
                                        <label>
                                            Upload Declaration
                                        </label>
                                        <input
                                            type="file"
                                            className="form-control"
                                        />
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div id="q-box__buttons">
                            {/* {currentStep > 1 && (
                                            <button
                                                id="prev-btn"
                                                type="button"
                                                onClick={prevStep}
                                                className="btn btn-primary me-2"
                                            >
                                                Previous
                                            </button>
                                        )} */}

                            {/* {currentStep < stepsTotal && (
                                            <button
                                                id="next-btn"
                                                type="button"
                                                onClick={nextStep}
                                                className="btn btn-primary me-2"
                                            >
                                                Next
                                            </button>
                                        )} */}

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
                    <div
                        className="text-center mt-5 p-4 border rounded shadow-sm bg-light"
                        id="print-section"
                    >
                        <h4>Success! We'll get back to you ASAP!</h4>
                        <p>Application ID: HOSP654321</p>
                        <Link to="/hosp/login">
                            Go back from the beginning ➜
                        </Link>
                    </div>
                )}
            </div>

            <div id="preloader-wrapper">
                <div id="preloader"></div>
                <div className="preloader-section section-left"></div>
                <div className="preloader-section section-right"></div>
            </div>
        </div>
    );
};

export default Dashboard;
