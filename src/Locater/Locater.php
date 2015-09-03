<?php

namespace Bdsm\Locater;

interface Locater
{
    /**
     * Get a list of possible migrations
     *
     * @return array An ordered array of Migrations (old to new)
     */
    public function findMigrations();

    /**
     * Check if a given migration id exists
     *
     * @param string $migrationId
     *
     * @return bool
     */
    public function has($migrationId);

    /**
     * Get a migration by its id
     *
     * @param string $migrationId
     *
     * @return Migration
     */
    public function get($migrationId);
}
