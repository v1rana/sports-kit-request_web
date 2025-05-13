import React, { useEffect, useState } from "react";
import { getHOSP } from "../services/hosp-service";

function HospList() {
    const [jobs, setJobs] = useState([]);

    useEffect(() => {
        const fetchHosp = async () => {
            try {
                const data = await getHOSP();
                setJobs(data);
            } catch (error) {
                console.error("Failed to load jobs.");
            }
        };

        fetchHosp();
    }, []);

    return (
        <div>
            <h1>Job Listings</h1>
            <ul>
                {jobs.map((job:any) => (
                    <li key={job.id}>{job.title}</li>
                ))}
            </ul>
        </div>
    );
}

export default HospList;
