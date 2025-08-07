import React, { useEffect, useRef, useState } from "react";
import { Link, useNavigate, useParams } from "react-router-dom";
import html2pdf from "html2pdf.js";
import { fetchApplicationDetails, fetchApplicationPreviewDetails, fetchDeclarationsList, fetchUserDetails } from "../services/hosp-service";
import { toast } from 'react-toastify';
interface OtpData {
    certificate_no: string;
    sports_person_name: string;
    aadhaar_no: string;
    mobile_no: string;
    district_sportsperson_belongs: string;
    profile_picture?: string;
    domicile_state: string;
    plays_for_statte_org: string;
    name_sports_discipline: string;
    type_of_event: string;
    organising_authority: string;
    venue_of_tournament: string;
    month_year: string;
    tournament_type: string;
    medal_won: string;
    participation_level: string;
    aadhaar_card?: string;
    domicile_certificate?: string;
    sports_certificate?: string;
    more_than25_photo?: string;
    created_at: string;
}

interface CertificatePDFProps {
    otpData: OtpData;
}
const ApplicationPreview = () => {
    const defaultOtpData: OtpData = {
        certificate_no: "",
        sports_person_name: "",
        aadhaar_no: "",
        mobile_no: "",
        district_sportsperson_belongs: "",
        profile_picture: "",
        domicile_state: "",
        plays_for_statte_org: "",
        name_sports_discipline: "",
        type_of_event: "",
        organising_authority: "",
        venue_of_tournament: "",
        month_year: "",
        tournament_type: "",
        medal_won: "",
        participation_level: "",
        aadhaar_card: "",
        domicile_certificate: "",
        sports_certificate: "",
        more_than25_photo: "",
        created_at: "",
    };
    const { application_id } = useParams();
    const otpData: OtpData = defaultOtpData;
    const navigate = useNavigate();
    let userData = JSON.parse(localStorage.getItem("user")!);
    let userDetails = userData?.user_details || {};
    let loginType = JSON.parse(localStorage.getItem("loginType")!);
    const [previewDetails, setPreviewDetails] = useState<any>({});
    const defaultDeclarations = [
        "1. I have read the Haryana Outstanding Sportspersons (Recruitment and Condition of Service) Rules, 2021 and declare that I am eligible for submission of my application for consideration of appointment under these Rules.",
        "2. I have enclosed self-attested copies of all documents in support of my application.",
        "3. I have played in 50% or more of the games played by team in the tournament.",
        "4. I did not represent a State/UT other than Haryana at the national level.",
        "5. I am not guilty of doping, sexual harassment and abuse, competitive manipulation like betting, inside information, match fixing, tanking, threatening the integrity and essence of sports.",
        "6. If appointment is offered, I undertake that I shall have no subsisting contract for pecuniary gains like commercial endorsement or professional sport before joining the service.",
        "7. I forego my earlier claim made under the Haryana Outstanding Sportsperson.",
    ];
    const [declarationList, setDeclarationList] =
            useState<string[]>(defaultDeclarations);
    const certificateRef = useRef<HTMLDivElement>(null);
    const downloadPDF = () => {
        const element = certificateRef.current;
        if (!element) return;

        element.style.display = "block";

        const opt = {
            margin: 0.1,
            filename: "sports-certificate.pdf",
            image: { type: "jpeg", quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: "in", format: "a4", orientation: "portrait" },
        };

        html2pdf()
            .set(opt)
            .from(element)
            .save()
            .then(() => {
                // element.style.display = 'none';
            });
    };
     useEffect(() => {
            const fetchApplicationDetails = async () => {
                try {
                    const data = await fetchApplicationPreviewDetails(application_id);
                    console.log('data',data);
                    setPreviewDetails(data.user);
                    // localStorage.setItem("user", JSON.stringify(data.user));
                    // userData = JSON.parse(localStorage.getItem("user")!);
                    // userDetails = userData?.user_details || {};
                    // if(userData&& !userData.declarations_hosp) {
                    //     toast.error('Please fill all required fields')
                    //     navigate("/basic-details");
                    // }
                } catch (error) {
                    console.error("Error loading form data", error);
                }
            };
            const fetchDeclarations = async () => {
                try {
                    const data = await fetchDeclarationsList();
                    console.log('data',data.declarations);
                    setDeclarationList(data.declarations);
                    // localStorage.setItem("user", JSON.stringify(data.user));
                    // userData = JSON.parse(localStorage.getItem("user")!);
                    // userDetails = userData?.user_details || {};
                    // if(userData&& !userData.declarations_hosp) {
                    //     toast.error('Please fill all required fields')
                    //     navigate("/basic-details");
                    // }
                } catch (error) {
                    console.error("Error loading form data", error);
                }
            };
            fetchApplicationDetails();
            fetchDeclarations();
        }, []); // <-- empty array ensures this only runs once
   useEffect(() => {
        // const userData = JSON.parse(localStorage.getItem("user") || "null");
        if (!userData || !loginType || loginType != '3') {
            localStorage.clear();
            navigate("/");
        }
    }, [navigate]);
    return (
        <div className="preview-form-page">
            <div ref={certificateRef}>
                <div className="certificate-card ">
                    <table width="100%">
                        <thead style={{ background: "#4831d4", color: "#fff" }}>
                            <tr>
                                <th
                                    style={{
                                        padding: "10px",
                                        display: "flex",
                                        justifyContent: "space-between",
                                        alignItems: "center",
                                    }}
                                >
                                    <div
                                        style={{
                                            fontSize: "14px",
                                            fontWeight: "normal",
                                            display: "flex",justifyContent:"start",
                                            alignItems: "center",width: "50%"
                                        }}
                                    >
                                        <img
                                            src="/assets/job_app/dash/images/logo-sports.png"
                                            alt="Sports Haryana Govt"
                                            style={{ height: "80px" }}
                                        />{" "}
                                        <h3 style={{ marginLeft: "10px",
                                                fontSize: "22px", }}>
                                            Sports Gradation Certificate
                                            <small
                                                style={{
                                                    fontSize: "13px",
                                                    fontWeight: "normal",
                                                    float: "left",
                                                    width: "100%",
                                                }}
                                            >
                                                Let the young minds grow to the
                                                full potential
                                            </small>
                                        </h3>
                                    </div>
                                    <div style={{width: "40%", }}>
                                        <p
                                            style={{
                                                fontSize: "18px",
                                                margin: "0 0 5px",
                                                color: "#fff",
                                                fontWeight: "normal",textAlign:"right"
                                            }}
                                        >
                                            Application ID -
                                            {previewDetails?.application_details?.application_id}
                                            <strong
                                                style={{
                                                    borderBottom: "1px dotted",
                                                }}
                                            >
                                                {otpData.certificate_no}
                                            </strong>
                                        </p>
                                        <button
                                            className="btn btn-success me-1 download-PDF-btn float-end"
                                            onClick={downloadPDF}
                                        >
                                            Download Form
                                        </button>
                                        <Link
                                            className="btn btn-primary me-1 download-PDF-btn float-end"
                                           to="/hosp/dashboard"
                                        >
                                            Dashboard
                                        </Link> 
                                       
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <table
                                        width="100%"
                                        style={{
                                            margin: "0 auto",
                                            boxShadow:
                                                "0 0 10px rgba(0,0,0,0.07)",
                                        }}
                                    >
                                        <tbody>
                                            <tr>
                                                <td style={{}} colSpan={4}>
                                                    <h3 className="modal-title-details">
                                                        <i className="fa-solid fa-user"></i>{" "}
                                                        Basic Details
                                                    </h3>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colSpan={3}>
                                                    <table width="100%">
                                                        <tbody>
                                                    <tr>
                                                        <td style={{ padding: "10px" }}>
                                                            <small
                                                                style={{
                                                                    fontSize: "16px",
                                                                }}
                                                            >
                                                                1. Name
                                                            </small>
                                                            <h5 style={{ fontSize: "15px", }}>
                                                                {
                                                                    previewDetails?.application_details?.full_name_en
                                                                }
                                                            </h5>
                                                        </td>
                                                        <td style={{ padding: "10px" }}>
                                                            <small
                                                                style={{
                                                                    fontSize: "16px",
                                                                }}
                                                            >
                                                                2. Aadhar No.
                                                            </small>
                                                            <h5 style={{ fontSize: "15px", }}>
                                                                { previewDetails?.application_details?.aadhaar}
                                                            </h5>
                                                        </td>
                                                        <td style={{ padding: "10px" }}>
                                                            <small
                                                                style={{
                                                                    fontSize: "16px",
                                                                }}
                                                            >
                                                                3. Mobile No.
                                                            </small>
                                                            <h5 style={{ fontSize: "15px", }}>{ previewDetails?.mobile}</h5>
                                                        </td>
                                                    
                                                    </tr>
                                                    <tr>
                                                        <td style={{ padding: "10px" }}>
                                                            <small
                                                                style={{
                                                                    fontSize: "16px",
                                                                }}
                                                            >
                                                                4. Date Of birth
                                                            </small>
                                                            <h5 style={{ fontSize: "15px", }}>
                                                                {
                                                                     previewDetails?.application_details?.date_of_birth
                                                                }
                                                            </h5>
                                                        </td>
                                                        <td style={{ padding: "10px" }}>
                                                            <small
                                                                style={{
                                                                    fontSize: "16px",
                                                                }}
                                                            >
                                                                5. Caste Category
                                                            </small>
                                                            <h5 style={{ fontSize: "15px", }}>
                                                                {
                                                                     previewDetails?.application_details?.caste_category
                                                                }
                                                            </h5>
                                                        </td>
                                                        <td style={{ padding: "10px" }}>
                                                            <small
                                                                style={{
                                                                    fontSize: "16px",
                                                                }}
                                                            >
                                                                6. Haryana Domicle
                                                            </small>
                                                            <h5 style={{ fontSize: "15px", }}>
                                                                { previewDetails?.application_details?.domicile ==
                                                                "1"
                                                                    ? "Yes"
                                                                    : "No"}
                                                            </h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style={{ padding: "10px" }}>
                                                            <small
                                                                style={{
                                                                    fontSize: "16px",
                                                                }}
                                                            >
                                                                7. Event type
                                                            </small>
                                                            <h5 style={{ fontSize: "15px", }}>
                                                                { previewDetails?.application_details?.sports_discipline_hosp?.event_type == "1"
                                                                ? "Individual"
                                                                : "Team"}
                                                                {/* Individual */}
                                                            </h5>
                                                        </td>
                                                        <td style={{ padding: "10px" }}>
                                                            <small
                                                                style={{
                                                                    fontSize: "16px",
                                                                }}
                                                            >
                                                                8. Played National Level
                                                            </small>
                                                            <h5 style={{ fontSize: "15px", }}>
                                                                { previewDetails?.application_details?.played_national_level ==
                                                            "1"
                                                                ? "Yes"
                                                                : "No"}
                                                                {/* yes */}
                                                            </h5>
                                                        </td>
                                                        <td
                                                            style={{ padding: "10px" }}
                                                        >
                                                            <small
                                                                style={{
                                                                    fontSize: "16px",
                                                                }}
                                                            >
                                                                9. Name of Central
                                                                Organisation Represented
                                                            </small>
                                                            <h5 style={{ fontSize: "15px", }}>
                                                                {
                                                                 previewDetails?.application_details?.organisation_represented ?? 'N/A'
                                                            }
                                                                {/* 'N/A' */}
                                                            </h5>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                    </table>

                                                </td>
                                                <td className="profile-pic-area" 
                                                    style={{ padding: "10px" }}
                                                >
                                                    <img
                                                        src={
                                                            previewDetails?.application_details?.photo
                                                                ? `/storage/photo/${ previewDetails?.application_details?.photo}`
                                                                : "default.jpg"
                                                        }
                                                        width="100%"
                                                        height="100%"
                                                        style={{
                                                            border: "5px solid #eee",
                                                        }}
                                                        alt="Profile"
                                                    />
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td
                                                        colSpan={4}
                                                    style={{
                                                        paddingTop: "20px",
                                                    }}
                                                >
                                                    <h3 className="modal-title-details">
                                                        <i className="fa-solid fa-user-graduate"></i>{" "}
                                                        Educational
                                                        Qualifications
                                                    </h3>
                                                </td>
                                            </tr>
                                            { previewDetails?.application_details?.education_hosp?.map(
                                                (item, index) => (
                                                    <tr key={item.id || index}>
                                                        <td colSpan={4}>
                                                            <table
                                                                width="100%"
                                                                style={{
                                                                    background:
                                                                        "#fff",
                                                                    tableLayout:
                                                                        "fixed",
                                                                }}
                                                            >
                                                                <tbody>
                                                                <tr>
                                                                    {!item.other_qualification && (
                                                                        <>
                                                                            <td
                                                                                style={{
                                                                                    border: "1px solid #efefef",
                                                                                    padding:
                                                                                        "3px 7px",
                                                                                    fontSize:
                                                                                        "16px",
                                                                                }}
                                                                            >
                                                                                {
                                                                                    item.qualification
                                                                                }
                                                                            </td>
                                                                            <td
                                                                                style={{
                                                                                    border: "1px solid #efefef",
                                                                                    padding:
                                                                                        "3px 7px",
                                                                                    fontSize:
                                                                                        "16px",
                                                                                }}
                                                                            >
                                                                                {item.certificate_path
                                                                                    ? "Attached doc"
                                                                                    : "No Attachment"}{" "}
                                                                                <i className="fa-solid fa-paperclip"></i>
                                                                            </td>
                                                                        </>
                                                                    )}
                                                                    {item.other_qualification && (
                                                                        <>
                                                                            <td
                                                                                style={{
                                                                                    border: "1px solid #efefef",
                                                                                    padding:
                                                                                        "3px 7px",
                                                                                    fontSize:
                                                                                        "16px",
                                                                                }}
                                                                            >
                                                                                {item.other_qualification ||
                                                                                    "N/A"}{" "}
                                                                                (Other)
                                                                            </td>
                                                                        </>
                                                                    )}

                                                                    {item.other_qualification && (
                                                                        <td
                                                                            style={{
                                                                                border: "1px solid #efefef",
                                                                                padding:
                                                                                    "3px 7px",
                                                                                fontSize:
                                                                                    "16px",
                                                                            }}
                                                                        >
                                                                            {" "}
                                                                            {item.certificate_path
                                                                                ? "Attached doc"
                                                                                : "No Attachment"}{" "}
                                                                            <i className="fa-solid fa-paperclip"></i>
                                                                        </td>
                                                                    )}
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                )
                                            )}
                                           
                                            <tr>
                                                <td
                                                    style={{
                                                        paddingTop: "30px",
                                                    }}
                                                    colSpan={4}
                                                >
                                                    <h3 className="modal-title-details">
                                                        <i className="fa-solid fa-trophy"></i>{" "}
                                                        Best Sports Achievement
                                                    </h3>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style={{ padding: "10px", width: "50%" }}
                                                    colSpan={2}
                                                >
                                                    <small
                                                        style={{
                                                            fontSize: "16px",
                                                        }}
                                                    >
                                                        Name of Tournament
                                                    </small>
                                                    <h5 style={{ fontSize: "15px", }}>
                                                    
                                                        { previewDetails &&
                                                             previewDetails?.application_details?.sports_discipline_hosp
                                                                .tournament.tournament
                                                        } 
                                                    </h5>
                                                </td>
                                                <td
                                                    style={{ padding: "10px" }}
                                                    colSpan={2}
                                                >
                                                    <small
                                                        style={{
                                                            fontSize: "16px",
                                                        }}
                                                    >
                                                        Organizing Authority
                                                    </small>
                                                    <h5 style={{ fontSize: "15px", }}>
                                                  
                                                        {  previewDetails?.application_details &&
                                                             previewDetails?.application_details?.sports_discipline_hosp
                                                                .organizing_committee
                                                        } 
                                                    </h5>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style={{ padding: "10px" }}>
                                                    <small
                                                        style={{
                                                            fontSize: "16px",
                                                        }}
                                                    >
                                                        Venue of Tournament
                                                    </small>
                                                    <h5 style={{ fontSize: "15px", }}>

                                                        { previewDetails?.application_details &&
                                                             previewDetails?.application_details?.sports_discipline_hosp
                                                                .tournament_venue
                                                        }
                                                    </h5>
                                                </td>

                                                <td style={{ padding: "10px" }}>
                                                    <small
                                                        style={{
                                                            fontSize: "16px",
                                                        }}
                                                    >
                                                        Medal Won
                                                    </small>
                                                    <h5 style={{ fontSize: "15px", }}>
                                                        { previewDetails?.application_details &&  previewDetails?.application_details?.sports_discipline_hosp
                                                            .medal_won ||
                                                            "None"}
                                                    </h5>
                                                </td>
                                                <td style={{ padding: "10px" }}>
                                                    <small
                                                        style={{
                                                            fontSize: "16px",
                                                        }}
                                                    >
                                                        Achievement date
                                                    </small>
                                                    <h5 style={{ fontSize: "15px", }}>
                                                        {new Date(
                                                             previewDetails?.application_details?.sports_discipline_hosp.achievement_date
                                                        ).toLocaleDateString()}
                                                    </h5>
                                                </td>
                                                { previewDetails?.application_details?.sports_discipline_hosp?.event_type == "2" &&
                                                <td
                                                    style={{ padding: "10px" }}
                                                >
                                                    <small
                                                        style={{
                                                            fontSize: "16px",
                                                        }}
                                                    >
                                                        Match played by team
                                                    </small>
                                                    <h5 style={{ fontSize: "15px", }}>
                                                        { previewDetails?.application_details &&
                                                             previewDetails?.application_details?.sports_discipline_hosp
                                                                .match_played_by_team ||
                                                            "N/A"
                                                        }
                                                    </h5>
                                                </td>
                                                }
                                                </tr>
                                                <tr>
                                                { previewDetails?.application_details?.sports_discipline_hosp?.event_type == "2" &&
                                                <td
                                                    style={{ padding: "10px" }}
                                                >
                                                    <small
                                                        style={{
                                                            fontSize: "16px",
                                                        }}
                                                    >
                                                        Match played by me
                                                    </small>
                                                    <h5 style={{ fontSize: "15px", }}>
                                                        { previewDetails?.application_details &&
                                                             previewDetails?.application_details?.sports_discipline_hosp
                                                                .match_played_by_me
                                                        }
                                                    </h5>
                                                </td>
                                                }
                                            </tr>
                                            <tr>
                                                <td colSpan={4} style={{pageBreakAfter:"always"}}></td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style={{
                                                        paddingTop: "20px",
                                                    }}
                                                    colSpan={4}
                                                >
                                                    <h3 className="modal-title-details">
                                                        <i className="fa-solid fa-paperclip"></i>{" "}
                                                        Documents Attached
                                                    </h3>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style={{ padding: "10px" }}
                                                    colSpan={4} className="uploaded-doc"
                                                >
                                                    <div
                                                        style={{
                                                            display: "flex",
                                                            justifyContent:
                                                                "space-between",
                                                            flexWrap: "nowrap",
                                                            marginTop: "8px",
                                                        }}
                                                    >
                                                        {[
                                                              ...(previewDetails?.application_details?.domicile_doc
                                                                ? [
                                                                    {
                                                                      src: "/assets/job_app/dash/images/PDF_file_icon.svg",
                                                                      label: "Haryana Domicile",
                                                                    },
                                                                  ]
                                                                : []),
                                                              ...(previewDetails?.application_details?.national_level_doc
                                                                ? [
                                                                    {
                                                                      src: "/assets/job_app/dash/images/PDF_file_icon.svg",
                                                                      label: "Nation Level Certificate",
                                                                    },
                                                                  ]
                                                                : []),
                                                              ...(previewDetails?.application_details?.organisation_doc
                                                                ? [
                                                                    {
                                                                      src: "/assets/job_app/dash/images/PDF_file_icon.svg",
                                                                      label: "Organisation Represented Certificate",
                                                                    },
                                                                  ]
                                                                : []),
                                                                ...(previewDetails?.application_details?.sports_discipline_hosp?.osp_achivement_certificate_path
                                                                    ? [
                                                                        {
                                                                          src: "/assets/job_app/dash/images/PDF_file_icon.svg",
                                                                          label: "Outstanding Sports Person Achievment Certificate",
                                                                        },
                                                                      ]
                                                                    : []),  
                                                                    ...(previewDetails?.application_details?.sports_discipline_hosp?.international_achievement_Verification_certificate_path
                                                                        ? [
                                                                            {
                                                                              src: "../assets/job_app/dash/images/PDF_file_icon.svg",
                                                                              label: "International Achievement and Verification Certificate",
                                                                            },
                                                                          ]
                                                                        : []),  
                                                            
                                                        ].map((doc, i) => (
                                                            <div
                                                                key={i}
                                                                style={{
                                                                    width: "22%",
                                                                }}
                                                            >
                                                                <img
                                                                    src={
                                                                        doc.src
                                                                            ? `${doc.src}`
                                                                            : "default.jpg"
                                                                    }
                                                                    width="60px"
                                                                    alt={
                                                                        doc.label
                                                                    }
                                                                />
                                                                <h6
                                                                    style={{
                                                                        marginTop:
                                                                            "3px",
                                                                        fontWeight: 500,
                                                                        fontSize:
                                                                            "14px",
                                                                    }}
                                                                >{`${i + 1}. ${
                                                                    doc.label
                                                                }`}</h6>
                                                            </div>
                                                        ))}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    colSpan={4}
                                                    style={{
                                                        padding: "10px",
                                                        background: "#eee",
                                                    }}
                                                >
                                                    <h4>Declaration</h4>
                                                    <ul>
                                                    {declarationList.map((label:any, index) => (
  <li key={index}>{typeof label === 'string' ? label : label?.point_text}</li>
))}
                                                    </ul>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colSpan={2} style={{padding:"20px 10px"}}>
                                                    <h5 style={{fontSize:"15px"}}>
                                                        Date -{" "}
                                                        {/* <strong>
                                            {new Date(
                                                otpData.created_at
                                            ).toLocaleDateString()}
                                        </strong> */}
                                                    </h5>
                                                </td>
                                                <td style={{ padding: "20px" }} align="right" colSpan={2}>
                                                    <div
                                                        style={{
                                                            width: "350px",
                                                            textAlign: "center",
                                                        }}
                                                    >
                                                        <span
                                                            style={{
                                                                width: "100%",
                                                                borderBottom:
                                                                    "1px dashed #9999",
                                                                height: "1px",
                                                                display:
                                                                    "block",
                                                                marginTop:
                                                                    "30px",
                                                                marginBottom:
                                                                    "10px",
                                                            }}
                                                        ></span>
                                                    <h5 style={{fontSize:"15px"}}>
                                                            (Signature of
                                                            Sportsperson)
                                                        </h5>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
};

export default ApplicationPreview;
