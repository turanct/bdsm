<?php

namespace Bdsm;

interface Migration
{
    /**
     * Migrate Up
     *
     * This method will be called whenever we're migrating up
     *
     * @param Database $database The database adapter
     */
    public function up(Database $database);

    /**
     * Migrate down
     *
     * This method will be called whenever we're migrating down
     *
     * @param Database $database The database adapter
     */
    public function down(Database $database);
}
