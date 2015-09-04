<?php

namespace Bdsm\Command;

use Bdsm\Exception\MigrationAlreadyExists;

final class NewMigrationHandler
{
    private $migrationDirectory;

    public function __construct($migrationDirectory)
    {
        $this->migrationDirectory = $migrationDirectory;
    }

    public function handle(NewMigration $command)
    {
        $fileContent = <<<CONTENT
<?php

use Bdsm\Migration;
use Bdsm\Database;

final class %s implements Migration
{
    public function up(Database \$database)
    {
    }

    public function down(Database \$database)
    {
    }
}
CONTENT;

        $fileContent = sprintf($fileContent, $command->name);
        $fileName = $this->migrationDirectory . '/' . $command->name . '.php';

        if (file_exists($fileName)) {
            throw new MigrationAlreadyExists($command->name);
        }

        file_put_contents($fileName, $fileContent);
    }
}
