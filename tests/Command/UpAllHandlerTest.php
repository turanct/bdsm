<?php

namespace Bdsm\Command;

use Bdsm\Migration;
use Bdsm\Database;
use Exception;

class UpAllHandlerTest extends \PHPUnit_Framework_TestCase
{
    function test_it_runs_multiple_migrations()
    {
        $locater = $this->getMockBuilder('\\Bdsm\\Locater\\Locater')->getMock();
        $locater->method('findMigrations')->willReturn(array(
            new FirstMigration,
            new SecondMigration,
        ));

        $log = $this->getMockBuilder('\\Bdsm\\Log\\Log')->getMock();
        $log->method('get')->willReturn(array());
        $log
            ->expects($this->exactly(2))
            ->method('set')
            ->withConsecutive(
                array('Bdsm\\Command\\FirstMigration', 'done'),
                array('Bdsm\\Command\\SecondMigration', 'done')
            );

        $database = $this->getMockBuilder('\\Bdsm\\Database')->getMock();
        $database
            ->expects($this->exactly(2))
            ->method('query');

        $upone = new UpAll('Foo');
        $handler = new UpAllHandler($locater, $log, $database);
        $handler->handle($upone);
    }

    function test_it_skips_done_and_skipped_migrations()
    {
        $locater = $this->getMockBuilder('\\Bdsm\\Locater\\Locater')->getMock();
        $locater->method('findMigrations')->willReturn(array(
            new DoneMigration,
            new SkippedMigration,
            new FirstMigration,
        ));

        $log = $this->getMockBuilder('\\Bdsm\\Log\\Log')->getMock();
        $log->method('get')->willReturn(array(
            'Bdsm\\Command\\DoneMigration' => 'done',
            'Bdsm\\Command\\SkippedMigration' => 'skipped',
        ));
        $log
            ->expects($this->once())
            ->method('set')
            ->with('Bdsm\\Command\\FirstMigration', 'done');

        $database = $this->getMockBuilder('\\Bdsm\\Database')->getMock();
        $database
            ->expects($this->once())
            ->method('query');

        $upone = new UpAll('Foo');
        $handler = new UpAllHandler($locater, $log, $database);
        $handler->handle($upone);
    }
}

class DoneMigration implements Migration
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

final class SkippedMigration extends DoneMigration {}

class FirstMigration implements Migration
{
    public function up(Database $database)
    {
        $database->query('foo');
    }

    public function down(Database $database)
    {
        throw new Exception('failed');
    }
}

final class SecondMigration extends FirstMigration {}
