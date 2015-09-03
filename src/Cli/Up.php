<?php

namespace Bdsm\Cli;

use Bdsm\Command\CommandBus;
use Bdsm\Command\UpAll;
use Bdsm\Command\UpOne;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Up extends Command
{
    protected $commandHandler;

    public function __construct($name, CommandBus $commandHandler) {
        parent::__construct($name);

        $this->commandHandler = $commandHandler;
    }

    protected function configure()
    {
        $this
            ->setName('up')
            ->setDescription('Migrate UP.')
            ->addArgument('migration-id', InputArgument::OPTIONAL, 'Only run a given migration', null)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $migrationId = $input->getArgument('migration-id');

        $migration = 'ALL migrations';
        $command = new UpAll();
        if ($migrationId != null) {
            $migration = 'migration ' . $migrationId;
            $command = new UpOne($migrationId);
        }

        $dialog = $this->getHelperSet()->get('dialog');
        $confirmed = $dialog->askConfirmation(
            $output,
            '<question>Do you want to migrate UP ' . $migration . '? (Y/n)</question>' . "\n",
            true
        );

        if ($confirmed === true) {
            $this->commandHandler->handle($command);

            $output->writeln('Migrated...');
        } else {
            $output->writeln('Aborting migration...');
        }
    }
}


