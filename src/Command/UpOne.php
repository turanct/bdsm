<?php

namespace Bdsm\Command;

final class UpOne
{
    public $migrationId;

    public function __construct($migrationId)
    {
        $this->migrationId = $migrationId;
    }
}
