import React from "react";
import {
    Card,
    CardBody,
    Divider,
    Snippet,
    Code,
    addToast,
} from "@heroui/react";

const Usage = () => {
    return (
        <div
            className="min-h-[80vh] flex flex-col justify-center items-center w-full py-20"
            id="usage"
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
                                        ⚡Usage
                                    </h1>
                                    <Divider className="my-4" />
                                    <div className="mb-5 w-full">
                                        <h1 className="text-lg md:text-xl font-medium mt-2">
                                            🏗️ Create Inertia Components
                                        </h1>
                                        <Snippet
                                            size="sm"
                                            className="p-4 w-full mt-2 overflow-x-auto bg-slate-400/50"
                                            symbol={false}
                                            onCopy={() => {
                                                addToast({
                                                    title: "Copied!",
                                                });
                                            }}
                                        >
                                            php artisan make:inertia
                                            Components/MyComponent
                                        </Snippet>
                                        <p className="mt-1 mb-5">
                                            This will create a new file at
                                            resources/js/Components/MyComponent.jsx
                                            with a basic component template.
                                        </p>
                                        <h1 className="text-lg md:text-xl font-medium mt-2">
                                            📂 File Structure
                                        </h1>
                                        <Snippet
                                            symbol={false}
                                            size="sm"
                                            className="p-4 w-full mt-2 mb-5 overflow-x-auto bg-slate-400/50"
                                            onCopy={() => {
                                                addToast({
                                                    title: "Copied!",
                                                });
                                            }}
                                        >
                                            resources/js/Components/MyComponent.jsx
                                        </Snippet>
                                        <h1 className="text-lg md:text-xl font-medium mt-2">
                                            ✨ Example Generated Component
                                        </h1>
                                        <Code
                                            size="sm"
                                            className="p-4 w-full mt-2 overflow-x-auto bg-slate-400/50"
                                            symbol={false}
                                        >
                                            <span>
                                                import React from "react";
                                            </span>
                                            <br />
                                            <br />
                                            <span>{`const MyComponent = () => {`}</span>
                                            <br />
                                            <span>
                                                {`return <div>{/* MyComponent component */}</div>;`}
                                            </span>
                                            <br />
                                            <span>{`};`}</span>
                                            <br />
                                            <br />
                                            <span>{`export default MyComponent;`}</span>
                                        </Code>
                                        <p className="mt-1 mb-10">
                                            This command ensures that components
                                            are placed in the correct directory
                                            and prevents overwriting existing
                                            files. It also automatically opens
                                            the newly created file for editing.
                                        </p>
                                    </div>
                                    <div className="mb-5 w-full">
                                        <h1 className="text-lg md:text-xl font-medium mt-2">
                                            🏗️ Create Orion Controllers
                                        </h1>
                                        <Snippet
                                            size="sm"
                                            className="p-4 w-full mt-2 overflow-x-auto bg-slate-400/50"
                                            symbol={false}
                                            onCopy={() => {
                                                addToast({
                                                    title: "Copied!",
                                                });
                                            }}
                                        >
                                            php artisan make:orion
                                            PostController
                                        </Snippet>
                                        <p className="mt-1 mb-1">
                                            This will create:
                                        </p>
                                        <ul className="space-y-2 mb-5 overflow-x-auto">
                                            <li>
                                                <Code className=" bg-slate-400/50">
                                                    app/Http/Controllers/Orion/PostController.php
                                                </Code>
                                            </li>
                                            <li className="flex item-center">
                                                <Code className=" bg-slate-400/50">
                                                    app/Models/Post.php
                                                </Code>
                                            </li>
                                            <li className="flex item-center">
                                                <Code className=" bg-slate-400/50">
                                                    routes/api.php
                                                </Code>
                                            </li>
                                        </ul>
                                        <h1 className="text-lg md:text-xl font-medium mt-2">
                                            📂 File Structure
                                        </h1>
                                        <Snippet
                                            symbol={false}
                                            size="sm"
                                            className="p-4 w-full mt-2 mb-5 overflow-x-auto bg-slate-400/50"
                                            onCopy={() => {
                                                addToast({
                                                    title: "Copied!",
                                                });
                                            }}
                                        >
                                            <span>{`app/Http/Controllers/Orion/PostController.php`}</span>
                                            <span>{`app/Models/Post.php`}</span>
                                        </Snippet>
                                        <h1 className="text-lg md:text-xl font-medium mt-2">
                                            ✨ Example Generated Controller
                                        </h1>
                                        <Code
                                            size="sm"
                                            className="p-4 w-full mt-2 mb-5 overflow-x-auto bg-slate-400/50"
                                            symbol={false}
                                        >
                                            <span>{`<?php`}</span>
                                            <br />
                                            <br />
                                            <span>{`namespace App/Http/Controllers/Orion;`}</span>
                                            <br />
                                            <br />
                                            <span>
                                                {`use Orion/Http/Controllers/Controller;`}
                                            </span>
                                            <br />
                                            <span>{`use App/Models/Post;`}</span>
                                            <br />
                                            <br />
                                            <span>{`class PostController extends Controller`}</span>
                                            <br />
                                            <span>{`{`}</span>
                                            <br />
                                            <span>{` protected $model = Post::class;`}</span>
                                            <br />
                                            <span>{`}`}</span>
                                        </Code>
                                        <h1 className="text-lg md:text-xl font-medium mt-2">
                                            🔗 Auto-Registered Route in
                                            routes/api.php
                                        </h1>
                                        <Snippet
                                            symbol={false}
                                            size="sm"
                                            className="p-4 w-full mt-2 overflow-x-auto bg-slate-400/50"
                                            onCopy={() => {
                                                addToast({
                                                    title: "Copied!",
                                                });
                                            }}
                                        >
                                            Orion::resource('posts',
                                            /App/Http/Controllers/Orion/PostController::class);
                                        </Snippet>
                                        <p className="mt-1 mb-1">
                                            This command ensures that
                                            controllers are correctly placed,
                                            models are created if missing, and
                                            routes are automatically registered.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </CardBody>
            </Card>
        </div>
    );
};

export default Usage;
