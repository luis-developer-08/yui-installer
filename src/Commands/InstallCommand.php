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

        chdir($projectDir);

        // Regenerate the lock file to prevent outdated dependencies message
        $this->runCommand("composer update --lock", $output);

        $output->writeln("<info>✅ Composer lock file synced with composer.json</info>");

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

            // Save updated .env file
            file_put_contents($envPath, $envContent);
            $output->writeln("✅ <info>Updated .env file with database configuration.</info>");
        }

        $output->writeln("<info>Installing and building Node Dependencies...</info>");
        $this->runCommand("npm i", $output);
        $this->runCommand("npm run build", $output);
        $output->writeln("✅ <info>Done Installing and building Node Dependencies.</info>");

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
}
