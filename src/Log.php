<?php

namespace Bdsm;

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
}
