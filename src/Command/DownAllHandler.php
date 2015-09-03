<?php

namespace Bdsm\Command;

use Bdsm\Locater\Locater;
use Bdsm\Log;
use Bdsm\Database;

final class DownAllHandler
{
    private $locater;
    private $log;
    private $database;

    public function __construct(
        Locater $locater,
        Log $log,
        Database $database
    ) {
        $this->locater = $locater;
        $this->log = $log;
        $this->database = $database;
    }

    public function handle(DownAll $command)
    {
        $migrations = $this->locater->findMigrations();

        $log = $this->log->get();

        foreach ($migrations as $migration) {
            $migrationId = get_class($migration);

            if (
                array_key_exists($migrationId, $log) === false
                || $log[$migrationId] === 'skipped'
            ) {
                continue;
            }

            $migration->down($this->database);

            $this->log->drop($migrationId);
        }
    }
}
