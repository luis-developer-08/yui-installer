<?php

namespace CustomLaravelInstaller;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\ChoiceQuestion;

class InstallCommand extends Command
{
    protected function configure()
    {
        $this->setName('new')
            ->setDescription('Create a new YUI Laravel project with optional Breeze authentication and Orion.')
            ->addArgument('name', InputArgument::OPTIONAL, 'The name of the Laravel project');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $name = $input->getArgument('name');
        if (!$name) {
            $question = new Question('<question>Enter the project name: [yui-laravel-project]</question> ', 'yui-laravel-project');
            $name = $helper->ask($input, $output, $question);
        }

        $dbTypeQuestion = new ChoiceQuestion(
            '<question>Which database will you use?</question> (default: SqLite)',
            ['sqlite', 'mysql'],
            0
        );
        $dbType = $helper->ask($input, $output, $dbTypeQuestion);

        if ($dbType === 'mysql') {
            $databaseConfig = [
                'DB_CONNECTION' => 'mysql',
                'DB_HOST' => '127.0.0.1',
                'DB_PORT' => '3306',
                'DB_DATABASE' => $name,
                'DB_USERNAME' => 'root',
                'DB_PASSWORD' => '',
            ];
        } else {
            $databaseConfig = [
                'DB_CONNECTION' => 'sqlite',
                '# DB_HOST' => '127.0.0.1',
                '# DB_PORT' => '3306',
                '# DB_DATABASE' => $name,
                '# DB_USERNAME' => 'root',
                '# DB_PASSWORD' => '',
            ];
        }

        $breezeQuestion = new ConfirmationQuestion('<info>Do you want to install Breeze authentication? (y/n): [y]</info>', true);
        $installBreeze = $helper->ask($input, $output, $breezeQuestion);

        if ($installBreeze) {
            $helper = $this->getHelper('question');

            function askWithOptions($helper, $input, $output, $questionText, $options, $default)
            {
                $output->writeln("\n<question>$questionText</question>");
                foreach ($options as $index => $option) {
                    $output->writeln("  <info>" . ($index + 1) . ". $option</info>");
                }

                $question = new Question("Your choice (default: $default): ", $default);
                $answer = $helper->ask($input, $output, $question);

                while (!in_array($answer, $options) && (!ctype_digit($answer) || !isset($options[(int) $answer - 1]))) {
                    $output->writeln('<error>Invalid choice. Please select a valid option.</error>');
                    $answer = $helper->ask($input, $output, $question);
                }

                return isset($options[(int) $answer - 1]) ? $options[(int) $answer - 1] : $answer;
            }

            // Ask for Breeze stack
            $stackOptions = ['blade', 'livewire', 'livewire-functional', 'react', 'vue', 'api'];
            $stack = askWithOptions($helper, $input, $output, 'Which Breeze stack do you want to use?', $stackOptions, 'react');

            // Ask for additional features
            $featureOptions = ['dark', 'ssr', 'typescript', 'eslint'];
            $output->writeln("\n<question>Select additional features (comma-separated for multiple, or leave blank for none):</question>");
            foreach ($featureOptions as $index => $feature) {
                $output->writeln("  <info>" . ($index + 1) . ". $feature</info>");
            }

            $featuresQuestion = new Question("Your choice: ", '');
            $featuresInput = $helper->ask($input, $output, $featuresQuestion);
            $features = array_map('trim', explode(',', $featuresInput));
            $features = array_filter($features, function ($feature) use ($featureOptions) {
                return in_array($feature, $featureOptions);
            });

            // Ask for test framework
            $testFrameworkOptions = ['Pest', 'PHPUnit'];
            $testFramework = askWithOptions($helper, $input, $output, 'Which test framework do you want to use?', $testFrameworkOptions, 'Pest');

            // Store the user's choices
            $options = compact('stack', 'features', 'testFramework');
        }

        $orionQuestion = new ConfirmationQuestion('Do you want to install Orion? (y/n): [y]', true);
        $installOrion = $helper->ask($input, $output, $orionQuestion);

        $spatiePermissionQuestion = new ConfirmationQuestion('Do you want to install Spatie permission? (y/n): [y]', true);
        $installSpatiePermission = $helper->ask($input, $output, $spatiePermissionQuestion);

        $tanstackReactQueryQuestion = new ConfirmationQuestion('Do you want to install Tanstack React Query? (y/n): [y]', true);
        $installTanstackReactQueryQuestion = $helper->ask($input, $output, $tanstackReactQueryQuestion);

        $output->writeln("<info>Creating Laravel project: $name</info>");
        $this->runCommand("composer create-project laravel/laravel $name", $output);

        chdir($name);

        // Path to .env file
        $envPath = getcwd() . '/.env';

        // Read .env file
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);

