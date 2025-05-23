import React, { useEffect, useRef, useState } from "react";
import { createSearchParams, Link, useNavigate } from "react-router-dom";
import { useForm, useFormState } from "react-hook-form";
import {
    fetchDeclarationDetails,
    fetchDeclarationsList,
    fetchEducation,
    fetchEvent,
    fetchGameList,
    fetchSchedule12Listing,
    fetchSportsDiscipline,
    fetchUserDetails,
    saveDeclarations,
    saveEducation,
    saveEvent,
    saveSportsDiscipline,
    updateEvent,
} from "../services/hosp-service";

import html2pdf from "html2pdf.js";
import { toast } from 'react-toastify';

const stepsTotal = 4;
const HospForm = () => {
    // const HospForm = () => {
    const navigate = useNavigate();
    let userData = JSON.parse(localStorage.getItem("user")!);
    let userDetails = userData?.user_details || {};
    if (userData.declarations_hosp && userData.declarations_hosp.id) {
        // navigate("/hosp/preview-application");
    }
    // Get user data from localStorage

    const logout = () => {
        localStorage.clear();
        navigate("/login");
    };
    const params = new URLSearchParams(window.location.search);
    // const step: any = params.get("step") ? parseInt(params.get("step")!) : 1;
    const step: any = 2; // start from 2, first step is basic detail
    // alert(step)
    const [currentStep, setCurrentStep] = useState(step);
    const [physical_disability, setPhysicalDisability] = useState(false);
    const [represented_india, setRepresentedIndia] = useState("1");
    const [tournament_level, setTournamentLevel] = useState("2");
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [isbtnDisabled, setIsbtnDisabled] = useState(false);
    const [isSubmitted, setIsSubmitted] = useState(false);
    const [educationErrors, setEducationErrors] = useState("");
    const [tournamentList, setTournamentList] = useState([]);
    const [gamesList, setGamesList] = useState([]);
    const [eventTitle, setEventTitle] = useState("Event Details");
    const {
        register,
        handleSubmit,
        watch,
        setError,
        setValue,
        getValues,
        formState: { errors },
    } = useForm();

    type SportsDisciplineFormData = {
        physical_disability: string;
        disability_type_id: string;
        disability_doc: File | null;
        event_type: string;
        tournament_id: string;
        organizing_committee: string;
        tournament_level: string;
        represented_india: string;
        game_id: string;
        // certificate_path: File | null;
        achievement_date: string;
        tournament_venue: string;
        medal_won: string;

        // participation_level: string;
        match_played_by_team: string;
        match_played_by_me: string;
        osp_achivement_certificate_path: File | null;
        international_achievement_Verification_certificate_path: File | null;
        declaration_file: string;
        declaration_ids: string;
    };

    type FormErrors = Partial<Record<keyof SportsDisciplineFormData, string>>;

    const [formData, setFormData] = useState<SportsDisciplineFormData>({
        physical_disability: "2",
        disability_type_id: "",
        disability_doc: null,
        tournament_id: "",
        organizing_committee: "",
        tournament_level: "",
        represented_india: "",
        game_id: "",
        // certificate_path: null,
        achievement_date: "",
        tournament_venue: "",
        medal_won: "",
        event_type: "",
        match_played_by_team: "",
        match_played_by_me: "",
        osp_achivement_certificate_path: null,
        international_achievement_Verification_certificate_path: null,
    });

    const defaultDeclarations = [
        "1. I have read the Haryana Outstanding Sportspersons (Recruitment and Condition of Service) Rules, 2021 and declare that I am eligible for submission of my application for consideration of appointment under these Rules.",
        "2. I have enclosed self-attested copies of all documents in support of my application.",
        "3. I have played in 50% or more of the games played by team in the tournament.",
        "4. I did not represent a State/UT other than Haryana at the national level.",
        "5. I am not guilty of doping, sexual harassment and abuse, competitive manipulation like betting, inside information, match fixing, tanking, threatening the integrity and essence of sports.",
        "6. If appointment is offered, I undertake that I shall have no subsisting contract for pecuniary gains like commercial endorsement or professional sport before joining the service.",
        "7. I forego my earlier claim made under the Haryana Outstanding Sportsperson.",
    ];

    const [sportdiserrors, setErrors] = useState<FormErrors>({});
    const [declarationList, setDeclarationList] =
        useState<string[]>(defaultDeclarations);
    const [diclarationerrors, setDiclarationErrors] = useState({
        msg: "",
    });

    // useEffect to filter games based on formData

    const fetchGames = async (is_para = 0) => {
        try {
            const data = await fetchGameList();

            if (data.status === "success") {
                setGamesList(data.list);
                console.log("formData.physical_disability", gamesList);
                // if (formData && formData.physical_disability =='1') {
                //     const filteredGames = gamesList.filter((game:any) => game.is_para === 1);
                //     console.log('filteredGames',filteredGames);
                //     setGamesList(filteredGames);
                // }else {
                //     const filteredGames = gamesList.filter((game:any) => game.is_para === 0);
                //     console.log('filteredGames',filteredGames);
                //     setGamesList(filteredGames);
                // }
            }
        } catch (error) {
            console.error("Error loading form data", error);
        }
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

    useEffect(() => {
        const fetchEventData = async () => {
            try {
                const data = await fetchEvent(); // Replace with your endpoint
                // Option 1: Set fields one-by-one
                setValue("id", data.id);
                // setValue("aadhaar", data.aadhaar);
                setValue("event_type", data.event_type);
                const selectedText =
                    data.event_type == "1" ? "Individual Event" : "Team Event";
                setEventTitle(selectedText);

                // setValue("domicile", data.domicile?.toString());
                setValue(
                    "played_national",
                    data.played_national_level?.toString()
                );
                setValue("central_org_name", data.organisation_represented);
                // setValue("domicile_doc", data.domicile_doc);
                setValue("national_level_doc", data.national_level_doc);
                setValue("organisation_doc", data.organisation_doc);

                // Option 2: Reset entire form (if keys match)
                // reset(data);
                await fetchTournamentList(data.event_type);
                setTimeout(() => {
                    setValue("tournament", data.tournament_id);
                }, 1);
            } catch (error) {
                console.error("Error loading form data", error);
            }
        };
        const fetchEducationData = async () => {
            try {
                const data = await fetchEducation(); // Replace with your endpoint
                if (data && data.length > 0) {
                    const formatted = data.map((item) => ({
                        id: item.id || "",
                        qualification: item.qualification || "",
                        otherText: item.other_qualification || "",
                        certificate: null, // Initially no file selected
                        fileUrl: `${item.certificate_path}`, // For display/download
                    }));
                    setEducationFields(formatted);
                }
            } catch (error) {
                console.error("Error loading form data", error);
            }
        };

        const fetchSportsDisciplineData = async () => {
            try {
                const data = await fetchSportsDiscipline(); // Replace with your endpoint
                console.log("data && data.physical_disability", data);
                console.log(
                    "data && data.physical_disability",
                    data && data.physical_disability == 1
                );

                setFormData({
                    physical_disability: String(data.physical_disability ?? ""),
                    disability_type_id: String(data.disability_type_id ?? ""),
                    disability_doc: data.disability_doc ?? null, // We can't prefill file inputs
                    // event_type: String(data.event_type ?? ""),
                    tournament_id: String(data.tournament_id ?? ""),
                    event_type: String(data.event_type ?? ""),
                    organizing_committee: data.organizing_committee ?? "",
                    tournament_level: String(data.tournament_level ?? ""),
                    represented_india: String(data.represented_india ?? ""),
                    game_id: String(data.game_id ?? ""),
                    osp_achivement_certificate_path:
                        data.osp_achivement_certificate_path ?? null,
                    international_achievement_Verification_certificate_path:
                        data.international_achievement_Verification_certificate_path ??
                        null,
                    achievement_date: data.achievement_date ?? "",
                    tournament_venue: data.tournament_venue ?? "",
                    medal_won: data.medal_won ?? "",
                    // participation_level: data.participation_level ?? "",
                    match_played_by_team: data.match_played_by_team ?? "",
                    match_played_by_me: data.match_played_by_me ?? "",
                });
                await fetchTournamentList(data.event_type);
            } catch (error) {
                console.error("Error loading form data", error);
            }
        };
        const fetchDeclarationDetail = async () => {
            try {
                const data = await fetchDeclarationDetails();

                const fetchedDeclarations = data.declaration_ids;

                // Convert array of declaration_id into the checkbox state
                const updatedDeclarations: any = { ...declarations };
                fetchedDeclarations.forEach((item: any) => {
                    updatedDeclarations[`d${item.declaration_id}`] = true;
                });

                setDeclarations(updatedDeclarations);

                // If all declarations were selected
                const allChecked =
                    Object.values(updatedDeclarations).every(Boolean);
                setAcceptAll(allChecked);

                // Set previously uploaded file path if needed
                if (fetchedDeclarations.length > 0) {
                    setDeclarationFile(fetchedDeclarations[0].declaration_file);
                }
            } catch (error) {
                console.error("Error loading form data", error);
            }
        };
        const fetchDeclarations = async () => {
            try {
                const data = await fetchDeclarationsList();
                const apiDeclarations = data.declarations;
                const texts = apiDeclarations.map(
                    (item: any, i: number) => `${i + 1}. ${item.point_text}`
                );
                setDeclarationList(texts);

                // Sync checkbox state
                const newDeclarationState: Record<string, boolean> = {};
                texts.forEach((_, i) => {
                    newDeclarationState[`d${i + 1}`] = false;
                });
                setDeclarations(newDeclarationState);
            } catch (error) {
                console.error("Error loading form data", error);
            }
        };
        // fetchEventData();
        fetchEducationData();
        fetchSportsDisciplineData();
        fetchGames();
        fetchDeclarations();
        fetchDeclarationDetail();
    }, []);

    const fetchTournamentList = async (event_type) => {
        const selectedText =
            event_type == "1" ? "Individual Event" : "Team Event";
        setEventTitle(selectedText);
        setValue("tournament", ""); // Reset tournament selection
        if(event_type) {
            const response = await fetchSchedule12Listing(event_type);
            if (response.status === "success") {
                setTournamentList(response.list);
            }
            console.log("tournament listing", response.list);
        }
       
    };
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
                // search: createSearchParams({
                //     step: String(currentStep),
                // }).toString(),
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

    // const onSubmit = (data) => {
    //     console.log("Form Data:", data);
    //     // Here you can handle the form submission (e.g. send to API)
    // };
    const progressPercent = (100 / stepsTotal) * currentStep;

    const nextStep = () => {
        if (currentStep < stepsTotal) setCurrentStep((nxt) => nxt + 1);
    };

    const prevStep = () => {
        if (currentStep > 0) {
            setCurrentStep((prev) => prev - 1);
            // navigate({
            //     pathname: location.pathname, // or keep current path
            //     search: createSearchParams({
            //         step: String(currentStep - 1),
            //     }).toString(),
            // });
        }
    };

    const finalSubmit = async () => {
        document.body.classList.add("loaded");
        setIsSubmitting(true);
        await new Promise((res) => setTimeout(res, 100));
        // setCurrentStep((nxt) => nxt + 1);
        document.body.classList.remove("loaded");
        setIsSubmitting(false);
        setIsSubmitted(true);
    };

    const [educationFields, setEducationFields] = useState([
        { qualification: "", certificate: null, otherText: "" ,id:''},
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
        console.log(value.checked);
        const is_para = value.checked ? 1 : 0;
        setPhysicalDisability(value.checked);
        setFormData((prev) => ({ ...prev, game_id: "" }));
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

    // const handleSingleChange = (key: string) => {
    //     const updated = {
    //         ...declarations,
    //         [key]: !declarations[key as keyof typeof declarations],
    //     };
    //     setDeclarations(updated);

    //     // Check if all are now true
    //     const allChecked = Object.values(updated).every(Boolean);
    //     setAcceptAll(allChecked);
    // };

    const handleAcceptAll = () => {
        const newValue = !acceptAll;
        const updatedDeclarations: any = Object.fromEntries(
            Object.keys(declarations).map((k) => [k, newValue])
        );
        setDeclarations(updatedDeclarations);
        setAcceptAll(newValue);
    };
    const [declarationFile, setDeclarationFile] = useState<File | null>(null);

    const handleDeclarationFileChange = (
        e: React.ChangeEvent<HTMLInputElement>
    ) => {
        if (e.target.files?.[0]) {
            setDeclarationFile(e.target.files[0]);
        }
    };
    const getDeclarationIds = () => {
        return Object.entries(declarations)
            .filter(([_, checked]) => checked)
            .map(([key]) => parseInt(key.replace("d", ""))); // d1 => 1
    };

    const handleDeclarationSubmit = async () => {
        const formData = new FormData();

        // Append other form fields here...

        const declarationIds = getDeclarationIds();
        declarationIds.forEach((id) =>
            formData.append("declaration_ids[]", id.toString())
        );

        if (declarationFile) {
            formData.append("declaration_file", declarationFile);
        }

        try {
            setIsbtnDisabled(true)
            const response = await saveDeclarations(formData);
            finalSubmit();
        } catch (error) {
            setIsbtnDisabled(false)
            console.error("Error:", error);
            if (error.response?.status === 422) {
                const backendErrors = error.response.data.errors;
                Object.keys(backendErrors).forEach((field) => {
                    console.log("field", field);
                    console.log("backendErrors[field]", backendErrors[field]);
                    console.log(
                        "backendErrors[field][0]",
                        backendErrors[field][0]
                    );
                    // setFormErrors(validationErrors);

                    setDiclarationErrors({
                        msg: backendErrors[field][0] || "",
                    });
                });
            }
        }
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

    const handlePrintDeclaration = (e) => {
        e.preventDefault();
        const content = document.getElementById("print_declaration");
    
        if (!content) {
            alert("Declaration content not found.");
            return;
        }
    
        const htmlContent = `
        <html>
          <head>
            <style>
             body{padding:0; margin:0}
              .modal-title-details {
                  background: rgba(0, 0, 0, 0.04);
                  padding: 10px;
                  margin: 0;
                  color: #36454F;
                  font-size: 20px;
                  text-transform: uppercase;
              }
              .fa-solid, .fas {
                  font-weight: 900;
              }
                #print_declaration .logo a {
                    text-decoration: none;
                }
                    #print_declaration .logo a .h1-logo {
                    font-size: 1.1rem;
                }

                @page {
                    padding:0;size: A4;margin: 0mm 0mm 0mm 0mm;
                }
                @media print{}
              /* Optional: add more styles if needed */
            </style>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
          </head>
          <body>
            <div style="padding: 20px">
              ${content.innerHTML}
            </div>
          </body>
        </html>
      `;
      
    
        // Create temporary element
        const tempElement = document.createElement("div");
        tempElement.innerHTML = htmlContent;
        document.body.appendChild(tempElement); // required for html2canvas to work
    
        const opt = {
            margin: 0,
            filename: "declaration.pdf",
            image: { type: "jpeg", quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: "in", format: "a4", orientation: "portrait" },
        };
    
        html2pdf().set(opt).from(tempElement).save().then(() => {
            document.body.removeChild(tempElement); // clean up
        });
    };
    

    const onEventSubmit = async (data) => {
        const formData = new FormData();

        formData.append("id", data.id ?? null);
        formData.append("event_type", data.event_type);
        // formData.append("aadhaar", data.aadhaar);
        // formData.append("tournament", data.tournament);
        // formData.append("domicile", data.domicile);
        formData.append("played_national", data.played_national);
        if (data.id) {
            // formData.append(
            //     "domicile_certificate",
            //     data.domicile_doc ? data.domicile_doc : ""
            // );
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
        // if (data.domicile == "2") {
        //     formData.delete("domicile_certificate");
        // }
        if (data.played_national == "1") {
            formData.delete("org_certificate");
        }

        // if (data.domicile_certificate?.[0]) {
        //     formData.append(
        //         "domicile_certificate",
        //         data.domicile_certificate[0]
        //     );
        // }

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
                // search: createSearchParams({
                //     step: currentStep + 1,
                // }).toString(),
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
    const onEducationSubmit = async () => {
        const hasValidEntry = educationFields.some(
            (field: any) => {
                const isQualificationOther = field.qualification === "other";
                const hasOtherText = field.otherText && field.otherText.trim() !== "";
                const hasCertificate = field.certificate || field.fileUrl;
        
                return (
                    field.qualification &&
                    (!isQualificationOther || hasOtherText) &&
                    hasCertificate
                );
            }
        );
        console.log("hasValidEntry", educationFields);

        if (!hasValidEntry) {
            setEducationErrors(
                "Educational certificates required. 10th grade certificate is compulsory."
            );
            return;
        }

        setEducationErrors(""); // clear error if validation passes

        const formData = new FormData();
        console.log('educationFields',educationFields);
        
        educationFields.forEach((field:any, index) => {
            if(field.id) {
                formData.append(
                    `educations[${index}][id]`,
                    field.id
                );
            }
            
            formData.append(
                `educations[${index}][qualification]`,
                field.qualification
            );
            formData.append(
                `educations[${index}][otherText]`,
                field.otherText || ""
            );
            if (field.certificate && !field.fileUrl) {
                formData.append(
                    `educations[${index}][certificate]`,
                    field.certificate
                );
            }else if (!field.certificate && field.fileUrl) {
                formData.append(
                    `educations[${index}][certificate]`,
                    field.fileUrl
                );
            }
        });

        try {
            setIsbtnDisabled(true)
            const response = await saveEducation(formData);
            setIsbtnDisabled(false)
            //   const response = await axios.post("/api/education/store", formData, {
            //     headers: { "Content-Type": "multipart/form-data" },
            //   });

            //   setCurrentStep((prev) => prev + 1);
            if (currentStep < stepsTotal) setCurrentStep((nxt) => nxt + 1);
            navigate({
                pathname: location.pathname, // or keep current path
                // search: createSearchParams({
                //     step: currentStep + 1,
                // }).toString(),
            });
        } catch (error) {
            setIsbtnDisabled(false)
            console.error(
                "Save failed:",
                error.response?.data || error.message
            );
            setEducationErrors( 
                "Educational field/certificate required."
            );
        }
    };

    // const onEducationSubmit = async (data) => {
    //     if (currentStep < stepsTotal) setCurrentStep((nxt) => nxt + 1);
    //     navigate({
    //         pathname: location.pathname, // or keep current path
    //         search: createSearchParams({ step: currentStep + 1 }).toString(),
    //     });
    // };

    const setOrganizationCommittee = (e) => {
        console.log(e.target.value);
        tournamentList.forEach((element: any) => {
            if (element.id == e.target.value) {
                console.log(element);

                setFormData((d) => ({
                    ...d,
                    organizing_committee: element.organizing_authority,
                }));
            }
        });
    };
    const handleSportsDiscChanges = (
        e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>
    ) => {
        const { name, value, type } = e.target;
        // console.log(type);

        if (type === "file") {
            const file = (e.target as HTMLInputElement).files?.[0] || null;
            console.log("name", name);
            console.log("file", file);

            setFormData({ ...formData, [name]: file });
        } else {
            console.log("formdata", formData);

            setFormData({ ...formData, [name]: value });

            // Reset dependent fields
            if (name === "physical_disability" && value !== "1") {
                setFormData((prev) => ({
                    ...prev,
                    disability_type_id: "",
                    disability_doc: null,
                }));
            }

            if (name === "tournament_level" && value !== "1") {
                setFormData((prev) => ({
                    ...prev,
                    represented_india: "",
                }));
            }
        }
    };

    const validate = (): boolean => {
        const newErrors: FormErrors = {};

        if (!formData.physical_disability)
            newErrors.physical_disability = "Required";
        if (formData.physical_disability === "1") {
            if (!formData.disability_type_id)
                newErrors.disability_type_id = "Required";
            if (!formData.disability_doc) newErrors.disability_doc = "Required";
        }

        if (!formData.tournament_id) newErrors.tournament_id = "Required";
        if (!formData.event_type) newErrors.event_type = "Required";
        if (!formData.organizing_committee)
            newErrors.organizing_committee = "Required";
        if (!formData.tournament_level) newErrors.tournament_level = "Required";

        if (formData.tournament_level === "1") {
            if (!formData.represented_india)
                newErrors.represented_india = "Required";
        }

        if (!formData.game_id) newErrors.game_id = "Required";
        if (!formData.osp_achivement_certificate_path)
            newErrors.osp_achivement_certificate_path = "Required";
        // if (!formData.international_achievement_Verification_certificate_path)
        //     newErrors.international_achievement_Verification_certificate_path =
        //         "Required";
        if (!formData.achievement_date) newErrors.achievement_date = "Required";
        if (!formData.tournament_venue) newErrors.tournament_venue = "Required";
        // if (!formData.medal_won) newErrors.medal_won = "Required";
        if (eventTitle === "Team Event") {
            if (!formData.match_played_by_team)
                newErrors.match_played_by_team = "Required";
            if (!formData.match_played_by_me)
                newErrors.match_played_by_me = "Required";

             // Both fields must be numbers before comparison
            const teamMatches = Number(formData.match_played_by_team);
            const myMatches = Number(formData.match_played_by_me);

            if (
                !isNaN(teamMatches) &&
                !isNaN(myMatches) &&
                myMatches >= teamMatches
            ) {
                newErrors.match_played_by_me =
                    "Matches played by you must be less than matches played by the team";
                    toast.error(newErrors.match_played_by_me);
            }
        }

        setErrors(newErrors);
        return Object.keys(newErrors).length === 0;
    };

    const onSportDisciplineSubmit = async (e: React.FormEvent) => {
        console.log(
            "formData.physical_disability",
            formData.physical_disability
        );

        e.preventDefault();
        if (!validate()) return;

        const submissionData = new FormData();
        Object.entries(formData).forEach(([key, value]) => {
            submissionData.append(key, value ?? "");
        });
        try {
             // Submit using fetch or axios
            console.log("Submitting form...", formData);
            setIsbtnDisabled(true)
            const response = await saveSportsDiscipline(submissionData);
            fetchUserData();
            setIsbtnDisabled(false)
            if (currentStep < stepsTotal) setCurrentStep((nxt) => nxt + 1);

            navigate({
                pathname: location.pathname, // or keep current path
                // search: createSearchParams({
                //     step: currentStep + 1,
                // }).toString(),
            });
        } catch (error) {
            setIsbtnDisabled(false)
        }
       
        // axios.post('/api/sports-discipline', submissionData)
    };

    // const onSportDisciplineSubmit = async () => {
    //     if (currentStep < stepsTotal) setCurrentStep((nxt) => nxt + 1);
    //     navigate({
    //         pathname: location.pathname, // or keep current path
    //         search: createSearchParams({ step: currentStep + 1 }).toString(),
    //     });
    // };

    return (
        <div>
           
            <header className="hero-section">
                <div className="hero-content">
                   <div className="d-flex justify-content-start"> <img
                        src="/assets/images/logo-sports.png"
                        alt="Sports Department Logo"
                        className="header-logo mx-3"
                    />
                    <div className="hero-text text-start">
                        <h2>
                            Haryana Outstanding Sportspersons Application
                            <br />
                            <small>Sports Department, Haryana</small>
                        </h2>
                        <p>Let the young minds grow to the full potential</p>
                    </div></div>
                    <div className="float-end m-2">
                        <button
                            className="btn btn-danger me-1"
                            onClick={logout}
                        >
                                <i className="fa-solid fa-power-off"></i> Logout
                        </button>
                    </div>
                </div>

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
                {!isSubmitted && 
                    <div className="progress">
                        <ol>
                            <li
                                className={
                                    currentStep === 1 ? "progress-active" : ""
                                }
                            >
                                <a href="javascript:;" >1. Basic Details</a>
                            </li>

                            <li
                                className={
                                    currentStep === 2 ? "progress-active" : ""
                                }
                            >
                                <a href="javascript:;" >2. Education Details</a>
                            </li>    
                            <li
                                className={
                                    currentStep === 3 ? "progress-active" : ""
                                }
                            >
                                <a href="javascript:;" >3. Best Sports Achievement </a>
                            </li>
                            <li
                                className={
                                    currentStep === 4 ? "progress-active" : ""
                                }
                            >
                                <a href="javascript:;" >4. Declaration</a>
                            </li>
                        </ol>
                        <div
                            className="progress-bar progress-bar-striped progress-bar-animated bg-success"
                            role="progressbar"
                            style={{ width: `${progressPercent}%` }}
                        ></div>
                    </div>
                }
                {isSubmitting && (
                    // <div className="d-block text-center mt-5">Submitting...</div>
                    <div id="preloader-wrapper">
                        <div id="preloader"></div>
                        <div className="preloader-section section-left"></div>
                        <div className="preloader-section section-right"></div>
                    </div>
                )}
                <div className="float-end m-2" hidden={!isSubmitted}>
                    {/* <button
                        className="btn btn-primary me-1"
                        onClick={handlePrint}
                    >
                        Print <i className="fa fa-print"></i>
                    </button> */}
                   
                    {/* <button className="btn btn-primary me-1" onClick={downloadPDF}>Preview Form</button> */}
                </div>
                {!isSubmitted && !isSubmitting && (
                    <div>
                        <form
                            onSubmit={handleSubmit(onEventSubmit)}
                            className="needs-validation row g-3"
                            hidden={currentStep === 3 ? false : true}
                        >
                            <div className="row g-3">
                                {/* {eventTitle === "Individual Event" ? (
                                    <h6 className="text-center mt-4">
                                        FORM - I
                                        <br />
                                        [See rule 9 (1)]
                                    </h6>
                                ) : (
                                    <h6 className="text-center mt-4">
                                        FORM - II
                                        <br />
                                        [See rule 9 (1)]
                                        <br />
                                    </h6>
                                )}
                                <h5 className="text-center mt-1">
                                    {eventTitle}
                                </h5> */}

                                {/* <div className="col-md-6">
                                    <label>Select Tournament</label>
                                    <select
                                        {...register("tournament")}
                                        className={`form-select ${
                                            errors.tournament
                                                ? "is-invalid"
                                                : ""
                                        }`}
                                    >
                                        <option value="" selected disabled>
                                            Select
                                        </option>
                                        {tournamentList.map((item: any) => (
                                            <option
                                                key={item.id}
                                                value={item.id}
                                            >
                                                {item.tournament}
                                            </option>
                                        ))}
                                    </select>
                                    {errors.tournament && (
                                        <div className="invalid-feedback">
                                            {
                                                errors.tournament
                                                    .message as string
                                            }
                                        </div>
                                    )}
                                </div> */}

                                {/* <div className="col-md-6">
                                    <label>Haryana Resident/Domicile</label>
                                    <select
                                        {...register("domicile")}
                                        className={`form-select ${
                                            errors.domicile ? "is-invalid" : ""
                                        }`}
                                    >
                                        <option value="" selected disabled>
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
                                    <label>Attach Certificate (Domicile)</label>
                                    <input
                                        type="file"
                                        accept="application/pdf"
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
                                </div> */}

                                {/* <div className="col-md-6">
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
                                        <option value="" selected disabled>
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
                                        accept="application/pdf"
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
                                                    )}/certificates`}
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
                                {playedNational == "2" && (
                                    <div className="col-md-6">
                                        <label>
                                            Name of Central Organisation
                                            Represented
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
                                )}
                                {playedNational == "2" && (
                                    <div className="col-md-6">
                                        <label>
                                            Attach Certificate (Organisation
                                            Represented)
                                        </label>
                                        <input
                                            type="file"
                                            accept="application/pdf"
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
                                                        )}/certificates`}
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                    >
                                                        Click here to view
                                                        uploaded file
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
                                )} */}
                                {/* <hr /> */}
                                {/* <div className="col-12 text-end">
                                    <button
                                        id="next-btn"
                                        type="submit"
                                        className="btn btn-primary me-2"
                                        onClick={() =>
                                            navigate("/basic-details")
                                        }
                                    >
                                        Previous
                                    </button>
                                    <button
                                        id="next-btn"
                                        type="submit"
                                        className="btn btn-primary me-2"
                                    >
                                        Next
                                    </button>
                                </div> */}
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

                            {educationFields.map((field:any, index) => (
                                <div key={index} className="row g-3">
                                    <div className="col">
                                        <label>Select Qualification</label>
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
                                            <option value="" selected disabled>
                                                Select
                                            </option>
                                            <option value="10th">10th</option>
                                            <option value="12th">12th</option>
                                            <option value="Graduation">
                                                Graduation
                                            </option>
                                            <option value="Post graduation">
                                                Post Graduation
                                            </option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>

                                    {field.qualification === "Other" && (
                                        <div className="col-md-4">
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
                                              {educationErrors && (field.qualification === "Other" && !field.otherText) && (
                                            <div className="text-danger mt-2">
                                                {educationErrors}
                                            </div>
                                        )}
                                        </div>
                                       
                                    )}
                                    

                                    <div className="col">
                                        <label>Attach Certificates (PDF)</label>

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
                                        {field.fileUrl && (
                                            <div className="mt-1">
                                                <a
                                                    href={`/storage/education-certificates/${encodeURIComponent(
                                                        field.fileUrl
                                                    )}`}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                >
                                                    View Uploaded Certificate
                                                </a>
                                            </div>
                                        )}
                                    </div>

                                    <div className="col-md-1"><br /> 
                                        {educationFields.length > 1 && index != 0 && (
                                           <button
                                                type="button"
                                                className="btn btn-outline-danger"
                                                onClick={() =>
                                                    handleRemoveEducation(index)
                                                }
                                            >
                                                <i className="fa-regular fa-trash-can"></i>
                                            </button>
                                        )}
                                    </div>

                                    {educationErrors && (!field.qualification || (!field.certificate && !field.fileUrl)) && (
                                        <div className="text-danger mt-2">
                                            {educationErrors}
                                        </div>
                                    )}

                                   
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
                            <hr />
                            <div  className=" text-end mt-1">
                                {currentStep > 1 && (
                                    <Link
                                        type="button"
                                        to="/basic-details"
                                        className="float-none btn btn-secondary mx-2 text-capitalize"
                                    >
                                        Previous
                                    </Link>
                                )}

                                <button
                                    type="submit"
                                    className="btn btn-primary"
                                    disabled={isbtnDisabled}
                                >
                                    Next
                                </button>
                            </div>
                        </form>
                        <form
                            className="needs-validation row g-3 mt-4"
                            hidden={currentStep === 3 ? false : true}
                            onSubmit={onSportDisciplineSubmit}
                        >
                            {eventTitle === "Individual Event" ? (
                                <h6 className="text-center mt-4">
                                    FORM - I
                                    <br />
                                    [See rule 9 (1)]
                                </h6>
                            ) : (
                                <h6 className="text-center mt-4">
                                    FORM - II
                                    <br />
                                    [See rule 9 (1)]
                                    <br />
                                </h6>
                            )}
                            {/* <h5 className="text-center mt-1">
                                    {eventTitle}
                                </h5> */}
                            <h2 className="text-center mt-1">
                                Best Sports Achievement{" "}
                            </h2>

                            <div className="col-md-12 ms-4">
                                <div className="form-check p-0">
                                    <input
                                        className="form-check-input"
                                        type="checkbox"
                                        id="physical_disability"
                                        name="physical_disability"
                                        checked={
                                            formData.physical_disability === "1"
                                        }
                                        onChange={(e) => {
                                            handleSportsDiscChanges({
                                                ...e,
                                                target: {
                                                    ...e.target,
                                                    name: "physical_disability",
                                                    value: e.target.checked
                                                        ? "1"
                                                        : "2",
                                                },
                                            } as any);
                                            handlePhysicalDisability(e.target);
                                        }}
                                    />
                                    <label
                                        className="form-check-label lb"
                                        htmlFor="physical_disability"
                                    >
                                        Physical Disability
                                    </label>
                                </div>
                            </div>

                            {formData.physical_disability === "1" && (
                                <>
                                    <div className="col-md-6">
                                        <label>Disability Type</label>
                                        <select
                                            className={`form-select ${
                                                sportdiserrors.disability_type_id
                                                    ? "is-invalid"
                                                    : ""
                                            }`}
                                            name="disability_type_id"
                                            value={formData.disability_type_id}
                                            onChange={handleSportsDiscChanges}
                                        >
                                            <option value="" selected disabled>
                                                Select
                                            </option>
                                            <option value="1">Para</option>
                                            <option value="2">Blind</option>
                                            <option value="3">Deaf</option>
                                            <option value="4">
                                                Special Olympic Sports
                                            </option>
                                        </select>
                                        <div className="invalid-feedback">
                                            {sportdiserrors.disability_type_id}
                                        </div>
                                    </div>

                                    <div className="col-md-6">
                                        <label>
                                            Attach Disability Certificate
                                        </label>
                                        <input
                                            type="file"
                                            name="disability_doc"
                                            accept="application/pdf"
                                            className={`form-control ${
                                                sportdiserrors.disability_doc
                                                    ? "is-invalid"
                                                    : ""
                                            }`}
                                            onChange={handleSportsDiscChanges}
                                        />
                                        <div className="invalid-feedback">
                                            {sportdiserrors.disability_doc}
                                        </div>
                                        {formData.disability_doc && (
                                            <div className="mt-1">
                                                <a
                                                    href={`/storage/certificates/${encodeURIComponent(
                                                        formData.disability_doc
                                                    )}`}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                >
                                                    View Uploaded Certificate
                                                </a>
                                            </div>
                                        )}
                                    </div>
                                </>
                            )}
                            <div className="col-md-6">
                                <label>Event Type</label>
                                <select
                                    name="event_type"
                                    value={formData.event_type}
                                    className={`form-select ${
                                        sportdiserrors.event_type
                                            ? "is-invalid"
                                            : ""
                                    }`}
                                    onChange={(e) => {
                                        fetchTournamentList(e.target.value),
                                            handleSportsDiscChanges(e);
                                    }}
                                >
                                    <option value="" selected disabled>
                                        Select
                                    </option>
                                    <option value="1">Individual Event</option>
                                    <option value="2">Team Event</option>
                                </select>
                                <div className="invalid-feedback">
                                    {sportdiserrors.event_type}
                                </div>
                            </div>
                            <div className="col-md-6">
                                <label>Name of Sports Discipline</label>
                                <select
                                    name="game_id"
                                    value={formData.game_id}
                                    className={`form-select ${
                                        sportdiserrors.game_id
                                            ? "is-invalid"
                                            : ""
                                    }`}
                                    onChange={(e) => {
                                        handleTournamentLevel(e.target.value);
                                        handleSportsDiscChanges(e);
                                    }}
                                >
                                    <option value="" selected disabled>
                                        Select
                                    </option>
                                    {gamesList
                                        .filter((game: any) =>
                                            formData?.physical_disability ===
                                            "1"
                                                ? game.is_para === 1
                                                : game.is_para === 0
                                        )
                                        .map((game: any) => (
                                            <option
                                                key={game.id}
                                                value={game.id}
                                            >
                                                {game.name}
                                            </option>
                                        ))}
                                </select>
                                <div className="invalid-feedback">
                                    {sportdiserrors.game_id}
                                </div>
                            </div>
                            {/* <div className="col-md-6"></div> */}
                            <div className="col-md-6">
                                <label>Name of Tournament</label>
                                <select
                                    name="tournament_id"
                                    value={formData.tournament_id}
                                    className={`form-select ${
                                        sportdiserrors.tournament_id
                                            ? "is-invalid"
                                            : ""
                                    }`}
                                    onChange={(e) => {
                                        handleSportsDiscChanges(e);
                                        setOrganizationCommittee(e);
                                    }}
                                >
                                    <option value="" selected disabled>
                                        Select
                                    </option>
                                    {tournamentList.map((item: any) => (
                                        <option key={item.id} value={item.id}>
                                            {item.tournament}
                                        </option>
                                    ))}
                                </select>
                                <div className="invalid-feedback">
                                    {sportdiserrors.tournament_id}
                                </div>
                            </div>

                            <div className="col-md-6">
                                <label>Organizing Authority</label>
                                <input
                                    type="text"
                                    name="organizing_committee"
                                    value={formData.organizing_committee}
                                    className={`form-control ${
                                        sportdiserrors.organizing_committee
                                            ? "is-invalid"
                                            : ""
                                    }`}
                                    readOnly
                                    onChange={(e) => {
                                        e.preventDefault(); // just in case
                                        return false;
                                    }}
                                />
                                <div className="invalid-feedback">
                                    {sportdiserrors.organizing_committee}
                                </div>
                            </div>

                            <div className="col-md-6">
                                <label>Level of Tournament</label>
                                <select
                                    name="tournament_level"
                                    value={formData.tournament_level}
                                    className={`form-select ${
                                        sportdiserrors.tournament_level
                                            ? "is-invalid"
                                            : ""
                                    }`}
                                    onChange={(e) => {
                                        handleSportsDiscChanges(e);
                                        handleTournamentLevel(e.target.value);
                                    }}
                                >
                                    <option value="" selected disabled>
                                        Select
                                    </option>
                                    <option value="1">National</option>
                                    <option value="2">International</option>
                                </select>
                                <div className="invalid-feedback">
                                    {sportdiserrors.tournament_level}
                                </div>
                            </div>

                            <div className="col-md-6">
                                <label>
                                    Represented India in any sports tournament
                                    (Schedule-I and Schedule-II)
                                </label>
                                <select
                                    name="represented_india"
                                    value={formData.represented_india}
                                    className={`form-select ${
                                        sportdiserrors.represented_india
                                            ? "is-invalid"
                                            : ""
                                    }`}
                                    onChange={(e) => {
                                        handleRepresentedIndia(e.target.value);
                                        handleSportsDiscChanges(e);
                                    }}
                                    disabled={tournament_level == "2"}
                                >
                                    <option value="" selected disabled>
                                        Select
                                    </option>
                                    <option value="1">Yes</option>
                                    <option value="2">No</option>
                                </select>
                                <div
                                    className="text-danger"
                                    hidden={represented_india != "2"}
                                >
                                    You are Ineligibile
                                </div>
                                <div className="invalid-feedback">
                                    {sportdiserrors.represented_india}
                                </div>
                            </div>

                            <div className="col-md-6">
                                <label>Achievement Date(Month & Year)</label>
                                <input
                                    type="date"
                                    name="achievement_date"
                                    value={formData.achievement_date}
                                    className={`form-control ${
                                        sportdiserrors.achievement_date
                                            ? "is-invalid"
                                            : ""
                                    }`}
                                    max={new Date().toISOString().split("T")[0]} // 👈 correct format: YYYY-MM-DD
                                    onChange={handleSportsDiscChanges}
                                    onClick={(e) =>
                                        e.target.showPicker &&
                                        e.target.showPicker()
                                    }
                                />
                                <div className="invalid-feedback">
                                    {sportdiserrors.achievement_date}
                                </div>
                            </div>

                            <div className="col-md-6">
                                <label>
                                    Tournament Venue (Type as per in your
                                    Achievement Certificate)
                                </label>
                                <input
                                    type="text"
                                    name="tournament_venue"
                                    value={formData.tournament_venue}
                                    className={`form-control ${
                                        sportdiserrors.tournament_venue
                                            ? "is-invalid"
                                            : ""
                                    }`}
                                    onChange={handleSportsDiscChanges}
                                />
                                <div className="invalid-feedback">
                                    {sportdiserrors.tournament_venue}
                                </div>
                            </div>

                            <div className="col-md-6">
                                <label>Medal won(if any)</label>
                                <select
                                    name="medal_won"
                                    value={formData.medal_won}
                                    className={`form-select ${
                                        sportdiserrors.medal_won
                                            ? "is-invalid"
                                            : ""
                                    }`}
                                    onChange={handleSportsDiscChanges}
                                >
                                    <option value="" selected disabled>
                                        Select
                                    </option>
                                    <option value="gold">Gold</option>
                                    <option value="silver">Silver</option>
                                    <option value="bronze">Bronze</option>
                                    <option value="participation">
                                        Participation
                                    </option>
                                </select>
                                {/* <div className="invalid-feedback">
                                    {sportdiserrors.medal_won}
                                </div> */}
                            </div>
                            {eventTitle === "Team Event" && (
                                <div className="col-md-6">
                                    <label>
                                        Total number of matches played by team
                                        in the tournament
                                    </label>
                                    <input
                                        type="number"
                                        name="match_played_by_team"
                                        value={formData.match_played_by_team}
                                        className={`form-control ${
                                            sportdiserrors.match_played_by_team
                                                ? "is-invalid"
                                                : ""
                                        }`}
                                        onChange={handleSportsDiscChanges}
                                        maxLength={3}
                                    />
                                    <div className="invalid-feedback">
                                        {sportdiserrors.match_played_by_team}
                                    </div>
                                </div>
                            )}
                            {eventTitle === "Team Event" && (
                                <div className="col-md-6">
                                    <label>
                                        {" "}
                                        Number of matches played by me in the
                                        tournament
                                    </label>
                                    <input
                                        type="number"
                                        name="match_played_by_me"
                                        value={formData.match_played_by_me}
                                        className={`form-control ${
                                            sportdiserrors.match_played_by_me
                                                ? "is-invalid"
                                                : ""
                                        }`}
                                        onChange={handleSportsDiscChanges}
                                        maxLength={3}
                                    />
                                    <div className="invalid-feedback">
                                        {sportdiserrors.match_played_by_me}
                                    </div>
                                </div>
                            )}

                            {/* <div className="col-md-6">
                                <label>
                                    Participation Level (in case of team game
                                    only)
                                </label>
                                <select
                                    name="participation_level"
                                    value={formData.participation_level}
                                    className={`form-select ${
                                        sportdiserrors.participation_level
                                            ? "is-invalid"
                                            : ""
                                    }`}
                                    onChange={handleSportsDiscChanges}
                                >
                                    <option value="" selected disabled>
                                        Select
                                    </option>
                                    <option value="25_plus">25% or more</option>
                                    <option value="less_25">
                                        Less than 25%
                                    </option>
                                </select>
                                <div className="invalid-feedback">
                                    {sportdiserrors.participation_level}
                                </div>
                            </div> */}
                            <div className="col-md-6">
                                <label>
                                    Attach Sports Achievment Certificates (pdf)
                                </label>
                                <br />

                                <input
                                    type="file"
                                    name="osp_achivement_certificate_path"
                                    accept="application/pdf"
                                    className={`form-control ${
                                        sportdiserrors.osp_achivement_certificate_path
                                            ? "is-invalid"
                                            : ""
                                    }`}
                                    onChange={handleSportsDiscChanges}
                                />
                                <div className="invalid-feedback">
                                    {
                                        sportdiserrors.osp_achivement_certificate_path
                                    }
                                </div>

                                {formData.osp_achivement_certificate_path && (
                                    <div className="mt-1">
                                        <a
                                            href={`/storage/certificates/${encodeURIComponent(
                                                formData.osp_achivement_certificate_path
                                            )}`}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            View Uploaded Certificate
                                        </a>
                                    </div>
                                )}
                            </div>
                            {/* <div className="col-md-6">
                                <label>
                                    Attach International Achievement and
                                    Verification Certificate (pdf){" "}
                                </label>
                                <br />

                                <input
                                    type="file"
                                    name="international_achievement_Verification_certificate_path"
                                    accept="application/pdf"
                                    className={`form-control ${
                                        sportdiserrors.international_achievement_Verification_certificate_path
                                            ? "is-invalid"
                                            : ""
                                    }`}
                                    onChange={handleSportsDiscChanges}
                                />
                                <div className="invalid-feedback">
                                    {
                                        sportdiserrors.international_achievement_Verification_certificate_path
                                    }
                                </div>

                                {formData.international_achievement_Verification_certificate_path && (
                                    <div className="mt-1">
                                        <a
                                            href={`/api/certificates/${encodeURIComponent(
                                                formData.international_achievement_Verification_certificate_path
                                            )}/certificates`}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            View Uploaded Certificate
                                        </a>
                                    </div>
                                )}
                            </div> */}
                            <hr />

                            <div className="col-12 text-end mt-1">
                                {currentStep > 1 && (
                                    <button
                                        type="button"
                                        onClick={prevStep}
                                        className="btn btn-secondary m-2"
                                    >
                                        Previous
                                    </button>
                                )}
                                <button
                                    className="btn btn-primary"
                                    type="submit"
                                    disabled={isbtnDisabled}
                                >
                                    Next
                                </button>
                            </div>
                        </form>
                        {/* <form
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
                        </form> */}

                        <form
                            className="needs-validation row g-3"
                            hidden={currentStep === 4 ? false : true}
                            onSubmit={handleSubmit(handleDeclarationSubmit)}
                        >
                            <h3 className="text-center mt-5">
                                Declaration by Sportsperson
                            </h3>
                            <div className="">
                                {/* <p className="text-center mb-4">Declaration</p> */}
                                <div className="row">
                                    <div className="col-md-12 mb-3">
                                        {/* <p>Declaration by Sportsperson</p> */}
                                        <ol className="declaration-list">
                                        {declarationList.map((label) => (
                                                <li  className=""> {label} </li>
                                           
                                        ))}
                                         </ol>

                                        <div className="form-check mt-3">
                                            <input
                                                className="form-check-input"
                                                type="checkbox"
                                                id="acceptAll"
                                                checked={acceptAll}
                                                onChange={handleAcceptAll}
                                            />
                                            <label
                                                className="form-check-label lb"
                                                htmlFor="acceptAll"
                                            >
                                                Accept all
                                            </label>
                                        </div>
                                    </div>

                                   
                                </div>
                            </div>
                            <hr />
                            <div className="row mt-1 align-items-end">
                                <div className="d-flex col-8 align-items-end">
                            <div
                                        className=""
                                        hidden={currentStep !== 4}
                                    >
                                        <button
                                            className="btn btn-primary"
                                            onClick={(e) =>
                                                handlePrintDeclaration(e)
                                            }
                                        >
                                            Download Unsigned Application Form <i className="fa fa-print"></i>
                                        </button>
                                    </div>
                                    <div className="ms-3">
                                        <label>
                                            Upload Signed Application Form
                                        </label>
                                        <input
                                            type="file"
                                            accept="application/pdf"
                                            className="form-control"
                                            onChange={
                                                handleDeclarationFileChange
                                            }
                                        />
                                         <div className="text-danger">
                                        {diclarationerrors.msg}
                                    </div>
                                    </div></div>
                                {currentStep === stepsTotal && (
                                    
                                    <div className="col-md-4 text-end">
                                        <button
                                            type="button"
                                            onClick={prevStep}
                                            className="btn btn-secondary m-2"
                                        >
                                            Previous
                                        </button>
                                        <button
                                            id="submit-btn"
                                            type="submit"
                                            className="btn btn-success"
                                           disabled={isbtnDisabled}
                                        >
                                            Submit
                                        </button>
                                    </div>
                                )}
                            </div>
                        </form>
                    </div>
                )}

                {isSubmitted && (
                    <div className="text-center p-4 " id="print-section"  >
                        <i className="fa-solid fa-circle-check text-success"></i>

                        <h3 className="text-success">Successfully submitted!</h3>
                        <p>Application ID: {userDetails?.application_id}</p>
                        <Link className="btn btn-primary me-1 fs-5 px-4 py-2" to="/hosp/preview-application" ><i className="fa-solid fa-eye"></i> Preview Form
                        </Link>
                        <Link className="btn btn-success me-1 fs-5 px-4 py-2" to="/hosp/login"><i className="fa-solid fa-house"></i> Go To Home</Link> 
                           
                    </div>
                )}
            </div>

            <div id="preloader-wrapper">
                <div id="preloader"></div>
                <div className="preloader-section section-left"></div>
                <div className="preloader-section section-right"></div>
            </div>
            {/* print declaration */}
            <div id="print_declaration" style={{ display: "none", padding: "0", margin: "0" }}>
                <table width="100%" style={{pageBreakInside: "avoid"}}>
                    <thead style={{ background: "#4831d4", display: "table-header-group"}}>
                        <tr style={{   }}>
                            <th style={{ padding: "10px" }}>
                                <div className="logo " style={{display: "flex", alignItems:"center" }}>
                                    <img  id="logo" src="../assets/images/logo-sports.png" alt="Sports Haryana Govt"
                                    />
                                    <div className="logo_text ms-3">
                                        <h1 className="h1-logo text-white mb-0" style={{ fontSize: "24px" }}>
                                            Sports Department<br />
                                            <small style={{ fontSize: "15px" }}>Let the young minds grow to the full potential</small>
                                        </h1>
                                    </div>
                                    
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td align="center" style={{ paddingTop: "30px" }}>
                                <h3 style={{ fontSize: "20px" }}>Haryana Outstanding Sportspersons Application</h3>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" style={{ 
                                        border: "1px solid #efefef",background: "#fff",
                                        tableLayout:"fixed",}}>
                                    <tr>
                                        <td colSpan={5}>
                                            <h3 className="modal-title-details"><i className="fa-solid fa-user"></i> Basic Details</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style={{ padding: "3px 7px",fontSize: "14px"}}>
                                            1. Parivar Pehchan Patra ID
                                            <h5 style={{fontSize: "15px"}}>{userDetails.family_id}</h5>
                                        </td>
                                        <td style={{ padding: "3px 7px",fontSize: "14px"}}>
                                            2. Name
                                            <h5 style={{fontSize: "15px"}}>{userData.name}</h5>
                                        </td>
                                        <td  style={{ padding: "3px 7px",fontSize: "14px"}} >
                                            3. Caste Category
                                            <h5 style={{fontSize: "15px"}}>
                                                {userDetails.caste_category} <a href="javascript:;" className="text-dark" target="_blank"><i className="fa-solid fa-paperclip"></i></a>
                                            </h5>
                                        </td>
                                        <td style={{ padding: "3px 7px",fontSize: "14px"}}>
                                            4. Date of Birth
                                            <h5 style={{fontSize: "15px"}}>
                                                {userDetails.date_of_birth} 

                                                {userDetails.dob_doc &&
                                                      <a href="javascript:;" className="text-dark" target="_blank"><i className="fa-solid fa-paperclip"></i></a>
                                                    }
                                            </h5>
                                        </td>
                                        <td  style={{ padding: "3px 7px",fontSize: "14px"}}>
                                            5. Age
                                            <h5 style={{fontSize: "15px"}}>{userDetails.age}</h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style={{ padding: "3px 7px",fontSize: "14px"}}>
                                            6. Aadhar No.
                                            <h5 style={{fontSize: "15px"}}>{userDetails.aadhaar}</h5>
                                        </td>
                                         
                                        <td style={{ padding: "3px 7px",fontSize: "14px"}}>
                                            7. Mobile
                                            <h5 style={{fontSize: "15px"}}>{userData.mobile}</h5>
                                        </td>
                                        <td style={{ padding: "3px 7px",fontSize: "14px"}}>
                                            8. Email ID
                                            <h5 style={{fontSize: "15px"}}>{userData.email}</h5>
                                        </td>
                                       
                                    <td   style={{ padding: "3px 7px",fontSize: "14px"}}>
                                            9. Haryana Domicle
                                            <h5 style={{fontSize: "15px"}}>
                                                {userDetails.domicile == "1"
                                                    ? "Yes "
                                                    : "No "}
                                                     {userDetails.domicile_doc &&
                                                     <a href="javascript:;" className="text-dark" target="_blank"><i className="fa-solid fa-paperclip"></i></a>
                                                    }
                                            </h5>
                                        </td>
                                        <td   style={{ padding: "3px 7px",fontSize: "14px"}}>
                                            10. Played National Level
                                            <h5 style={{fontSize: "15px"}}>
                                                {userDetails.played_national_level == "1"
                                                    ? "Yes "
                                                    : "No "}
                                                    {userDetails.national_level_doc &&
                                                     <a href="javascript:;" className="text-dark" target="_blank"><i className="fa-solid fa-paperclip"></i></a>
                                                    }
                                            </h5>
                                        </td>
                                    {userDetails.organisation_doc && userDetails.played_national_level == "2" &&
                                        <td  style={{ padding: "3px 7px",fontSize: "14px"}}>
                                            11. Organisation represented
                                            <h5 style={{fontSize: "15px"}}>
                                                {userDetails.organisation_represented}
                                                     {userDetails.organisation_doc &&
                                                     <a href="javascript:;" className="text-dark" target="_blank"> <i className="fa-solid fa-paperclip"></i></a>
                                                    }
                                            </h5>
                                        </td>
                                         } 
                                    </tr>
                                         
                            
                                </table>
                            </td>
                        </tr>
                        
                       
                       
                        
                        {userData.education_hosp.length && (
                            <tr>
                                <td style={{paddingTop: "20px"}}>
                                    <h3 className="modal-title-details"><i className="fa-solid fa-user-graduate"></i> Educational  Qualifications</h3> 
                                </td>
                            </tr>
                        )}
                        {userData.education_hosp.map( (item, index) => (
                            <tr key={item.id || index}>
                                <td >
                                    <table width="100%" style={{ 
                                        border: "1px solid #efefef",background: "#fff",
                                        tableLayout:"fixed",}}>
                                        <tr>
                                            
                                            {!item.other_qualification && (
                                                <>
                                            <td style={{ padding: "3px 7px",fontSize: "14px" }}>{item.qualification}</td>
                                            <td style={{ padding: "3px 7px",fontSize: "14px"}}>{item.certificate_path
                                            ? "Attached doc"
                                            : "No Attachment"} <i className='fa-solid fa-paperclip'></i></td>
                                            </>
                                            )}
                                            {item.other_qualification && (
                                                 <>
                                              
                                                <td style={{ padding: "3px 7px",fontSize: "14px"}}>{item.other_qualification ||
                                                    "N/A"} (Other)</td>
                                                    </>
                                            )}
                                        
                                            {item.other_qualification && (
                                            
                                                <td style={{ padding: "3px 7px",fontSize: "14px"}}> {item.certificate_path
                                            ? "Attached doc"
                                            : "No Attachment"} <i className='fa-solid fa-paperclip'></i></td>
                                            )}
                                            
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        ))}
                        <tr>
                            <td style={{paddingTop: "20px"}}>
                                <h3 className="modal-title-details"><i className="fa-solid fa-trophy"></i> Best Sports Achievement</h3> 
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" style={{ 
                                    border: "1px solid #efefef",background: "#fff",
                                    tableLayout:"fixed",}}>
                                        {userData.sports_discipline_hosp && (
                                        <tr>
                                            <td style={{ padding: "3px 7px",fontSize: "14px"}}> Physical Disability
                                                <h5 style={{fontSize: "15px"}}>
                                                    {userData
                                                        .sports_discipline_hosp
                                                        .physical_disability ===
                                                    1
                                                        ? "Yes"
                                                        : userData
                                                                .sports_discipline_hosp
                                                                .physical_disability ===
                                                            2
                                                        ? "No"
                                                        : "N/A"}
                                                </h5>
                                            </td>
                                            {userData.sports_discipline_hosp.physical_disability == 1 &&
                                           
                                                <td style={{ padding: "3px 7px",fontSize: "14px"}}> Physical Disability Type
                                                    <h5 style={{fontSize: "15px"}}>
                                                        {userData
                                                            .sports_discipline_hosp.disablility_type  && userData
                                                            .sports_discipline_hosp
                                                            .disablility_type.type}
                                                            {userData
                                                        .sports_discipline_hosp
                                                        .disability_doc && (
                                                            <a href="javascript:;" className="text-dark" target="_blank"> <i className="fa-solid fa-paperclip"></i></a>
                                                            )}
                                                    </h5>
                                                </td>
                                                
                                            
                                            }  
                                             <td style={{ padding: "3px 7px",fontSize: "14px"}}>
                                                   Event Type{" "}
                                                    <h5 style={{fontSize: "15px"}}>
                                                    {
                                                        userData
                                                            .sports_discipline_hosp
                                                            .event_type == '1' ? 'Individual' : 'Team'
                                                    }
                                                </h5>
                                            </td>         
                                            <td style={{ padding: "3px 7px",fontSize: "14px"}}>
                                                    Sports Discipline{" "}
                                                    <h5 style={{fontSize: "15px"}}>
                                                    {userData
                                                            .sports_discipline_hosp.game  &&
                                                        userData
                                                            .sports_discipline_hosp
                                                            .game.name
                                                    }
                                                </h5>
                                            </td>
                                            <td style={{ padding: "3px 7px",fontSize: "14px"}}>
                                            Name of Tournament{" "}
                                                    <h5 style={{fontSize: "15px"}}>
                                                    {userData
                                                            .sports_discipline_hosp.tournament  &&
                                                        userData
                                                            .sports_discipline_hosp
                                                            .tournament.tournament
                                                    }
                                                </h5>
                                            </td>
                                           
                                            
                                        </tr> 
                                        )} 
                                         {userData.sports_discipline_hosp && (
                                        <tr>
                                             <td colSpan={2} style={{ padding: "3px 7px",fontSize: "14px"}}>
                                                Organizing Authority
                                                <h5 style={{fontSize: "15px"}}>
                                                    {
                                                        userData
                                                            .sports_discipline_hosp
                                                            .organizing_committee
                                                    }
                                                </h5>
                                            </td>
                                             <td style={{ padding: "3px 7px",fontSize: "14px"}}>
                                                Tournament_level
                                                <h5 style={{fontSize: "15px"}}>
                                                    {userData
                                                        .sports_discipline_hosp
                                                        .tournament_level ===
                                                    1
                                                        ? "National"
                                                        : "International"}
                                                </h5>
                                            </td>
                                            <td style={{ padding: "3px 7px",fontSize: "14px"}}>
                                                Represented India
                                                <h5 style={{fontSize: "15px"}}>
                                                    {userData
                                                        .sports_discipline_hosp
                                                        .represented_india ===
                                                    1
                                                        ? "Yes"
                                                        : "No"}
                                                </h5>
                                            </td>
                                        
                                            <td style={{ padding: "3px 7px",fontSize: "14px"}}>
                                                Medal Won
                                                <h5 style={{fontSize: "15px"}}>
                                                    {userData
                                                        .sports_discipline_hosp
                                                        .medal_won ||
                                                        "N/A"}
                                                </h5>
                                            </td></tr>
                                         )}
                                        {userData.sports_discipline_hosp && (
                                        <tr>
                                            <td style={{ padding: "3px 7px",fontSize: "14px"}}>
                                                Tournament Venue
                                                <h5 style={{fontSize: "15px"}}>
                                                    {
                                                        userData
                                                            .sports_discipline_hosp
                                                            .tournament_venue
                                                    }
                                                </h5>
                                            </td>
                                            {
                                                        userData
                                                            .sports_discipline_hosp.event_type == '2' && (

                                                <>           
                                            <td style={{ padding: "3px 7px",fontSize: "14px"}}>
                                            Matches played by team in the tournament
                                                <h5 style={{fontSize: "15px"}}>
                                                    {
                                                        userData
                                                            .sports_discipline_hosp
                                                            .match_played_by_team
                                                    }
                                                </h5>
                                            </td>
                                            <td style={{ padding: "3px 7px",fontSize: "14px"}}>
                                           Matches played by me in the tournament
                                                <h5 style={{fontSize: "15px"}}>
                                                    {
                                                        userData
                                                            .sports_discipline_hosp
                                                            .match_played_by_me
                                                    }
                                                </h5>
                                            </td>
                                            </> 
                                            )}
                                        
                                           <td style={{ padding: "3px 7px",fontSize: "14px"}}>
                                           Achievement Date
                                                <h5 style={{fontSize: "15px"}}>
                                                    {
                                                        userData
                                                            .sports_discipline_hosp
                                                            .achievement_date
                                                    }
                                                </h5>
                                            </td>
                                                <td  style={{ padding: "3px 7px",fontSize: "14px"}}>
                                                Sports Achievment Certificates
                                                <h5 style={{fontSize: "15px"}}>
                                                    {userData
                                                        .sports_discipline_hosp
                                                        .osp_achivement_certificate_path
                                                        ? "Attached doc "
                                                        : "No Attachment"}
                                                        <i className='fa-solid fa-paperclip'></i>
                                                </h5>
                                            </td>

                                            {/* <td style={{ padding: "3px 7px"}}>
                                                International Certificate
                                                <h5 style={{fontSize: "15px"}}>
                                                    {userData
                                                        .sports_discipline_hosp
                                                        .international_achievement_Verification_certificate_path
                                                        ? "(Attached doc)"
                                                        : "(No Attachment)"}
                                                </h5>
                                            </td> */}
                                        </tr>
                                            )}
                                </table>

                            </td>
                        </tr>
                       
                        
                         <tr style={{ padding: "60px 0 0" }}>
                            <td>
                                <table width="100%">
                                    <tr>
                                        <td style={{ padding: "0 10px" }} >
                                            <strong>Date -</strong> <u> </u>
                                        </td>
                                        <td
                                            align="right" 
                                            style={{
                                                padding: "40px 10px 20px",
                                                textAlign: "right",
                                            }}
                                        >
                                            <strong>
                                                (Signature of Sportsperson)
                                            </strong>
                                        </td>

                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td  >
                                <div style={{pageBreakAfter:"always"}}></div>
                            </td>
                        </tr>
                        <tr>
                            <td style={{padding: "50px 10px 0 30px"}}>
                                <h3  style={{ textAlign: "center",fontSize: "20px", marginBottom:"20px"  }}  >
                                    DECLARATION BY SPORTS PERSON
                                </h3>
                                <ol type="1" style={{padding: "0", margin :"0"}} className="declaration-list">
                                        {declarationList.map((label) => (
                                                <li style={{ textAlign:"justify",fontSize: "14px"}} className=""> {label} </li>
                                           
                                        ))}
                                         </ol>
                               
                                <p  style={{ padding: "15px 5px",fontSize: "14px" }} >
                                    It is certified that the above particulars given by me are true and correct to the best of my knowledge and  record and there is no  martial concealment . In  case of any wrong information furnished or material concealment, my service may be terminated without notice.
                                </p>
                            </td>
                        </tr>
                        <tr style={{ padding: "20px 0 0" }}>
                            <td style={{ padding: "0 20px",fontSize: "14px" }} >
                                <table width="100%">
                                    <tr>
                                        <td><strong>Date -</strong> <u> </u></td>
                                        <td
                                            align="right"
                                            style={{
                                                padding: "40px 20px 20px",
                                                textAlign: "right",fontSize: "14px"
                                            }}
                                            
                                        >
                                            <strong>
                                                (Signature of Sportsperson)
                                            </strong>
                                        </td>
                                    </tr>
                                </table>
                            </td> 
                        </tr>


                        <tr>
                            <td  >
                                <div style={{pageBreakAfter:"always"}}></div>
                            </td>
                        </tr>
                        <tr>
                          <td style={{padding: "50px 10px 0 30px",fontSize: "14px"}}>
                                <h3  style={{ textAlign: "center",fontSize: "20px", marginBottom:"20px"  }}  >
                                    VERIFICATION BY NATIONAL SPORTS FEDERATION
                                </h3>
                                
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%">
                                    <tr>
                                        <td style={{padding:"10px 10px 10px 10px",fontSize: "14px"}}>Name : {userDetails.full_name_en}</td>
                                        <td style={{padding:"10px",fontSize: "14px"}} >Father's Name : {userDetails.father_name_en}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td >
                                <table width="98%" style={{margin:"0 auto"}}>
                                    <tr className="bg-light text-dark">
                                        <th style={{padding:"10px 6px", border:"1px solid #eee", fontSize:"14px"}}>Sr. No.</th>
                                        <th style={{padding:"10px", border:"1px solid #eee", fontSize:"14px"}}>Date of Achievement</th>
                                        <th style={{padding:"10px", border:"1px solid #eee", fontSize:"14px"}}>Name of Tournament</th>
                                        <th style={{padding:"10px", border:"1px solid #eee", fontSize:"14px"}}>Organising Authority</th>
                                        <th style={{padding:"10px", border:"1px solid #eee", fontSize:"14px"}}>Sports Discipline</th>
                                        <th style={{padding:"10px", border:"1px solid #eee", fontSize:"14px"}}>Sports Federation</th>
                                        <th style={{padding:"10px", border:"1px solid #eee", fontSize:"14px", width:"150px"}}>Medal Won (If Any)</th>
                                    </tr>
                                    {userData.sports_discipline_hosp && (
                                    <tr>
                                        <td style={{padding:"10px", border:"1px solid #eee", fontSize:"14px"}}>1</td>
                                        <td style={{padding:"10px", border:"1px solid #eee", fontSize:"14px"}}>{userData.sports_discipline_hosp.achievement_date}</td>
                                        <td style={{padding:"10px", border:"1px solid #eee", fontSize:"14px"}}>{userData.sports_discipline_hosp.tournament.tournament} </td>
                                        <td style={{padding:"10px", border:"1px solid #eee", fontSize:"14px"}}>{userData.sports_discipline_hosp.organizing_committee} </td>
                                        <td style={{padding:"10px", border:"1px solid #eee", fontSize:"14px"}}>{userData.sports_discipline_hosp.game.name}</td>
                                        <td style={{padding:"10px", border:"1px solid #eee", fontSize:"14px"}}>{userData.sports_discipline_hosp.tournament.organizing_authority_abbr}</td>
                                        <td style={{padding:"10px", border:"1px solid #eee", fontSize:"14px"}}>{userData.sports_discipline_hosp.medal_won ?? 'N/A' }</td>
                                    </tr>
                                    )}
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style={{padding: "15px 10px 0",fontSize: "14px"}}><p>
                                    Certified that the particulars declared by the sportsperson have been checked, verified and found correct.
                                </p></td>
                        </tr>

                        <tr>
                            <td>
                                <table width="100%">
                                    <td style={{ padding: "0 20px",fontSize: "14px" }} >
                                        <strong>Date -</strong> <u> </u>
                                    </td>
                                    <td align="right"  style={{  padding: "30px 20px 20px",fontSize: "14px", textAlign: "right", }} >
                                        <strong>
                                            (Signature and Seal of the
                                            Secretary/President
                                            <br />
                                            of National Sports
                                            Federation concerned)
                                        </strong>
                                    </td>
                                </table>
                            </td>
                        </tr>
                                    
                    </tbody>
                </table>
            </div>
        </div>
    );
};

export default HospForm;
