<?php

namespace Bdsm\Command;

use Bdsm\Locater\Locater;
use Bdsm\Log\Log;
use Bdsm\Database;

final class UpAllHandler
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

    public function handle(UpAll $command)
    {
        $migrations = $this->locater->findMigrations();

        $log = $this->log->get();

        foreach ($migrations as $migration) {
            $migrationId = get_class($migration);

            if (array_key_exists($migrationId, $log) === true) {
                continue;
            }

            $migration->up($this->database);

            $this->log->set($migrationId, 'done');
        }
    }
}
