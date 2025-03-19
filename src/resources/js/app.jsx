import "../css/app.css";
import "./bootstrap";

import { createInertiaApp } from "@inertiajs/react";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createRoot } from "react-dom/client";
import { HeroUIProvider, ToastProvider } from "@heroui/react";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

const queryClient = new QueryClient({
    defaultOptions: {
        queries: {
            refetchOnWindowFocus: false,
            staleTime: 1000 * 60 * 5,
        },
    },
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.jsx`,
            import.meta.glob("./Pages/**/*.jsx")
        ),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(
            <QueryClientProvider client={queryClient}>
                <HeroUIProvider>
                    <ToastProvider
                        placement="top-right"
                        toastOffset={70}
                        toastProps={{
                            radius: "sm",
                            color: "primary",
                            variant: "flat",
                            timeout: 2000,
                            hideIcon: true,
                            classNames: {
                                closeButton:
                                    "opacity-100 absolute right-4 top-1/2 -translate-y-1/2",
                            },
                        }}
                    />
                    <App {...props} />
                </HeroUIProvider>
            </QueryClientProvider>
        );
    },
    progress: {
        color: "#4B5563",
    },
});
