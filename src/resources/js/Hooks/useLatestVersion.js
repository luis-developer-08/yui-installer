import { useEffect, useState } from "react";
import axios from "axios";

const useLatestVersion = (repo) => {
    const [version, setVersion] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchLatestVersion = async () => {
            try {
                const response = await axios.get(
                    `https://api.github.com/repos/${repo}/releases/latest`
                );
                setVersion(response.data.tag_name);
            } catch (err) {
                setError("Failed to fetch version");
            } finally {
                setLoading(false);
            }
        };

        fetchLatestVersion();
    }, [repo]);

    return { version, loading, error };
};

export default useLatestVersion;
