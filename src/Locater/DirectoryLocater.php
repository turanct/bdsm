<?php

namespace Bdsm\Locater;

use Bdsm\Exception\MigrationDoesNotExist;

final class DirectoryLocater implements Locater
{
    private $directory;

    public function __construct($directory)
    {
        $this->directory = (string) $directory;
    }

    /**
     * Get a list of possible migrations
     *
     * @return array An ordered array of Migrations (old to new)
     */
    public function findMigrations()
    {
        $files = glob($this->directory . '/*.php');

        $instances = array_map(
            function($file) {
                require_once $file;

                $classname = basename($file, '.php');

                return class_exists($classname) ? new $classname : null;
            },
            $files
        );

        return array_values(array_filter($instances));
    }

    /**
     * Check if a given migration id exists
     *
     * @param string $migrationId
     *
     * @return bool
     */
    public function has($migrationId)
    {
        $file = $this->directory . '/' . $migrationId . '.php';

        if (!file_exists($file)) {
            return false;
        }

        require_once $file;

        return class_exists($migrationId);
    }

    /**
     * Get a migration by its id
     *
     * @param string $migrationId
     *
     * @return Migration
     */
    public function get($migrationId)
    {
        if (!$this->has($migrationId)) {
            throw new MigrationDoesNotExist($migrationId);
        }

        return new $migrationId;
    }
}
