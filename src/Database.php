<?php

namespace Bdsm;

interface Database
{
    /**
     * Query the database
     *
     * @param string $query The query to execute
     */
    public function query($query);
}
