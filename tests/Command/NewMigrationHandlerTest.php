<?php

namespace Bdsm\Command;

class NewMigrationHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Bdsm\Exception\MigrationAlreadyExists
     */
    function test_it_throws_when_migration_already_exists()
    {
        $migrationsDirectory = __DIR__ . '/fixtures';

        $command = new NewMigration('Foo');
        $handler = new NewMigrationHandler($migrationsDirectory);
        $handler->handle($command);
    }

    function test_it_generates_a_migration_file()
    {
        $migrationsDirectory = __DIR__ . '/fixtures';

        $command = new NewMigration('Bar');
        $handler = new NewMigrationHandler($migrationsDirectory);
        $handler->handle($command);

        $expected = <<<CONTENT
<?php

use Bdsm\Migration;
use Bdsm\Database;

final class Bar implements Migration
{
    public function up(Database \$database)
    {
    }

    public function down(Database \$database)
    {
    }
}
CONTENT;

        $file = $migrationsDirectory . '/Bar.php';
        $fileContents = file_get_contents($file);
        $this->assertEquals($expected, $fileContents);

        unlink($file);
    }
}
