import React from "react";

const GithubStarButton = ({ repo }) => {
    return (
        <a
            href={`https://github.com/${repo}`}
            target="_blank"
            rel="noopener noreferrer"
            style={{
                display: "inline-flex",
                alignItems: "center",
                gap: "8px",
                padding: "8px 12px",
                backgroundColor: "#24292e",
                color: "white",
                textDecoration: "none",
                borderRadius: "5px",
                fontSize: "14px",
                fontWeight: "bold",
            }}
        >
            ⭐ Star on GitHub
        </a>
    );
};

export default GithubStarButton;
