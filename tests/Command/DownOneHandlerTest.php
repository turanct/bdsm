<?php

namespace Bdsm\Command;

class DownOneHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Bdsm\Exception\MigrationDoesNotExist
     */
    function test_it_throws_when_given_migration_not_found()
    {
        $locater = $this->getMockBuilder('\\Bdsm\\Locater\\Locater')->getMock();
        $locater->method('has')->willReturn(false);

        $log = $this->getMockBuilder('\\Bdsm\\Log\\Log')->getMock();
        $database = $this->getMockBuilder('\\Bdsm\\Database')->getMock();

        $downone = new DownOne('Foo');
        $handler = new DownOneHandler($locater, $log, $database);
        $handler->handle($downone);
    }

    /**
     * @expectedException Bdsm\Exception\MigrationDidNotRunYet
     */
    function test_it_throws_when_migration_did_not_run_yet()
    {
        $locater = $this->getMockBuilder('\\Bdsm\\Locater\\Locater')->getMock();
        $locater->method('has')->willReturn(true);

        $log = $this->getMockBuilder('\\Bdsm\\Log\\Log')->getMock();
        $log->method('get')->willReturn(array());

        $database = $this->getMockBuilder('\\Bdsm\\Database')->getMock();

        $downone = new DownOne('Foo');
        $handler = new DownOneHandler($locater, $log, $database);
        $handler->handle($downone);
    }

    /**
     * @expectedException Bdsm\Exception\MigrationDidNotRunYet
     */
    function test_it_throws_when_migration_was_skipped()
    {
        $locater = $this->getMockBuilder('\\Bdsm\\Locater\\Locater')->getMock();
        $locater->method('has')->willReturn(true);

        $log = $this->getMockBuilder('\\Bdsm\\Log\\Log')->getMock();
        $log->method('get')->willReturn(array(
            'Foo' => 'skipped',
        ));

        $database = $this->getMockBuilder('\\Bdsm\\Database')->getMock();

        $downone = new DownOne('Foo');
        $handler = new DownOneHandler($locater, $log, $database);
        $handler->handle($downone);
    }

    function test_it_runs_the_down_migration()
    {
        $migration = $this->getMockBuilder('\\Bdsm\\Migration')->getMock();
        $migration
            ->expects($this->once())
            ->method('down');

        $locater = $this->getMockBuilder('\\Bdsm\\Locater\\Locater')->getMock();
        $locater
            ->method('has')
            ->with($this->equalTo('Foo'))
            ->willReturn(true);
        $locater
            ->method('get')
            ->with($this->equalTo('Foo'))
            ->willReturn($migration);

        $log = $this->getMockBuilder('\\Bdsm\\Log\\Log')->getMock();
        $log->method('get')->willReturn(array(
            'Foo' => 'done',
        ));
        $log
            ->expects($this->once())
            ->method('drop')
            ->with($this->equalTo('Foo'));

        $database = $this->getMockBuilder('\\Bdsm\\Database')->getMock();

        $downone = new DownOne('Foo');
        $handler = new DownOneHandler($locater, $log, $database);
        $handler->handle($downone);
    }
}
