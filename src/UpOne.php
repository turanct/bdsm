<?php

namespace Bdsm;

final class UpOne
{
    public $migrationId;

    public function __construct($migrationId)
    {
        $this->migrationId = $migrationId;
    }
}
