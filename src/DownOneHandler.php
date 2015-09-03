<?php

namespace Bdsm;

use Bdsm\Exception\MigrationDoesNotExist;
use Bdsm\Exception\MigrationDidNotRunYet;

final class DownOneHandler
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

    public function handle(DownOne $command)
    {
        $migrationId = $command->migrationId;

        if ($this->locater->has($migrationId) === false) {
            throw new MigrationDoesNotExist($migrationId);
        }

        $log = $this->log->get();
        if (
            array_key_exists($migrationId, $log) === false
            || $log[$migrationId] === 'skipped'
        ) {
            throw new MigrationDidNotRunYet($migrationId);
        }

        $migration = $this->locater->get($migrationId);
        $migration->down($this->database);
    }
}
