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
            element.style.display = 'none';
          });
        }

        

    return (
        <div>
           
            <div ref={certificateRef} >
                <div className="certificate-card">
                    <table width="100%">
                        <thead style={{ background: "#225395", color: "#fff" }}>
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
                                        }}
                                    >
                                        <img
                                            src="http://164.100.137.70/assets/job_app/dash/images/logo-sports.png"
                                            alt="Sports Haryana Govt"
                                            style={{ height: "80px" }}
                                        />{" "}
                                        Application for Sports Gradation
                                        Certificate

                                      
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
                                                <small>4. District</small>
                                                <h5>
                                                    {
                                                        otpData.district_sportsperson_belongs
                                                    }
                                                </h5>
                                            </td>
                                            <td rowSpan={2} width="19%">
                                                <img
                                                    src={
                                                        otpData.profile_picture
                                                            ? `/storage/${otpData.profile_picture}`
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
                                                <small>5. Domicile State</small>
                                                <h5>
                                                    {otpData.domicile_state}
                                                </h5>
                                            </td>
                                            <td>
                                                <small>
                                                    6. State/Organization
                                                </small>
                                                <h5>
                                                    {
                                                        otpData.plays_for_statte_org
                                                    }
                                                </h5>
                                            </td>
                                            <td>
                                                <small>
                                                    7. Sports Discipline
                                                </small>
                                                <h5>
                                                    {
                                                        otpData.name_sports_discipline
                                                    }
                                                </h5>
                                            </td>
                                            <td>
                                                <small>Type of Event</small>
                                                <h5>{otpData.type_of_event}</h5>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colSpan={3} width="58%">
                                                <small>
                                                    Name of Tournament
                                                </small>
                                                <h5 style={{ width: "95%" }}>
                                                    {otpData.type_of_event}
                                                </h5>
                                            </td>
                                            <td colSpan={2}>
                                                <small>
                                                    Organizing Authority
                                                </small>
                                                <h5 style={{ width: "95%" }}>
                                                    {
                                                        otpData.organising_authority
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
                                                        otpData.venue_of_tournament
                                                    }
                                                </h5>
                                            </td>
                                            <td>
                                                <small>Date</small>
                                                <h5>
                                                    {new Date(
                                                        otpData.month_year
                                                    ).toLocaleDateString()}
                                                </h5>
                                            </td>
                                            <td>
                                                <small>Tournament Type</small>
                                                <h5>
                                                    {otpData.tournament_type}
                                                </h5>
                                            </td>
                                            <td>
                                                <small>Medal Won</small>
                                                <h5>{otpData.medal_won}</h5>
                                            </td>
                                            <td>
                                                <small>
                                                    Participation Level
                                                </small>
                                                <h5>
                                                    {
                                                        otpData.participation_level
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
                                                src: otpData.aadhaar_card,
                                                label: "Aadhaar Card",
                                            },
                                            {
                                                src: otpData.domicile_certificate,
                                                label: "Domicile Certificate",
                                            },
                                            {
                                                src: otpData.sports_certificate,
                                                label: "Achievement Certificate",
                                            },
                                            {
                                                src: otpData.more_than25_photo,
                                                label: "Proof of Playing 25% Matches",
                                            },
                                        ].map((doc, i) => (
                                            <div
                                                key={i}
                                                style={{ width: "22%" }}
                                            >
                                                <img
                                                    src={
                                                        doc.src
                                                            ? `/storage/${doc.src}`
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
                                            I certify that I am currently a
                                            domicile/resident of Haryana.
                                        </li>
                                        <li>
                                            I certify that I have never played
                                            for any State or Union Territory
                                            other than Haryana.
                                        </li>
                                        <li>
                                            I certify that I have played for
                                            Haryana at the
                                            National/International Level.
                                        </li>
                                        <li>
                                            I certify that I have not been
                                            penalized for any unfair practice
                                            like age fraud, doping, etc., in the
                                            tournament for which cash award is
                                            being applied for.
                                        </li>
                                        <li>
                                            I certify that I have enclosed the
                                            self-attested copies of the
                                            documents as per requirements.
                                        </li>
                                        <li>
                                            I also understand that if any
                                            information provided by me for the
                                            grant of Gradation Certificate is
                                            found to be false or incorrect, then
                                            I shall be liable for any penal
                                            action.
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
                                        <strong>
                                            {new Date(
                                                otpData.created_at
                                            ).toLocaleDateString()}
                                        </strong>
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