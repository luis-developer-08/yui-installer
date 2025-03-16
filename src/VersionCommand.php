<?php

namespace CustomLaravelInstaller;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class VersionCommand extends Command
{
    protected function configure()
    {
        $this->setName('v')
            ->setDescription('Displays the version of Yui Installer.')
            ->setHelp('This command shows the version of the Yui Installer.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // If any of the options are triggered, show the version
        // Read composer.json file
        $composerJson = json_decode(file_get_contents(__DIR__ . '/../composer.json'), true);

        // Check if the version key exists in composer.json
        $version = $composerJson['version'] ?? 'unknown';  // Default to 'unknown' if no version is set

        // Display the version
        $output->writeln("<info>Yui Installer version: v$version</info>");

        return Command::SUCCESS;
    }
}
