<?php

namespace Bdsm\Command;

use Bdsm\Migration;
use Bdsm\Database;
use Exception;

class DownAllHandlerTest extends \PHPUnit_Framework_TestCase
{
    function test_it_runs_multiple_migrations()
    {
        $locater = $this->getMockBuilder('\\Bdsm\\Locater\\Locater')->getMock();
        $locater->method('findMigrations')->willReturn(array(
            new FirstDownMigration,
            new SecondDownMigration,
        ));

        $log = $this->getMockBuilder('\\Bdsm\\Log')->getMock();
        $log->method('get')->willReturn(array(
            'Bdsm\\Command\\FirstDownMigration' => 'done',
            'Bdsm\\Command\\SecondDownMigration' => 'done',
        ));
        $log
            ->expects($this->exactly(2))
            ->method('drop')
            ->withConsecutive(
                $this->equalTo('Bdsm\\Command\\FirstDownMigration'),
                $this->equalTo('Bdsm\\Command\\SecondDownMigration')
            );

        $database = $this->getMockBuilder('\\Bdsm\\Database')->getMock();
        $database
            ->expects($this->exactly(2))
            ->method('query');

        $upone = new DownAll('Foo');
        $handler = new DownAllHandler($locater, $log, $database);
        $handler->handle($upone);
    }

    function test_it_skips_not_done_and_skipped_migrations()
    {
        $locater = $this->getMockBuilder('\\Bdsm\\Locater\\Locater')->getMock();
        $locater->method('findMigrations')->willReturn(array(
            new NotDoneDownMigration,
            new SkippedDownMigration,
            new FirstDownMigration,
        ));

        $log = $this->getMockBuilder('\\Bdsm\\Log')->getMock();
        $log->method('get')->willReturn(array(
            'Bdsm\\Command\\FirstDownMigration' => 'done',
            'Bdsm\\Command\\SkippedDownMigration' => 'skipped',
        ));
        $log
            ->expects($this->once())
            ->method('drop')
            ->with($this->equalTo('Bdsm\\Command\\FirstDownMigration'));

        $database = $this->getMockBuilder('\\Bdsm\\Database')->getMock();
        $database
            ->expects($this->once())
            ->method('query');

        $upone = new DownAll('Foo');
        $handler = new DownAllHandler($locater, $log, $database);
        $handler->handle($upone);
    }
}

class NotDoneDownMigration implements Migration
{
    public function up(Database $database)
    {
        throw new Exception('failed');
    }

    public function down(Database $database)
    {
        throw new Exception('failed');
    }
}

final class SkippedDownMigration extends NotDoneDownMigration {}

class FirstDownMigration implements Migration
{
    public function up(Database $database)
    {
        throw new Exception('failed');
    }

    public function down(Database $database)
    {
        $database->query('foo');
    }
}

final class SecondDownMigration extends FirstDownMigration {}
