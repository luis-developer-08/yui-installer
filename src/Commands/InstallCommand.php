<?php

namespace YuiInstaller\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Illuminate\Support\Collection;

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
        }

        if ($dbType === 'sqlite') {
            $databaseConfig = [
                'DB_CONNECTION' => 'sqlite',
                '# DB_HOST' => '127.0.0.1',
                '# DB_PORT' => '3306',
                '# DB_DATABASE' => $name,
                '# DB_USERNAME' => 'root',
                '# DB_PASSWORD' => '',
            ];
        }

        $uiProviders = $this->loadUiProviders($output);

        if (empty($uiProviders)) {
            $output->writeln("⚠️ No UI providers found in the JSON file.");
        }

        $uiNames = array_column($uiProviders, 'name');

        $uiProviderQuestion = new ChoiceQuestion(
            '<question>Which Ui provider?</question> (default: Hero UI)',
            $uiNames,
            1
        );

        $uiProviderName = $helper->ask($input, $output, $uiProviderQuestion);

        $selectedProvider = collect($uiProviders)->firstWhere('name', $uiProviderName);

        if (!$selectedProvider) {
            $output->writeln("❌ Invalid provider selection.");
        }

        $package = $selectedProvider['package'];

        $currentDir = getcwd();

        // Set the target directory for the new Laravel project (e.g., from the current directory)
        $projectDir = $currentDir . '/' . $name; // Full path to where you want the project



        $output->writeln("<info>Creating Yui-Laravel project in: $projectDir</info>");
        $this->runCommand("composer create-project $package $projectDir", $output);

        // return Command::SUCCESS;

        chdir($projectDir);

        // Path to .env file
        $envPath = getcwd() . '/.env';

        // Read .env file
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);

            $envContent = preg_replace('/^APP_NAME=Laravel.*/m', "APP_NAME=YUI", $envContent);

            // Ensure all database keys exist and are properly updated
            if ($dbType === 'mysql') {
                $envContent = preg_replace('/^DB_CONNECTION=.*/m', "DB_CONNECTION={$databaseConfig['DB_CONNECTION']}", $envContent);
                $envContent = preg_replace('/^# DB_HOST=.*/m', "DB_HOST={$databaseConfig['DB_HOST']}", $envContent);
                $envContent = preg_replace('/^# DB_PORT=.*/m', "DB_PORT={$databaseConfig['DB_PORT']}", $envContent);
                $envContent = preg_replace('/^# DB_DATABASE=.*/m', "DB_DATABASE={$databaseConfig['DB_DATABASE']}", $envContent);
                $envContent = preg_replace('/^# DB_USERNAME=.*/m', "DB_USERNAME={$databaseConfig['DB_USERNAME']}", $envContent);
                $envContent = preg_replace('/^# DB_PASSWORD=.*/m', "DB_PASSWORD={$databaseConfig['DB_PASSWORD']}", $envContent);
            }

            // Handle SQLite case: Ensure MySQL values are not commented, and SQLite values are commented
            // if ($dbType === 'sqlite') {
            //     $envContent = preg_replace('/^DB_CONNECTION=.*/m', "DB_CONNECTION=sqlite", $envContent);
            //     $envContent = preg_replace('/^DB_HOST=.*/m', "# DB_HOST=127.0.0.1", $envContent);
            //     $envContent = preg_replace('/^DB_PORT=.*/m', "# DB_PORT=3306", $envContent);
            //     $envContent = preg_replace('/^DB_DATABASE=.*/m', "# DB_DATABASE={$databaseConfig['DB_DATABASE']}", $envContent);
            //     $envContent = preg_replace('/^DB_USERNAME=.*/m', "# DB_USERNAME=root", $envContent);
            //     $envContent = preg_replace('/^DB_PASSWORD=.*/m', "# DB_PASSWORD=", $envContent);
            // }

            // Save updated .env file
            file_put_contents($envPath, $envContent);
            $output->writeln("✅ <info>Updated .env file with database configuration.</info>");
        }

        $output->writeln("<info>Installing and building Node Dependencies...</info>");
        $this->runCommand("npm i", $output);
        $this->runCommand("npm run build", $output);
        $output->writeln("✅ <info>Done Installing and building Node Dependencies.</info>");

        // $output->writeln("<info>Installing Breeze...</info>");
        // $this->runCommand("composer require laravel/breeze --dev", $output);
        // $this->runCommand("php artisan breeze:install react --pest", $output);
        // $this->registerMakeInertiaCommand($output);

        // $output->writeln("<info>Installing Orion...</info>");
        // $this->runCommand("php artisan install:api", $output);
        // $this->runCommand("composer require tailflow/laravel-orion", $output);
        // $this->registerMakeOrionCommand($output);

        // $output->writeln("<info>Installing Spatie Permission...</info>");
        // $this->runCommand("composer require spatie/laravel-permission", $output);
        // $this->runCommand("php artisan vendor:publish --provider='Spatie\Permission\PermissionServiceProvider'", $output);
        // $this->updateMiddlewareConfig($output);
        // $this->updateUserModel($output);

        // $output->writeln("<info>Installing Zustand, React Icons, Tanstack React Query and Hero UI ...</info>");
        // $output->writeln("<info>Installing Zustand, React Icons and Tanstack React Query...</info>");
        // $this->runCommand("npm i zustand react-icons @tanstack/react-query", $output);

        // $this->runCommand("npm i zustand react-icons @tanstack/react-query @heroui/react framer-motion", $output);
        // $this->updateTailwindConfig($output);
        // $this->replaceResourcesFolder($output);
        // $this->replaceRoutesFolder($output);
        // $this->copyImagesFolder($output);

        $output->writeln('<info>✅ Installation complete!</info>');
        return Command::SUCCESS;
    }

    private function loadUiProviders(OutputInterface $output): array
    {
        $jsonPath = __DIR__ . '/../ui-providers.json';  // Use new path

        if (!file_exists($jsonPath)) {
            $output->writeln("⚠️ ui-providers.json not found.");

            return [];
        }

        $jsonContent = file_get_contents($jsonPath);
        $data = json_decode($jsonContent, true);

        return $data['providers'] ?? [];
    }

    private function replaceResourcesFolder(OutputInterface $output): void
    {
        $projectPath = getcwd();
        $preparedResourcesPath = __DIR__ . '/../resources';  // Use new path

        $targetPath = $projectPath . '/resources';

        // Ensure the prepared folder exists
        if (!is_dir($preparedResourcesPath)) {
            $output->writeln("⚠️ <error>Prepared resources folder not found at: {$preparedResourcesPath}</error>");
            return;
        }

        // Remove the existing resources folder
        if (is_dir($targetPath)) {
            $output->writeln("🗑️ <info>Removing existing resources folder...</info>");
            $this->deleteFolder($targetPath);
        }

        // Copy the prepared resources folder
        $output->writeln("📁 <info>Copying prepared resources folder...</info>");
        $this->copyFolder($preparedResourcesPath, $targetPath);

        $output->writeln("✅ <info>Replaced resources folder successfully.</info>");
    }

    private function replaceRoutesFolder(OutputInterface $output): void
    {
        $projectPath = getcwd();
        $preparedRoutesPath = __DIR__ . '/../routes';  // Use new path

        $targetPath = $projectPath . '/routes';

        // Ensure the prepared folder exists
        if (!is_dir($preparedRoutesPath)) {
            $output->writeln("⚠️ <error>Prepared Routes folder not found at: {$preparedRoutesPath}</error>");
            return;
        }

        // Remove the existing Routes folder
        if (is_dir($targetPath)) {
            $output->writeln("🗑️ <info>Removing existing Routes folder...</info>");
            $this->deleteFolder($targetPath);
        }

        // Copy the prepared Routes folder
        $output->writeln("📁 <info>Copying prepared Routes folder...</info>");
        $this->copyFolder($preparedRoutesPath, $targetPath);

        $output->writeln("✅ <info>Replaced Routes folder successfully.</info>");
    }

    private function copyImagesFolder(OutputInterface $output): void
    {
        $projectPath = getcwd();
        $preparedImagesPath = __DIR__ . '/../images';  // Path to your prepared images folder

        $targetPath = $projectPath . '/public/images';

        // Ensure the prepared images folder exists
        if (!is_dir($preparedImagesPath)) {
            $output->writeln("⚠️ <error>Prepared images folder not found at: {$preparedImagesPath}</error>");
            return;
        }

        // Remove existing images folder if it exists
        if (is_dir($targetPath)) {
            $output->writeln("🗑️ <info>Removing existing images folder...</info>");
            $this->deleteFolder($targetPath);
        }

        // Copy the prepared images folder
        $output->writeln("📁 <info>Copying images folder into public...</info>");
        $this->copyFolder($preparedImagesPath, $targetPath);

        $output->writeln("✅ <info>Copied images folder successfully into public.</info>");
    }

    private function deleteFolder(string $folder): void
    {
        if (!is_dir($folder)) {
            return;
        }

        $files = array_diff(scandir($folder), ['.', '..']);

        foreach ($files as $file) {
            $filePath = "$folder/$file";
            if (is_dir($filePath)) {
                $this->deleteFolder($filePath);
            } else {
                unlink($filePath);
            }
        }

        rmdir($folder);
    }

    /**
     * Recursively copies the prepared folder into the Laravel project.
     */
    private function copyFolder(string $source, string $destination): void
    {
        if (!is_dir($destination)) {
            mkdir($destination, 0777, true);
        }

        $files = scandir($source);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $srcFile = $source . '/' . $file;
            $destFile = $destination . '/' . $file;

            if (is_dir($srcFile)) {
                $this->copyFolder($srcFile, $destFile);
            } else {
                copy($srcFile, $destFile);
            }
        }
    }

    private function updateTailwindConfig(OutputInterface $output): void
    {
        $appJsPath = getcwd() . '/tailwind.config.js'; // Path to app.jsx
        $stubPath = __DIR__ . '/../Stubs/UpdatedTailwindConfig.stub';  // Use new path

        if (!file_exists($stubPath)) {
            $output->writeln('<error>Stub file not found!</error>');
            return;
        }

        $newContent = file_get_contents($stubPath);

        if (!file_exists(dirname($appJsPath))) {
            mkdir(dirname($appJsPath), 0777, true);
        }

        file_put_contents($appJsPath, $newContent);
        $output->writeln("✅ <info>Updated tailwind.config.js with HeroUI setup.</info>");
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

    private function updateUserModel(OutputInterface $output): void
    {
        $filePath = getcwd() . "/app/Models/User.php";  // Use absolute path

        if (!file_exists($filePath)) {
            $output->writeln("⚠️ <error>app/Models/User.php not found!</error>");
            return;
        }

        $fileContent = file_get_contents($filePath);

        // Check if the traits are already included
        if (str_contains($fileContent, "HasRoles") && str_contains($fileContent, "HasApiTokens")) {
            $output->writeln("⚠️ <info>User Model already updated in app/Models/User.php. Skipping...</info>");
            return;
        }

        // 1️⃣ Add the traits to the existing trait line
        $traitPattern = '/use\s+HasFactory,\s*Notifiable(?:,\s*HasApiTokens)?;/';
        $traitReplacement = 'use HasFactory, Notifiable, HasApiTokens, HasRoles;';

        if (preg_match($traitPattern, $fileContent)) {
            $fileContent = preg_replace($traitPattern, $traitReplacement, $fileContent);
        } else {
            $output->writeln("⚠️ <error>Could not find the trait declaration in app/Models/User.php. Please add the traits manually.</error>");
        }

        // 2️⃣ Add the new `use` statements after `use Illuminate\Notifications\Notifiable;`
        $usePattern = '/use\s+Illuminate\\\Notifications\\\Notifiable;/';
        $useReplacement = <<<PHP
        use Illuminate\Notifications\Notifiable;
        use Laravel\Sanctum\HasApiTokens;
        use Spatie\Permission\Traits\HasRoles;
        PHP;

        if (preg_match($usePattern, $fileContent)) {
            $fileContent = preg_replace($usePattern, $useReplacement, $fileContent);
        } else {
            $output->writeln("⚠️ <error>Could not find the Notifiable import in app/Models/User.php. Please add the imports manually.</error>");
        }

        // 3️⃣ Save the updated content back to the file
        file_put_contents($filePath, $fileContent);
        $output->writeln("✅ <info>User Model updated successfully in app/Models/User.php with traits and imports.</info>");
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
        $stubPath = __DIR__ . '/../Stubs/MakeInertiaComponent.stub';  // Use new path

        if (!file_exists($commandPath)) {
            if (!is_dir("app/Console/Commands")) {
                mkdir("app/Console/Commands", 0777, true);
            }

            // Use the stub content from the new location
            file_put_contents($commandPath, file_get_contents($stubPath));
            $output->writeln("✅ <info>make:inertia command added at $commandPath</info>");
        } else {
            $output->writeln("⚠️ <info>make:inertia command already exists. Skipping...</info>");
        }
    }

    private function registerMakeOrionCommand(OutputInterface $output): void
    {
        $output->writeln("<info>Registering make:orion command...</info>");

        $commandPath = "app/Console/Commands/MakeOrionController.php";
        $stubPath = __DIR__ . '/../Stubs/MakeOrionController.stub';  // Use new path

        if (!file_exists($commandPath)) {
            if (!is_dir("app/Console/Commands")) {
                mkdir("app/Console/Commands", 0777, true);
            }

            file_put_contents($commandPath, file_get_contents($stubPath));
            $output->writeln("✅ <info>make:orion command added at $commandPath</info>");
        } else {
            $output->writeln("⚠️ <info>make:orion command already exists. Skipping...</info>");
        }
    }
}
