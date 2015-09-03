<?php

namespace Bdsm\Log;

interface Log
{
    /**
     * Get the migration log entries
     *
     * Get an associative array of migration ids and their status. The status
     * can be one of "done" or "skipped". Migrations that are not done should
     * not be in the list.
     *
     * @return array An associative array of migration ids and their status
     */
    public function get();

    /**
     * Drop a migration log entry
     *
     * @param string $migrationId The migration id
     */
    public function drop($migrationId);

    /**
     * Set a migration log entry
     *
     * The status can be one of "done" or "skipped".
     *
     * @param string $migrationId The migration id
     * @param string $status      The status, done/skipped
     */
    public function set($migrationId, $status);
}
