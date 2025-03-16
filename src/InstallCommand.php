<?php

namespace CustomLaravelInstaller;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Illuminate\Filesystem\Filesystem;

class InstallCommand extends Command
{
    protected function configure()
    {
        $this->setName('new')
            ->setDescription('Create a new Laravel project with optional Breeze authentication and Orion.')
            ->addArgument('name', InputArgument::OPTIONAL, 'The name of the Laravel project');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        // 1️⃣ Ask for project name if not provided
        $name = $input->getArgument('name');
        if (!$name) {
            $question = new Question('<question>Enter the project name:</question> ', 'laravel-project');
            $name = $helper->ask($input, $output, $question);
        }

        // 2️⃣ Ask if the user wants Breeze authentication
        $breezeQuestion = new ConfirmationQuestion('Do you want to install Breeze authentication? (y/n): ', false);
        $installBreeze = $helper->ask($input, $output, $breezeQuestion);

        // 3️⃣ Ask if the user wants Orion
        $orionQuestion = new ConfirmationQuestion('Do you want to install Orion? (y/n): ', false);
        $installOrion = $helper->ask($input, $output, $orionQuestion);

        // 4️⃣ Execute installation process based on user's choices
        $output->writeln("<info>Creating Laravel project: $name</info>");
        $this->runCommand("composer create-project laravel/laravel $name", $output);

        chdir($name);

        if ($installBreeze) {
            $output->writeln("<info>Installing Breeze...</info>");
            $this->runCommand("composer require laravel/breeze --dev", $output);
            $this->runCommand("php artisan breeze:install", $output);
            $this->registerMakeInertiaCommand($output);
        }

        if ($installOrion) {
            $output->writeln("<info>Installing Orion...</info>");
            $this->runCommand("composer require tailflow/laravel-orion", $output);
            $this->registerMakeOrionCommand($output);
        }

        $output->writeln('<info>Installation complete!</info>');
        return Command::SUCCESS;
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
