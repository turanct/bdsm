<?php

namespace Bdsm\Cli;

use Bdsm\Command\CommandBus;
use Bdsm\Command\NewMigration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Create extends Command
{
    protected $commandHandler;

    public function __construct($name, CommandBus $commandHandler)
    {
        parent::__construct($name);

        $this->commandHandler = $commandHandler;
    }

    protected function configure()
    {
        $this
            ->setName('create')
            ->setDescription('Create a migration.')
            ->addArgument('migration-id', InputArgument::OPTIONAL, 'Specify the newly created migration\'s name', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $migrationId = $input->getArgument('migration-id') ?: 'Migration' . time();
        $command = new NewMigration($migrationId);

        $this->commandHandler->handle($command);

        $output->writeln('Migration "' . $migrationId . '" created...');
    }
}