            // Ensure all database keys exist and are properly updated
            $envContent = preg_replace('/^APP_NAME=Laravel.*/m', "APP_NAME=YUI", $envContent);
            $envContent = preg_replace('/^DB_CONNECTION=.*/m', "DB_CONNECTION={$databaseConfig['DB_CONNECTION']}", $envContent);
            $envContent = preg_replace('/^# DB_HOST=.*/m', "DB_HOST={$databaseConfig['DB_HOST']}", $envContent);
            $envContent = preg_replace('/^# DB_PORT=.*/m', "DB_PORT={$databaseConfig['DB_PORT']}", $envContent);
            $envContent = preg_replace('/^# DB_DATABASE=.*/m', "DB_DATABASE={$databaseConfig['DB_DATABASE']}", $envContent);
            $envContent = preg_replace('/^# DB_USERNAME=.*/m', "DB_USERNAME={$databaseConfig['DB_USERNAME']}", $envContent);
            $envContent = preg_replace('/^# DB_PASSWORD=.*/m', "DB_PASSWORD={$databaseConfig['DB_PASSWORD']}", $envContent);

            // Handle SQLite case: Ensure MySQL values are not commented, and SQLite values are commented
            if ($dbType === 'sqlite') {
                $envContent = preg_replace('/^DB_CONNECTION=.*/m', "DB_CONNECTION=sqlite", $envContent);
                $envContent = preg_replace('/^DB_HOST=.*/m', "# DB_HOST=127.0.0.1", $envContent);
                $envContent = preg_replace('/^DB_PORT=.*/m', "# DB_PORT=3306", $envContent);
                $envContent = preg_replace('/^DB_DATABASE=.*/m', "# DB_DATABASE={$databaseConfig['DB_DATABASE']}", $envContent);
                $envContent = preg_replace('/^DB_USERNAME=.*/m', "# DB_USERNAME=root", $envContent);
                $envContent = preg_replace('/^DB_PASSWORD=.*/m', "# DB_PASSWORD=", $envContent);
            }

