import MainLayout from "@/Layouts/MainLayout";
import Features from "@/Sections/Features";
import GettingStarted from "@/Sections/GettingStarted";
import Hero from "@/Sections/Hero";
import Usage from "@/Sections/Usage";
import { Head } from "@inertiajs/react";
import React from "react";

const Home = () => {
    return (
        <div>
            <Head title="Home" />
            <Hero />
            <Features />
            <GettingStarted />
            <Usage />
        </div>
    );
};

export default Home;

Home.layout = (page) => <MainLayout>{page}</MainLayout>;
