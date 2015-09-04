<?php

namespace Bdsm\Command;

final class NewMigration
{
    public $name;

    public function __construct($name = null)
    {
        $this->name = $name;
    }
}
