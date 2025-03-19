import NavbarHUI from "@/Components/NavbarHUI";
import React from "react";

const MainLayout = ({ children }) => {
    return (
        <div
            className="min-h-screen flex flex-col bg-cover bg-center bg-fixed"
            style={{ backgroundImage: "url('/images/bg.jpg')" }}
        >
            {/* <div className="inset-0 bg-gray-500 bg-opacity-10 fixed"></div> */}
            <NavbarHUI />
            <main className="flex-1 md:mx-40 my-5">{children}</main>
        </div>
    );
};

export default MainLayout;
