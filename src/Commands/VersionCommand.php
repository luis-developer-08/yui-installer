<?php

namespace YuiInstaller\Commands;

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
        $composerJson = json_decode(file_get_contents(__DIR__ . '/../../composer.json'), true);

        $version = $composerJson['version'] ?? 'unknown';

        $output->writeln("<info>Yui Installer version: $version</info>");

        return Command::SUCCESS;
    }
}
