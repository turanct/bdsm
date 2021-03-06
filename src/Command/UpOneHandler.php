<?php

namespace Bdsm\Command;

use Bdsm\Locater\Locater;
use Bdsm\Log\Log;
use Bdsm\Database;
use Bdsm\Exception\MigrationDoesNotExist;
use Bdsm\Exception\MigrationAlreadyRan;

final class UpOneHandler
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

    public function handle(UpOne $command)
    {
        $migrationId = $command->migrationId;

        if ($this->locater->has($migrationId) === false) {
            throw new MigrationDoesNotExist($migrationId);
        }

        if (array_key_exists($migrationId, $this->log->get()) === true) {
            throw new MigrationAlreadyRan($migrationId);
        }

        $migration = $this->locater->get($migrationId);
        $migration->up($this->database);

        $this->log->set($migrationId, 'done');
    }
}
