<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeInertiaComponent extends Command
{
    protected $signature = 'make:inertia {path}';
    protected $description = 'Create a new React component at the specified path';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $path = $this->argument('path');
        $filesystem = new Filesystem();

        // Ensure the path is relative to the resources/js directory
        $componentPath = resource_path("js/{$path}.jsx");

        if ($filesystem->exists($componentPath)) {
            $this->error("⚠️ Component at {$path} already exists!");
            return;
        }
        $filesystem->ensureDirectoryExists(dirname($componentPath));
        $filesystem->put($componentPath, $this->getComponentTemplate(basename($path)));
        $this->info("✅ React component created successfully at {$componentPath}.");
        $this->openFile($componentPath);
    }
    protected function getComponentTemplate($name)
    {
        return <<<JSX
        import React from 'react';

        const {$name} = () => {
            return (
                <div>
                    {/* {$name} component */}
                </div>
            );
        };

        export default {$name};
        JSX;
    }
    protected function openFile($filePath)
    {
        if (PHP_OS_FAMILY === 'Windows') {
            exec("start \"\" \"$filePath\"");
        } elseif (PHP_OS_FAMILY === 'Linux') {
            exec("xdg-open \"$filePath\"");
        } elseif (PHP_OS_FAMILY === 'Darwin') { // macOS
            exec("open \"$filePath\"");
        }
    }
}