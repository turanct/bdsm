<?php

namespace Bdsm;

final class DownOne
{
    public $migrationId;

    public function __construct($migrationId)
    {
        $this->migrationId = $migrationId;
    }
}
