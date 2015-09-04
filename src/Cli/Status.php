<?php

namespace Bdsm\Cli;

use Bdsm\Query\Status as StatusQuery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Status extends Command
{
    protected $statusQuery;

    public function __construct($name, StatusQuery $statusQuery)
    {
        parent::__construct($name);

        $this->statusQuery = $statusQuery;
    }

    protected function configure()
    {
        $this
            ->setName('status')
            ->setDescription('Get the current migration status.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $status = $this->statusQuery->getResult();

        $doneMigrations = $this->filterByType($status, 'done');
        $skippedMigrations = $this->filterByType($status, 'skipped');
        $newMigrations = $this->filterByType($status, 'new');

        foreach ($doneMigrations as $doneMigration) {
            $output->writeln('<fg=green>✔︎</> ' . $doneMigration);
        }

        foreach ($skippedMigrations as $skippedMigration) {
            $output->writeln('<fg=yellow>o</> ' . $skippedMigration);
        }

        foreach ($newMigrations as $newMigration) {
            $output->writeln('<fg=red>✘</> ' . $newMigration);
        }
    }

    private function filterByType(array $migrations, $status)
    {
        $filteredList = array();

        foreach ($migrations as $migration => $migrationStatus) {
            if ($migrationStatus == $status) {
                $filteredList[] = $migration;
            }
        }

        return $filteredList;
    }
}
