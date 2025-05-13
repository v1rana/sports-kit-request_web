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
    const step: any = params.get("step") ? parseInt(params.get("step")!) : 1;
    // alert(step)
    const [currentStep, setCurrentStep] = useState(step);
    const [physical_disability, setPhysicalDisability] = useState(false);
    const [represented_india, setRepresentedIndia] = useState("1");
    const [tournament_level, setTournamentLevel] = useState("2");
    const [isSubmitting, setIsSubmitting] = useState(false);
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
        match_played_by_team: "",
        match_played_by_me: "",
        osp_achivement_certificate_path: null,
        international_achievement_Verification_certificate_path: null,
    });

    const defaultDeclarations = [
        "1. I have read the Haryana Outstanding Sportspersons (Recruitment and Condition of Service) Rules, 2021 and declare that I am eligible for submission of my application for consideration of appointment under these Rules.",
        "2. I have enclosed self-attested copies of all documents in support of my application.",
        "3. I have played in 50% or more of the games played by team in the tournament at serial No.12 above.",
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
                    disability_doc: data.certificate_path ?? null, // We can't prefill file inputs
                    tournament_id: String(data.tournament_id ?? ""),
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
        fetchEventData();
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
        const response = await fetchSchedule12Listing(event_type);
        if (response.status === "success") {
            setTournamentList(response.list);
        }
        console.log("tournament listing", response.list);
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

    const finalSubmit = async () => {
        document.body.classList.add("loaded");
        setIsSubmitting(true);
        await new Promise((res) => setTimeout(res, 1000));
        // setCurrentStep((nxt) => nxt + 1);
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
            const response = await saveDeclarations(formData);
            finalSubmit();
        } catch (error) {
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

    const handlePrintDeclaration = () => {
        const content: any = document.getElementById("print_declaration");
        const printWindow: any = window.open("", "", "width=800,height=600");
        printWindow.document.write(`
         <!DOCTYPE html>
<html>

<head>
    <title>Apply Certificate Form - Sports Haryana </title>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta name="keywords" content=""> 
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	
	<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">


	<style>
		@page{orientation: A4;margin:0; padding:0}		
		body{background:#fafafc;margin:0;padding:0;}
		h1,h2,h3,h4,h5,h6{font-family: "Jost", sans-serif;color:#5f788a; margin:0}
		a,p,li,td{text-decoration:none; font-family: "Noto Sans", sans-serif;}
		.logo a{display:flex; align-items:center}
		.logo img{display: inline-block;vertical-align: middle;margin: 0 4px 0 0;max-width:70px; width:100%}
		td h4{font-size: 30px;}
		td h5{font-size: 20px;margin:0}
		.logo h1{color:#fff; font-size:26px; padding-left:10px}
		footer{background:#2f4858; color:#fff;width:100%;bottom:0;padding:10px 0; font-size:12px; left:0}
		footer p{margin:0}
		ol {margin:0; padding:0}
		ol li{padding: 0 10px 20px;margin-left:30px}
		@media print{
			* { -webkit-print-color-adjust: exact !important; color-adjust: exact !important;print-color-adjust: exact !important;}
			table tbody table{width: 90% !important}
		}
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
    const onEducationSubmit = async () => {
        const hasValidEntry = educationFields.some(
            (field: any) =>
                field.qualification &&
                (field.qualification !== "other" || field.otherText) &&
                (field.certificate || field.fileUrl)
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
        educationFields.forEach((field, index) => {
            formData.append(
                `educations[${index}][qualification]`,
                field.qualification
            );
            formData.append(
                `educations[${index}][otherText]`,
                field.otherText || ""
            );
            if (field.certificate) {
                formData.append(
                    `educations[${index}][certificate]`,
                    field.certificate
                );
            }
        });

        try {
            const response = await saveEducation(formData);

            //   const response = await axios.post("/api/education/store", formData, {
            //     headers: { "Content-Type": "multipart/form-data" },
            //   });

            //   setCurrentStep((prev) => prev + 1);
            if (currentStep < stepsTotal) setCurrentStep((nxt) => nxt + 1);
            navigate({
                pathname: location.pathname, // or keep current path
                search: createSearchParams({
                    step: currentStep + 1,
                }).toString(),
            });
        } catch (error) {
            console.error(
                "Save failed:",
                error.response?.data || error.message
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
        if (!formData.international_achievement_Verification_certificate_path)
            newErrors.international_achievement_Verification_certificate_path =
                "Required";
        if (!formData.achievement_date) newErrors.achievement_date = "Required";
        if (!formData.tournament_venue) newErrors.tournament_venue = "Required";
        if (!formData.medal_won) newErrors.medal_won = "Required";
        if (eventTitle === "Team Event") {
            if (!formData.match_played_by_team)
                newErrors.match_played_by_team = "Required";
            if (!formData.match_played_by_me)
                newErrors.match_played_by_me = "Required";
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

        // Submit using fetch or axios
        console.log("Submitting form...", formData);

        const response = await saveSportsDiscipline(submissionData);
        fetchUserData();
        if (currentStep < stepsTotal) setCurrentStep((nxt) => nxt + 1);

        navigate({
            pathname: location.pathname, // or keep current path
            search: createSearchParams({
                step: currentStep + 1,
            }).toString(),
        });
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
                    <ol>
                        <li
                            className={
                                currentStep === 1 ? "progress-active" : ""
                            }
                        >
                            <span>1. Event Details</span>
                        </li>
                        <li
                            className={
                                currentStep === 2 ? "progress-active" : ""
                            }
                        >
                            <span>2. Education Details</span>
                        </li>
                        <li
                            className={
                                currentStep === 3 ? "progress-active" : ""
                            }
                        >
                            <span>3. Sports Discipline</span>
                        </li>
                        <li
                            className={
                                currentStep === 4 ? "progress-active" : ""
                            }
                        >
                            <span>4. Declaration</span>
                        </li>
                    </ol>
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
                <div className="float-end m-2" hidden={!isSubmitted}>
                    {/* <button
                        className="btn btn-primary me-1"
                        onClick={handlePrint}
                    >
                        Print <i className="fa fa-print"></i>
                    </button> */}
                    <Link
                        className="btn btn-primary me-1"
                        to="/hosp/preview-application"
                    >
                        Preview Form
                    </Link>
                    {/* <button className="btn btn-primary me-1" onClick={downloadPDF}>Preview Form</button> */}
                </div>
                {!isSubmitted && !isSubmitting && (
                    <div>
                        <form
                            onSubmit={handleSubmit(onEventSubmit)}
                            className="needs-validation row g-3"
                            hidden={currentStep === 1 ? false : true}
                        >
                            <div className="row g-3">
                                {eventTitle === "Individual Event" ? (
                                    <h4 className="text-center mt-4">
                                        FORM - I
                                        <br />
                                        [See rule 9 (1)]
                                    </h4>
                                ) : (
                                    <h4 className="text-center mt-4">
                                        FORM - II
                                        <br />
                                        [See rule 9 (1)]
                                        <br />
                                    </h4>
                                )}
                                <h2 className="text-center mt-1">
                                    {eventTitle}
                                </h2>

                                <div className="col-md-6">
                                    <label>Select Event</label>
                                    <select
                                        {...register("event_type")}
                                        className={`form-select ${
                                            errors.event_type
                                                ? "is-invalid"
                                                : ""
                                        }`}
                                        onChange={(e) =>
                                            fetchTournamentList(e.target.value)
                                        }
                                    >
                                        <option value="" selected disabled>
                                            Select
                                        </option>
                                        <option value="1">
                                            Individual Event
                                        </option>
                                        <option value="2">Team Event</option>
                                    </select>
                                    {errors.event_type && (
                                        <div className="invalid-feedback">
                                            {
                                                errors.event_type
                                                    .message as string
                                            }
                                        </div>
                                    )}
                                </div>

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
                                )}
                                <hr />
                                <div className="col-12 text-end">
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
                                <div key={index} className="row g-3">
                                    <div className="col-md-6">
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
                                            <option value="10">10th</option>
                                            <option value="12">12th</option>
                                            <option value="Graduation">
                                                Graduation
                                            </option>
                                            <option value="Post graduation">
                                                Post Graduation
                                            </option>
                                            <option value="Other">Other</option>
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
                                                    href={`/api/certificates/${encodeURIComponent(
                                                        field.fileUrl
                                                    )}/education-certificates`}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                >
                                                    View Uploaded Certificate
                                                </a>
                                            </div>
                                        )}
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

                                    {educationErrors && (
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
                            <div id="text-center mt-4" className="text-end">
                                {currentStep > 1 && (
                                    <button
                                        type="button"
                                        onClick={prevStep}
                                        className="btn btn-primary m-2"
                                    >
                                        Previous
                                    </button>
                                )}

                                <button
                                    type="submit"
                                    className="btn btn-primary"
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
                            <h2 className="text-center">Sports Discipline</h2>

                            <div className="col-md-12">
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
                                                    href={`/api/certificates/${encodeURIComponent(
                                                        formData.disability_doc
                                                    )}/certificates`}
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
                                <label>Select Sports Discipline</label>
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
                            <div className="col-md-6"></div>
                            <div className="col-md-6">
                                <label>Tournament</label>
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
                                <label>Organizing Committee</label>
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
                                <label>Tournament Level</label>
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
                                <label>Achievement Date</label>
                                <input
                                    type="date"
                                    name="achievement_date"
                                    value={formData.achievement_date}
                                    className={`form-control ${
                                        sportdiserrors.achievement_date
                                            ? "is-invalid"
                                            : ""
                                    }`}
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
                                <label>Tournament Venue</label>
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
                                </select>
                                <div className="invalid-feedback">
                                    {sportdiserrors.medal_won}
                                </div>
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
                                    Attach Proof of Outstanding Sports Person
                                    Achievment Certificate (pdf)
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
                                            href={`/api/certificates/${encodeURIComponent(
                                                formData.osp_achivement_certificate_path
                                            )}/certificates`}
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            View Uploaded Certificate
                                        </a>
                                    </div>
                                )}
                            </div>
                            <div className="col-md-6">
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
                            </div>

                            <div className="col-12 text-center mt-3">
                                {currentStep > 1 && (
                                    <button
                                        type="button"
                                        onClick={prevStep}
                                        className="btn btn-primary m-2"
                                    >
                                        Previous
                                    </button>
                                )}
                                <button
                                    className="btn btn-primary"
                                    type="submit"
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
                            <h3 className="text-center">
                                Declaration by Sportsperson
                            </h3>
                            <div className="">
                                {/* <p className="text-center mb-4">Declaration</p> */}
                                <div className="row">
                                    <div className="col-md-12 mb-3">
                                        {/* <p>Declaration by Sportsperson</p> */}
                                        {declarationList.map((label, index) => (
                                            <div
                                                className="form-check"
                                                key={`d${index + 1}`}
                                            >
                                                <input
                                                    className="form-check-input"
                                                    type="checkbox"
                                                    hidden
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
                                                    className="form-check-label lb"
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
                                                className="form-check-label lb"
                                                htmlFor="acceptAll"
                                            >
                                                Accept all
                                            </label>
                                        </div>
                                    </div>

                                    <div className="col-md-6">
                                        <label>Upload Declaration</label>
                                        <input
                                            type="file"
                                            accept="application/pdf"
                                            className="form-control"
                                            onChange={
                                                handleDeclarationFileChange
                                            }
                                        />
                                    </div>
                                    <div
                                        className="col-md-6 float-end mt-4"
                                        hidden={currentStep !== 4}
                                    >
                                        <button
                                            className="btn btn-primary"
                                            onClick={handlePrintDeclaration}
                                        >
                                            Print Declaration
                                            <i className="fa fa-print"></i>
                                        </button>
                                    </div>
                                    <div className="text-danger">
                                        {diclarationerrors.msg}
                                    </div>
                                </div>
                            </div>

                            <div id="col-12 text-center mt-3">
                                {currentStep === stepsTotal && (
                                    <button
                                        id="submit-btn"
                                        type="submit"
                                        className="btn btn-primary"
                                    >
                                        Submit
                                    </button>
                                )}
                            </div>
                        </form>
                    </div>
                )}

                {isSubmitted && (
                    <div
                        className="text-center mt-5 p-4 border rounded shadow-sm bg-light"
                        id="print-section"
                    >
                        <h4>Application is successfully submitted</h4>
                        <p>Application ID: {userDetails?.application_id}</p>
                        <Link to="/hosp/login">Go Login</Link>
                    </div>
                )}
            </div>

            <div id="preloader-wrapper">
                <div id="preloader"></div>
                <div className="preloader-section section-left"></div>
                <div className="preloader-section section-right"></div>
            </div>
            {/* print declaration */}
            <div id="print_declaration" style={{ display: "none" }}>
                <table width="100%">
                    <thead style={{ background: "#225395" }}>
                        <tr>
                            <th style={{ padding: "10px" }}>
                                <div className="logo">
                                    <a
                                        href="#"
                                        title="Go to home"
                                        className="site_logo"
                                        rel="home"
                                    >
                                        <img
                                            id="logo"
                                            src="images/logo-sports.png"
                                            alt="Sports Haryana Govt"
                                        />
                                        <div className="logo_text">
                                            <h1 className="h1-logo">
                                                Sports Department
                                            </h1>
                                        </div>
                                    </a>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td align="center" style={{ paddingTop: "30px" }}>
                                <h4>Declaration by Sportsperson</h4>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table
                                    width="60%"
                                    style={{
                                        margin: "0 auto 50px",
                                        background: "#fff",
                                        tableLayout: "fixed",
                                    }}
                                >
                                    <tbody>
                                        <tr>
                                            <td style={{ padding: "15px" }}>
                                                Name
                                                <h5>{userData.name}</h5>
                                            </td>
                                            <td style={{ padding: "15px" }}>
                                                Date Of birth
                                                <h5>
                                                    {userDetails.date_of_birth}
                                                </h5>
                                            </td>
                                            <td
                                                style={{ padding: "15px" }}
                                                align="right"
                                            >
                                                Aadhar No.
                                                <h5>{userDetails.aadhaar}</h5>
                                            </td>
                                        </tr>
                                        <tr>
                                            
                                            <td
                                                style={{ padding: "15px" }}
                                                align="center"
                                            >
                                                Caste Category
                                                <h5>
                                                    {userDetails.caste_category}
                                                </h5>
                                            </td>
                                            <td
                                                style={{ padding: "15px" }}
                                                align="right"
                                            >
                                                Mobile
                                                <h5>{userData.mobile}</h5>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style={{ padding: "15px" }}>
                                                Haryana Domicle
                                                <h5>
                                                    {userDetails.domicile == "1"
                                                        ? "Yes"
                                                        : "No"}
                                                </h5>
                                            </td>
                                        </tr>
                                        {/* <tr>
                                        <td style={{ padding: "15px" }}>
                                        Event </td>
                                        </tr> */}
                                        {userData.event_hosp && (
                                        <tr>
                                            <td style={{ padding: "15px" }}>
                                                Event type
                                                <h5>
                                                    {userData.event_hosp
                                                        .event_type == "1"
                                                        ? "Individual"
                                                        : "Team"}
                                                </h5>
                                            </td>
                                            <td style={{ padding: "15px" }}>
                                            Played National Level
                                                <h5>
                                                    {userData.event_hosp
                                                        .played_national_level ==
                                                    "1"
                                                        ? "Yes"
                                                        : "No"}
                                                </h5>
                                                <h6>
                                                    {userData.event_hosp
                                                        .national_level_doc
                                                        ? "(Attached doc)"
                                                        : "(No Attachment)"}
                                                </h6>
                                            </td>
                                            <td style={{ padding: "15px" }}>
                                                Name of Central Organisation
                                                Represented
                                                <h5>
                                                    {
                                                        userData.organisation_represented
                                                    }
                                                </h5>
                                                <h6>
                                                    {userData.event_hosp
                                                        .organisation_doc
                                                        ? "(Attached doc)"
                                                        : "(No Attachment)"}
                                                </h6>
                                            </td>
                                        </tr>
                                        )}
                                         {userData.education_hosp.length && (
                                        <tr>
                                        <td style={{ padding: "15px" }}>
                                       Educations </td>
                                        </tr>
                                         )}
                                        {userData.education_hosp.map(
                                            (item, index) => (
                                                <tr key={item.id || index}>
                                                    <td
                                                        style={{
                                                            padding: "15px",
                                                        }}
                                                    >
                                                        Qualification
                                                        <h5>
                                                            {item.qualification}
                                                        </h5>
                                                    </td>
                                                    <td
                                                        style={{
                                                            padding: "15px",
                                                        }}
                                                    >
                                                        Certificate
                                                        <h6>
                                                            {item.certificate_path
                                                                ? "(Attached doc)"
                                                                : "(No Attachment)"}
                                                        </h6>
                                                    </td>
                                                    {item.other_qualification && (
                                                        <td
                                                            style={{
                                                                padding: "15px",
                                                            }}
                                                        >
                                                            Other Qualification
                                                            <h5>
                                                                {item.other_qualification ||
                                                                    "N/A"}
                                                            </h5>
                                                            <h6>
                                                                {item.certificate_path
                                                                    ? "(Attached doc)"
                                                                    : "(No Attachment)"}
                                                            </h6>
                                                        </td>
                                                    )}
                                                </tr>
                                            )
                                        )}
                                        {userData.sports_discipline_hosp && (
                                        <tr>
                                        <td style={{ padding: "15px" }}>
                                        Sports Discipline </td>
                                        <h5>
                                                    {
                                                        userData
                                                            .sports_discipline_hosp
                                                            .tournament_id
                                                    }
                                                </h5>
                                        </tr>
                                        )}
                                        {userData.sports_discipline_hosp && (
                                        <tr>
                                            <td style={{ padding: "15px" }}>
                                                Tournament Venue
                                                <h5>
                                                    {
                                                        userData
                                                            .sports_discipline_hosp
                                                            .tournament_venue
                                                    }
                                                </h5>
                                            </td>

                                            <td style={{ padding: "15px" }}>
                                                Medal Won
                                                <h5>
                                                    {userData
                                                        .sports_discipline_hosp
                                                        .medal_won || "None"}
                                                </h5>
                                            </td>

                                            <td style={{ padding: "15px" }}>
                                                Physical Disability
                                                <h5>
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
                                        </tr>
                                        )}
                                        {userData.sports_discipline_hosp && (
                                        <tr>
                                            <td style={{ padding: "15px" }}>
                                                Represented India
                                                <h5>
                                                    {userData
                                                        .sports_discipline_hosp
                                                        .represented_india === 1
                                                        ? "Yes"
                                                        : "No"}
                                                </h5>
                                            </td>
                                            <td style={{ padding: "15px" }}>
                                                Organizing Committee
                                                <h5>
                                                    {
                                                        userData
                                                            .sports_discipline_hosp
                                                            .organizing_committee
                                                    }
                                                </h5>
                                            </td>

                                           
                                        </tr>
                                        )}
                                        {userData.sports_discipline_hosp && (
                                        <tr>
                                        <td style={{ padding: "15px" }}>
                                                OSP Certificate
                                                <h6>
                                                    {userData
                                                        .sports_discipline_hosp
                                                        .osp_achivement_certificate_path
                                                        ? "(Attached doc)"
                                                        : "(No Attachment)"}
                                                </h6>
                                            </td>

                                            <td style={{ padding: "15px" }}>
                                                International Certificate
                                                <h6>
                                                    {userData
                                                        .sports_discipline_hosp
                                                        .international_achievement_Verification_certificate_path
                                                        ? "(Attached doc)"
                                                        : "(No Attachment)"}
                                                </h6>
                                            </td>
                                        </tr>
                                        )}
                                        <tr>
                                            <td colSpan={3}>
                                                <hr style={{ marginTop: 0 }} />
                                            </td>
                                        </tr>
                                        <tr style={{ padding: "20px 0 0" }}>
                                            <td
                                                style={{ padding: "0 20px" }}
                                                colSpan={2}
                                            >
                                                <strong>Date -</strong> <u> </u>
                                            </td>
                                            <td
                                                align="right"
                                                style={{
                                                    padding: "40px 20px 20px",
                                                    textAlign: "right",
                                                }}
                                            >
                                                <strong>
                                                    (Signature of Sportsperson)
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colSpan={3}>
                                                <ol type="1">
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
                                                    <li>
                                                        I forego my earlier
                                                        claim made under the
                                                        Haryana Outstanding
                                                        Sportsperson
                                                        (Recruitment and
                                                        Condition of Service)
                                                        Rule, 2018, which have
                                                        been repealed.
                                                    </li>
                                                </ol>
                                                <p
                                                    style={{
                                                        padding: "10px 15px",
                                                    }}
                                                >
                                                    It is certified that the
                                                    above particulars given by
                                                    me are true and correct to
                                                    the best of my knowledge and
                                                    record and there is no
                                                    martial concealment . In
                                                    case of any wrong
                                                    information furnished or
                                                    material concealment, my
                                                    service may be terminated
                                                    without notice.
                                                </p>
                                            </td>
                                        </tr>
                                        <tr style={{ padding: "20px 0 0" }}>
                                            <td
                                                style={{ padding: "0 20px" }}
                                                colSpan={2}
                                            >
                                                <strong>Date -</strong> <u> </u>
                                            </td>
                                            <td
                                                align="right"
                                                style={{
                                                    padding: "40px 20px 20px",
                                                    textAlign: "right",
                                                }}
                                            >
                                                <strong>
                                                    (Signature of Sportsperson)
                                                </strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td
                                                colSpan={3}
                                                style={{
                                                    pageBreakAfter: "always",
                                                }}
                                            ></td>
                                        </tr>

                                        <tr>
                                            <td
                                                colSpan={3}
                                                style={{
                                                    padding: "60px 20px 0",
                                                }}
                                            >
                                                <h3
                                                    style={{
                                                        textAlign: "center",
                                                    }}
                                                >
                                                    VERIFICATION BY NATIONAL
                                                    SPORTS FEDERATION
                                                </h3>
                                                <p>
                                                    Certified that the
                                                    particulars declared by the
                                                    sportsperson have been
                                                    checked, verified and found
                                                    correct.
                                                </p>
                                            </td>
                                        </tr>

                                        <tr style={{ padding: "20px 20px 0" }}>
                                            <td
                                                style={{ padding: "0 20px" }}
                                                colSpan={2}
                                            >
                                                <strong>Date -</strong> <u> </u>
                                            </td>
                                            <td
                                                align="right"
                                                style={{
                                                    padding: "40px 20px 20px",
                                                    textAlign: "right",
                                                }}
                                            >
                                                <strong>
                                                    (Signature and Seal of the
                                                    Secretary/President
                                                    <br />
                                                    of National Sports
                                                    Federation concerned)
                                                </strong>
                                            </td>
                                        </tr>
                                    </tbody>
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
