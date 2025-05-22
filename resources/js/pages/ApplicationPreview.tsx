import React, { useEffect, useRef, useState } from "react";
import { useNavigate } from "react-router-dom";
import html2pdf from 'html2pdf.js';

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
  otpData: OtpData
}
const ApplicationPreview = () => {
    const defaultOtpData: OtpData = {
        certificate_no: '',
        sports_person_name: '',
        aadhaar_no: '',
        mobile_no: '',
        district_sportsperson_belongs: '',
        profile_picture: '',
        domicile_state: '',
        plays_for_statte_org: '',
        name_sports_discipline: '',
        type_of_event: '',
        organising_authority: '',
        venue_of_tournament: '',
        month_year: '',
        tournament_type: '',
        medal_won: '',
        participation_level: '',
        aadhaar_card: '',
        domicile_certificate: '',
        sports_certificate: '',
        more_than25_photo: '',
        created_at: ''
      };
      const otpData:OtpData = defaultOtpData;
    const navigate = useNavigate();
    const userData = JSON.parse(localStorage.getItem("user")!);
    const userDetails = userData?.user_details || {};
const certificateRef = useRef<HTMLDivElement>(null);
    const downloadPDF = () => {
        const element = certificateRef.current;
        if (!element) return;
    
        element.style.display = 'block';
    
        const opt = {
          margin: 0.1,
          filename: 'sports-certificate.pdf',
          image: { type: 'jpeg', quality: 0.98 },
          html2canvas: { scale: 2 },
          jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' },
        };
    
        html2pdf()
          .set(opt)
          .from(element)
          .save()
          .then(() => {
            // element.style.display = 'none';
          });
        }

        

    return (
        <div>
           
            <div ref={certificateRef} >
                <div className="certificate-card">
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
                                            fontSize: "17px",
                                            fontWeight: "normal",
                                            display:"flex", alignItems:"center"
                                        }}
                                    >
                                        <img
                                            src="../assets/job_app/dash/images/logo-sports.png"
                                            alt="Sports Haryana Govt"
                                            style={{ height: "80px" }}
                                        />{" "}
                                        <h3 style={{marginLeft:"10px"}}>Sports Gradation Certificate
                                        <small style={{fontSize:"16px", fontWeight:"normal", float:"left", width:"100%"}}>Let the young minds grow to the full potential</small>
                                        </h3>

                                      
                                    </div>
                                    <div>
                                        <p
                                            style={{
                                                fontSize: "15px",
                                                margin: "0 0 5px",
                                                color: "#fff",
                                                fontWeight: "normal",
                                            }}
                                        >
                                            Application ID -{userDetails?.application_id}
                                            <strong
                                                style={{
                                                    borderBottom: "1px dotted",
                                                }}
                                            >
                                                {otpData.certificate_no}
                                            </strong>
                                        </p>
                                        <button className="btn btn-primary me-1" onClick={downloadPDF}>Download Form</button>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <table width="100%">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <small>1. Name</small>
                                                <h5>
                                                    {userDetails.full_name_en}
                                                </h5>
                                            </td>
                                            <td>
                                                <small>2. Aadhar No.</small>
                                                <h5>{userDetails.aadhaar}</h5>
                                            </td>
                                            <td>
                                                <small>3. Mobile No.</small>
                                                <h5>{userData.mobile}</h5>
                                            </td>
                                            <td>
                                                <small>4.  Date Of birth</small>
                                                <h5>
                                                    {
                                                        userDetails.date_of_birth
                                                    }
                                                </h5>
                                            </td>
                                            <td rowSpan={2} width="19%">
                                                <img
                                                    src={
                                                        userDetails.photo
                                                            ? `/storage/photo/${userDetails.photo}`
                                                            : "default.jpg"
                                                    }
                                                    width="140px"
                                                    height="140px"
                                                    style={{
                                                        border: "5px solid #eee",
                                                    }}
                                                    alt="Profile"
                                                />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <small>5. Caste Category</small>
                                                <h5>
                                                {userDetails.caste_category}
                                                </h5>
                                            </td>
                                            <td>
                                                <small>
                                                    6.  Haryana Domicle
                                                </small>
                                                <h5>
                                                {userDetails.domicile == "1"
                                                        ? "Yes"
                                                        : "No"}
                                                </h5>
                                            </td>
                                            <td>
                                                <small>
                                                    7.  Event type
                                                </small>
                                                <h5>
                                                {/* {userData.event_hosp
                                                        .event_type == "1"
                                                        ? "Individual"
                                                        : "Team"} */}
                                                        Individual
                                                </h5>
                                            </td>
                                            <td>
                                                <small>Played National Level</small>
                                                <h5>
                                                      {/* {userData.event_hosp
                                                        .played_national_level ==
                                                    "1"
                                                        ? "Yes"
                                                        : "No"} */}
                                                        yes
                                                        </h5>
                                            </td>
                                            </tr>
                                            <tr>
                                            <td>
                                                <small> Name of Central Organisation
                                                Represented</small>
                                                <h5>  
                                                     {/* {
                                                        userData.event_hosp.organisation_represented ?? 'N/A'
                                                    } */}
                                                    'N/A'
                                                    </h5>
                                            </td>
                                        </tr>
                                        {userData.education_hosp.length > 0 && 
                                            userData.education_hosp.map((item, index) => (
                                                <tr key={item.id || index}>
                                                <td colSpan={3} width="58%">
                                                    <small>Qualification</small>
                                                    <h5 style={{ width: "95%" }}>
                                                    {item.qualification}
                                                    </h5>
                                                </td>
                                                {item.other_qualification && (
                                                        <td
                                                            style={{
                                                                padding: "15px",
                                                            }}
                                                        >
                                                           <small>Other Qualification</small>
                                                           <h5 style={{ width: "95%" }}>
                                                           {item.other_qualification ||
                                                                    "N/A"}
                                                    </h5>
                                                        </td>
                                                    )}
                                                </tr>
                                            ))
                                            }
                                        <tr>
                                            <td colSpan={3} width="58%">
                                                <small>
                                                    Name of Tournament
                                                </small>
                                                <h5 style={{ width: "95%" }}>
                                                {
                                                        userData
                                                            .sports_discipline_hosp
                                                            .tournament_id
                                                    }
                                                </h5>
                                            </td>
                                            <td colSpan={2}>
                                                <small>
                                                    Organizing Authority
                                                </small>
                                                <h5 style={{ width: "95%" }}>
                                                    {
                                                         userData
                                                         .sports_discipline_hosp.organizing_committee
                                                    }
                                                </h5>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <small>
                                                    Venue of Tournament
                                                </small>
                                                <h5>
                                                    {
                                                          userData
                                                          .sports_discipline_hosp
                                                          .tournament_venue
                                                    }
                                                </h5>
                                            </td>
                                            <td>
                                                <small>Achievement date</small>
                                                <h5>
                                                    {new Date(
                                                        userData
                                                        .sports_discipline_hosp.achievement_date
                                                    ).toLocaleDateString()}
                                                </h5>
                                            </td>
                                           
                                            <td>
                                                <small>Medal Won</small>
                                                <h5>{userData
                                                        .sports_discipline_hosp
                                                        .medal_won || "None"}</h5>
                                            </td>
                                            <td>
                                                <small>
                                                Match played by team
                                                </small>
                                                <h5>
                                                    {
                                                        userData
                                                        .sports_discipline_hosp.match_played_by_team
                                                    }
                                                </h5>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style={{ padding: "10px" }}>
                                    <h4>Documents Attached</h4>
                                    <div
                                        style={{
                                            display: "flex",
                                            justifyContent: "space-between",
                                            flexWrap: "nowrap",
                                            marginTop: "8px",
                                        }}
                                    >
                                        {[
                                            {
                                                src: 'https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg',
                                                label: "Haryana Domicile",
                                            },
                                            {
                                                src: 'https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg',
                                                label: "Nation Level Certificate",
                                            },
                                            {
                                                src: 'https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg',
                                                label: " International Achievement and Verification Certificate",
                                            },
                                            {
                                                src: 'https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg',
                                                label: "Outstanding Sports Person Achievment Certificate ",
                                            },
                                        ].map((doc, i) => (
                                            <div
                                                key={i}
                                                style={{ width: "22%" }}
                                            >
                                                <img
                                                    src={
                                                        doc.src
                                                            ? `${doc.src}`
                                                            : "default.jpg"
                                                    }
                                                    width="140"
                                                    height="140"
                                                    style={{
                                                        border: "5px solid #eee",
                                                    }}
                                                    alt={doc.label}
                                                />
                                                <h5
                                                    style={{
                                                        marginTop: "3px",
                                                        fontWeight: 500,
                                                        fontSize: "16px",
                                                    }}
                                                >{`${i + 1}. ${doc.label}`}</h5>
                                            </div>
                                        ))}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td
                                    style={{
                                        padding: "10px",
                                        background: "#eee",
                                    }}
                                >
                                    <h4>Declaration</h4>
                                    <ul>
                                        <li>
                                        I have read the Haryana
                                                        Outstanding
                                                        Sportspersons
                                                        (Recruitment and
                                                        Condition of Service)
                                                        Rules, 2021 and declare
                                                        that I am eligible for
                                                        submission of my
                                                        application for
                                                        considration of
                                                        appointment under these
                                                        Rules.
                                        </li>
                                        <li>
                                        I have enclosed
                                                        self-attested copies of
                                                        all documents in support
                                                        of my application.
                                        </li>
                                        <li>
                                        I have played 50% or
                                                        more of the games played
                                                        by team in the
                                                        tournament at serial no.
                                                        12 above.
                                        </li>
                                        <li>
                                        I did not represent a
                                                        State/UT other than
                                                        Haryana at the national
                                                        level.
                                        </li>
                                        <li>
                                        I am guilty of doping,
                                                        sexual harassment and
                                                        abuse, competitive
                                                        manipulation like
                                                        betting, inside
                                                        information, match
                                                        fixing, tanking,
                                                        threatening the
                                                        integrity and essence of
                                                        Sports.
                                        </li>
                                        <li>
                                        If appointment is
                                                        offered, I undertake
                                                        that I shall have no
                                                        subsisting contract for
                                                        pecuniaryg gains like
                                                        commercial endorsement
                                                        or professional sport
                                                        before joining the
                                                        service.
                                        </li>
                                        
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td style={{ padding: "20px" }}>
                                    <div
                                        style={{
                                            width: "250px",
                                            textAlign: "center",
                                        }}
                                    >
                                        <span
                                            style={{
                                                width: "100%",
                                                borderBottom:
                                                    "1px dashed #9999",
                                                height: "1px",
                                                display: "block",
                                                marginTop: "30px",
                                                marginBottom: "10px",
                                            }}
                                        ></span>
                                        <h6>(Signature of Sportsperson)</h6>
                                    </div>
                                </td>
                                <td>
                                    <h6 style={{ marginLeft: "-173px" }}>
                                        Date -{" "}
                                        {/* <strong>
                                            {new Date(
                                                otpData.created_at
                                            ).toLocaleDateString()}
                                        </strong> */}
                                    </h6>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    );
};

export default ApplicationPreview;