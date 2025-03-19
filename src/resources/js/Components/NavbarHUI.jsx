import React, { useState } from "react";
import {
    Navbar,
    NavbarBrand,
    NavbarContent,
    NavbarItem,
    NavbarMenuToggle,
    NavbarMenu,
    NavbarMenuItem,
    Link,
} from "@heroui/react";
import ApplicationLogo from "./ApplicationLogo";
import useLatestVersion from "@/Hooks/useLatestVersion";
import GithubStarButton from "./GithubStarButton";

const NavbarHUI = () => {
    const [isMenuOpen, setIsMenuOpen] = useState(false);
    const { version } = useLatestVersion("luis-developer-08/yui-installer");

    const menuItems = [
        { name: "Home", url: "home" },
        { name: "Features", url: "features" },
        { name: "Getting Started", url: "getting-started" },
        { name: "Usage", url: "usage" },
    ];

    // Function to handle smooth scrolling
    const handleLinkClick = (sectionId) => {
        const section = document.getElementById(sectionId);
        if (section) {
            section.scrollIntoView({ behavior: "smooth", block: "start" });
        }
        setIsMenuOpen(false); // Close menu on mobile after clicking
    };

    return (
        <Navbar
            onMenuOpenChange={setIsMenuOpen}
            className="bg-sky-300/70"
            maxWidth="xl"
            isMenuOpen={isMenuOpen}
        >
            <NavbarContent>
                <NavbarBrand>
                    <div className="w-9 me-3">
                        <ApplicationLogo />
                    </div>
                    <p className="font-bold text-inherit">YUI</p>
                </NavbarBrand>
            </NavbarContent>

            <NavbarContent className="hidden sm:flex gap-4" justify="center">
                {menuItems.map((item, index) => (
                    <NavbarItem key={`${item.url}-${index}`}>
                        <Link
                            color="foreground"
                            onClick={() => handleLinkClick(item.url)}
                            className="hover:bg-blue-600 duration-500 hover:text-white hover:cursor-pointer w-full px-4 py-2 rounded-md transition"
                        >
                            {item.name}
                        </Link>
                    </NavbarItem>
                ))}
            </NavbarContent>

            <NavbarContent justify="end">
                <NavbarItem>
                    <Link color="foreground">{version}</Link>
                </NavbarItem>
                <div className="hidden md:block">
                    <NavbarItem>
                        <GithubStarButton repo="luis-developer-08/yui-installer" />
                    </NavbarItem>
                </div>
                <NavbarMenuToggle
                    aria-label={isMenuOpen ? "Close menu" : "Open menu"}
                    className="sm:hidden"
                />
            </NavbarContent>

            <NavbarMenu>
                {menuItems.map((item, index) => (
                    <NavbarMenuItem key={`mobile-${item.url}-${index}`}>
                        <Link
                            className="w-full"
                            size="lg"
                            onClick={() => handleLinkClick(item.url)}
                        >
                            {item.name}
                        </Link>
                    </NavbarMenuItem>
                ))}
            </NavbarMenu>
        </Navbar>
    );
};

export default NavbarHUI;
