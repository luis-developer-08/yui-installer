import { BsGithub } from "react-icons/bs";
import React from "react";
import { Card, CardBody, Image, Divider, Button } from "@heroui/react";
import { router } from "@inertiajs/react";
import GithubStarButton from "@/Components/GithubStarButton";

const Hero = () => {
    const handleLinkClick = (sectionId) => {
        const section = document.getElementById(sectionId);
        if (section) {
            section.scrollIntoView({ behavior: "smooth", block: "start" });
        }
    };

    return (
        <div
            className="min-h-[80vh] flex flex-col justify-center items-center"
            id="home"
        >
            <Card
                isBlurred
                className="border-none bg-white/70 dark:bg-default-100/50 p-2 md:p-5"
                shadow="sm"
            >
                <CardBody className="overflow-visible py-2">
                    <div className="grid grid-cols-6 md:grid-cols-12 gap-4 md:gap-10 items-center justify-center">
                        <div className="flex flex-col col-span-6 md:col-span-8">
                            <div className="flex justify-between items-start">
                                <div className="flex flex-col gap-0 justify-center items-center w-full md:items-start">
                                    <h1 className="text-2xl md:text-3xl font-medium">
                                        🌟 YUI INSTALLER 🌟
                                    </h1>
                                    <div className="md:hidden mt-2">
                                        <GithubStarButton repo="luis-developer-08/yui-installer" />
                                    </div>

                                    <Divider className="my-4" />
                                    <div className="hidden md:block">
                                        <h1 className="text-xl font-medium mt-5 mb-2">
                                            🚀 Introduction
                                        </h1>
                                        <p className="text-large text-foreground/80 text-justify">
                                            YUI is a lightweight Laravel
                                            installer for modern developers,
                                            featuring Laravel 12, React 19
                                            (JSX), Inertia.js, TailwindCSS 4,
                                            and Breeze for authentication.
                                            Pre-configured with Orion for REST
                                            APIs, TanStack Query for state
                                            management, and Laravel-permission
                                            for role handling. Optimized with
                                            Vite for fast builds and includes
                                            PHPUnit & Pest for testing. Get
                                            started in minutes! Let me know if
                                            you want any tweaks!
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="relative col-span-6 md:col-span-4 flex flex-col items-center">
                            <Image
                                alt="Card background"
                                className="object-cover rounded-xl"
                                src="../images/yui.png"
                                width={270}
                            />

                            <div className="flex gap-2 mt-10">
                                <Button
                                    color="primary"
                                    size="lg"
                                    onClick={() =>
                                        handleLinkClick("getting-started")
                                    }
                                >
                                    Getting Started
                                </Button>
                                <Button
                                    size="lg"
                                    endContent={<BsGithub size={25} />}
                                    onPress={() =>
                                        window.open(
                                            "https://github.com/luis-developer-08/yui-installer",
                                            "_blank"
                                        )
                                    }
                                    className="bg-slate-500/50"
                                >
                                    Github
                                </Button>
                            </div>
                        </div>
                    </div>
                </CardBody>
            </Card>
        </div>
    );
};

export default Hero;
