<?php

namespace Bdsm\Command;

class DownOneHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Bdsm\Exception\MigrationDoesNotExist
     */
    function test_it_throws_when_given_migration_not_found()
    {
        $locater = $this->getMockBuilder('\\Bdsm\\Locater')->getMock();
        $locater->method('has')->willReturn(false);

        $log = $this->getMockBuilder('\\Bdsm\\Log')->getMock();
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
        $locater = $this->getMockBuilder('\\Bdsm\\Locater')->getMock();
        $locater->method('has')->willReturn(true);

        $log = $this->getMockBuilder('\\Bdsm\\Log')->getMock();
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
        $locater = $this->getMockBuilder('\\Bdsm\\Locater')->getMock();
        $locater->method('has')->willReturn(true);

        $log = $this->getMockBuilder('\\Bdsm\\Log')->getMock();
        $log->method('get')->willReturn(array(
            'Foo' => 'skipped',
        ));

        $database = $this->getMockBuilder('\\Bdsm\\Database')->getMock();

        $downone = new DownOne('Foo');
        $handler = new DownOneHandler($locater, $log, $database);
        $handler->handle($downone);
    }

    /**
     * @expectedException Exception
     */
    function test_it_runs_the_down_migration()
    {
        $migration = $this->getMockBuilder('\\Bdsm\\Migration')->getMock();
        $migration
            ->method('down')
            ->will($this->throwException(new \Exception('it works')));

        $locater = $this->getMockBuilder('\\Bdsm\\Locater')->getMock();
        $locater
            ->method('has')
            ->with($this->equalTo('Foo'))
            ->willReturn(true);
        $locater
            ->method('get')
            ->with($this->equalTo('Foo'))
            ->willReturn($migration);

        $log = $this->getMockBuilder('\\Bdsm\\Log')->getMock();
        $log->method('get')->willReturn(array(
            'Foo' => 'done',
        ));

        $database = $this->getMockBuilder('\\Bdsm\\Database')->getMock();

        $downone = new DownOne('Foo');
        $handler = new DownOneHandler($locater, $log, $database);
        $handler->handle($downone);
    }
}
