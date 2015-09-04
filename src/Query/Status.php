<?php

namespace Bdsm\Query;

use Bdsm\Locater\Locater;
use Bdsm\Log\Log;

final class Status
{
    private $locater;
    private $log;

    public function __construct(Locater $locater, Log $log)
    {
        $this->locater = $locater;
        $this->log = $log;
    }

    public function getResult()
    {
        $migrations = $this->locater->findMigrations();
        $logs = $this->log->get();

        foreach ($migrations as $migration) {
            $class = get_class($migration);

            if (!isset($logs[$class])) {
                $logs[$class] = 'new';
            }
        }

        ksort($logs);

        return $logs;
    }
}