            // Save updated .env file
            file_put_contents($envPath, $envContent);
            $output->writeln("✅ <info>Updated .env file with database configuration.</info>");
        }

        if ($installBreeze) {
            $output->writeln("<info>Installing Breeze...</info>");
            $this->runCommand("composer require laravel/breeze --dev", $output);

            // Construct Breeze installation command dynamically
            $breezeCommand = "php artisan breeze:install $stack";

            if (!empty($features)) {
                $breezeCommand .= ' ' . implode(' ', $features);
            }

            // Add test framework flag only for PHPUnit (since Pest is now the default)
            if ($testFramework === 'PHPUnit') {
                $breezeCommand .= ' --phpunit';
            }

            if ($testFramework === 'Pest') {
                $breezeCommand .= ' --pest';
            }

            $this->runCommand($breezeCommand, $output);
            $this->registerMakeInertiaCommand($output);
        }

        if ($installOrion) {
            $output->writeln("<info>Installing Orion...</info>");
            $this->runCommand("php artisan install:api", $output);
            $this->runCommand("composer require tailflow/laravel-orion", $output);
            $this->registerMakeOrionCommand($output);
        }

        if ($installSpatiePermission) {
            $output->writeln("<info>Installing Spatie Permission...</info>");
            $this->runCommand("composer require spatie/laravel-permission", $output);
            $this->runCommand("php artisan vendor:publish --provider='Spatie\Permission\PermissionServiceProvider'", $output);
            $this->updateMiddlewareConfig($output);
        }

        if ($installTanstackReactQueryQuestion) {
            $output->writeln("<info>Installing Tanstack React Query...</info>");
            $this->runCommand("npm i @tanstack/react-query", $output);

            $this->updateAppJsx($output);
        }

        $output->writeln('<info>Installation complete!</info>');
        return Command::SUCCESS;
    }

    private function updateAppJsx(OutputInterface $output): void
    {
        $appJsPath = getcwd() . '/resources/js/app.jsx'; // Fix path issue

        $newContent = <<<JSX
import "../css/app.css";
import "./bootstrap";

import { createInertiaApp } from "@inertiajs/react";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createRoot } from "react-dom/client";

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
    title: (title) => `\${title} - \${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/\${name}.jsx`,
            import.meta.glob("./Pages/**/*.jsx")
        ),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(
            <QueryClientProvider client={queryClient}>
                <App {...props} />
            </QueryClientProvider>
        );
    },
    progress: {
        color: "#4B5563",
    },
});
JSX;

        if (!file_exists(dirname($appJsPath))) {
            mkdir(dirname($appJsPath), 0777, true);
        }

        file_put_contents($appJsPath, $newContent);
        $output->writeln("✅ <info>Updated app.jsx with TanStack React Query setup.</info>");
    }



    private function updateMiddlewareConfig(OutputInterface $output): void
    {
        $filePath = "bootstrap/app.php";

        if (!file_exists($filePath)) {
            $output->writeln("⚠️ <error>bootstrap/app.php not found!</error>");
            return;
        }

        $middlewareSnippet = <<<PHP
        \$middleware->alias([
            'role' => \\Spatie\\Permission\\Middleware\\RoleMiddleware::class,
            'permission' => \\Spatie\\Permission\\Middleware\\PermissionMiddleware::class,
            'role_or_permission' => \\Spatie\\Permission\\Middleware\\RoleOrPermissionMiddleware::class,
        ]);
    PHP;

        $fileContent = file_get_contents($filePath);

        if (str_contains($fileContent, "'role' => \\Spatie\\Permission\\Middleware\\RoleMiddleware::class")) {
            $output->writeln("⚠️ <info>Spatie middleware already registered in bootstrap/app.php. Skipping...</info>");
            return;
        }

        $pattern = '/->withMiddleware\(function \(Middleware \$middleware\) \{(.*?)\}/s';

        if (preg_match($pattern, $fileContent, $matches)) {
            $updatedContent = preg_replace(
                $pattern,
                "->withMiddleware(function (Middleware \$middleware) {\n$1\n\n        // Spatie Permission Middleware\n        $middlewareSnippet\n    }",
                $fileContent
            );

            file_put_contents($filePath, $updatedContent);
            $output->writeln("✅ <info>Spatie middleware added successfully to bootstrap/app.php.</info>");
        } else {
            $output->writeln("⚠️ <error>Could not modify bootstrap/app.php. Please add middleware manually.</error>");
        }
    }


    private function runCommand(string $command, OutputInterface $output): void
    {
        $process = proc_open(
            $command,
            [
                0 => STDIN,
                1 => STDOUT,
                2 => STDERR,
            ],
            $pipes
        );

        if (is_resource($process)) {
            proc_close($process);
        }
    }

    private function registerMakeInertiaCommand(OutputInterface $output): void
    {
        $output->writeln("<info>Registering make:inertia command...</info>");

        $commandPath = "app/Console/Commands/MakeInertiaComponent.php";

        if (!file_exists($commandPath)) {
            if (!is_dir("app/Console/Commands")) {
                mkdir("app/Console/Commands", 0777, true);
            }

            // Write the command file
            file_put_contents($commandPath, $this->getMakeInertiaComponentTemplate());

            $output->writeln("✅ <info>make:inertia command added at $commandPath</info>");
        } else {
            $output->writeln("⚠️ <info>make:inertia command already exists. Skipping...</info>");
        }
    }

    private function registerMakeOrionCommand(OutputInterface $output): void
    {
        $output->writeln("<info>Registering make:orion command...</info>");

        $commandPath = "app/Console/Commands/MakeOrionController.php";

        if (!file_exists($commandPath)) {
            if (!is_dir("app/Console/Commands")) {
                mkdir("app/Console/Commands", 0777, true);
            }

            file_put_contents($commandPath, $this->getMakeOrionControllerTemplate());
            $output->writeln("✅ <info>make:orion command added at $commandPath</info>");
        } else {
            $output->writeln("⚠️ <info>make:orion command already exists. Skipping...</info>");
        }
    }

    private function getMakeInertiaComponentTemplate(): string
    {
        return <<<PHP
        <?php

        namespace App\Console\Commands;

        use Illuminate\Console\Command;
        use Illuminate\Filesystem\Filesystem;

        class MakeInertiaComponent extends Command
        {
            protected \$signature = 'make:inertia {path}';
            protected \$description = 'Create a new React component at the specified path';

            public function __construct()
            {
                parent::__construct();
            }

            public function handle()
            {
                \$path = \$this->argument('path');
                \$filesystem = new Filesystem();

                // Ensure the path is relative to the resources/js directory
                \$componentPath = resource_path("js/{\$path}.jsx");

                if (\$filesystem->exists(\$componentPath)) {
                    \$this->error("⚠️ Component at {\$path} already exists!");
                    return;
                }
                \$filesystem->ensureDirectoryExists(dirname(\$componentPath));
                \$filesystem->put(\$componentPath, \$this->getComponentTemplate(basename(\$path)));
                \$this->info("✅ React component created successfully at {\$componentPath}.");
                \$this->openFile(\$componentPath);
            }

            protected function getComponentTemplate(\$name)
            {
                return <<<JSX
                import React from 'react';

                const {\$name} = () => {
                    return (
                        <div>
                            {/* {\$name} component */}
                        </div>
                    );
                };

                export default {\$name};
                JSX;
            }

            protected function openFile(\$filePath)
            {
                if (PHP_OS_FAMILY === 'Windows') {
                    exec("start \"\" \"\$filePath\"");
                } elseif (PHP_OS_FAMILY === 'Linux') {
                    exec("xdg-open \"\$filePath\"");
                } elseif (PHP_OS_FAMILY === 'Darwin') { // macOS
                    exec("open \"\$filePath\"");
                }
            }
        }
        PHP;
    }

    private function getMakeOrionControllerTemplate(): string
    {
        return <<<PHP
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeOrionController extends Command
{
    protected \$signature = 'make:orion {name}';
    protected \$description = 'Generate a new Orion controller and its model if not exists';

    public function handle()
    {
        \$name = str_replace('\\\', '/', \$this->argument('name'));
        \$path = app_path("Http/Controllers/Orion/{\$name}.php");

        \$directory = dirname(\$path);
        \$className = basename(\$name);
        \$modelName = Str::singular(str_replace('Controller', '', class_basename(\$className)));

        if (!File::isDirectory(\$directory)) {
            File::makeDirectory(\$directory, 0755, true, true);
        }

        \$namespace = "App\\\Http\\\Controllers\\\Orion";
        \$namespace = rtrim(\$namespace, '\\\'); // Remove trailing slash if root
        \$controllerStub = "<?php\n\nnamespace {\$namespace};\n\nuse Orion\\Http\\Controllers\\Controller;\nuse App\\Models\\\{\$modelName};\n\nclass {\$className} extends Controller\n{\n    protected \\\$model = {\$modelName}::class;\n}\n";

        if (!File::exists(\$path)) {
            File::put(\$path, \$controllerStub);
            \$this->info("✅ Orion controller created: app/Http/Controllers/Orion/{\$name}.php");
        } else {
            \$this->error("⚠️ Controller already exists!");
        }

        \$routePath = base_path('routes/api.php');
        \$routeName = Str::plural(Str::kebab(\$modelName)); // Plural and kebab-case
        \$routeEntry = "\\nOrion::resource('{\$routeName}', \\\{\$namespace}\\\{\$className}::class);";

        // Ensure api.php exists and contains required imports
        if (!File::exists(\$routePath)) {
            File::put(\$routePath, "<?php\\n\\nuse Illuminate\\\Support\\\Facades\\\Route;\\nuse Orion\\\Facades\\\Orion;\\n\\n" . \$routeEntry);
        } else {
            \$routes = File::get(\$routePath);

            // Check if the route already exists
            if (!Str::contains(\$routes, \$routeEntry)) {
                File::append(\$routePath, "\\n" . \$routeEntry);
                \$this->info("✅ Route added to api.php: Orion::resource('{\$routeName}', \\\{\$namespace}\\\{\$className}::class);");
            } else {
                \$this->error("⚠️ Route already exists in api.php");
            }
        }
    }
}
PHP;
    }
}
