<?php

namespace Bdsm\Log;

use Bdsm\Exception\CouldNotAccessLog;

final class JsonLog implements Log
{
    private $path;

    public function __construct($path)
    {
        if (!file_exists($path) || !is_file($path)) {
            throw new CouldNotAccessLog($path);
        }

        $this->path = (string) $path;
    }

    /**
     * Get the migration log entries
     *
     * Get an associative array of migration ids and their status. The status
     * can be one of "done" or "skipped". Migrations that are not done should
     * not be in the list.
     *
     * @return array An associative array of migration ids and their status
     */
    public function get()
    {
        return json_decode(file_get_contents($this->path), true);
    }

    /**
     * Drop a migration log entry
     *
     * @param string $migrationId The migration id
     */
    public function drop($migrationId)
    {
        $log = $this->get();

        if (!isset($log[$migrationId])) {
            return;
        }

        unset($log[$migrationId]);

        file_put_contents($this->path, json_encode($log));
    }

    /**
     * Set a migration log entry
     *
     * The status can be one of "done" or "skipped".
     *
     * @param string $migrationId The migration id
     * @param string $status      The status, done/skipped
     */
    public function set($migrationId, $status)
    {
        $log = $this->get();

        $log[$migrationId] = $status;

        file_put_contents($this->path, json_encode($log));
    }
}
