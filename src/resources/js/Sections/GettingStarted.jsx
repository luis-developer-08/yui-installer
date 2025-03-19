import React from "react";
import { Card, CardBody, Divider, Snippet, addToast } from "@heroui/react";

const GettingStarted = () => {
    return (
        <div
            className="min-h-[80vh] flex flex-col justify-center items-center w-full py-20 md:py-0"
            id="getting-started"
        >
            <Card
                isBlurred
                className="border-none bg-white/70 dark:bg-default-100/50 p-2 md:p-5 w-full rounded-none md:rounded-2xl"
                shadow="sm"
            >
                <CardBody className="overflow-visible py-2">
                    <div className="grid grid-cols-6 md:grid-cols-12 gap-4 md:gap-10 items-center justify-center">
                        <div className="flex flex-col col-span-6 md:col-span-12">
                            <div className="flex justify-between items-start">
                                <div className="flex flex-col gap-0 w-full">
                                    <h1 className="text-2xl md:text-3xl font-medium mt-2">
                                        🛠 Getting Started
                                    </h1>
                                    <Divider className="my-4" />
                                    <h1 className="text-lg md:text-xl font-medium mt-2">
                                        1️⃣ Install
                                    </h1>
                                    <Snippet
                                        size="sm"
                                        className="p-4 mt-2 mb-5 overflow-x-auto bg-slate-400/50"
                                        symbol={false}
                                        onCopy={() => {
                                            addToast({
                                                title: "Copied!",
                                            });
                                        }}
                                    >
                                        composer global require
                                        luis-developer-08/yui-installer
                                    </Snippet>
                                    <h1 className="text-lg md:text-xl font-medium mt-2">
                                        2️⃣ Create a New Laravel Project
                                    </h1>
                                    <Snippet
                                        size="sm"
                                        className="p-4 mt-2 mb-5 overflow-x-auto bg-slate-400/50"
                                        symbol={false}
                                        onCopy={() => {
                                            addToast({
                                                title: "Copied!",
                                            });
                                        }}
                                    >
                                        yui new my-laravel-app
                                    </Snippet>
                                    <h1 className="text-lg md:text-xl  font-medium mt-2">
                                        🎉 Your application is now up and
                                        running!
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </CardBody>
            </Card>
        </div>
    );
};

export default GettingStarted;
