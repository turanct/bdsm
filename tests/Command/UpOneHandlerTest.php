<?php

namespace Bdsm\Command;

class UpOneHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Bdsm\Exception\MigrationDoesNotExist
     */
    function test_it_throws_when_given_migration_not_found()
    {
        $locater = $this->getMockBuilder('\\Bdsm\\Locater\\Locater')->getMock();
        $locater->method('has')->willReturn(false);

        $log = $this->getMockBuilder('\\Bdsm\\Log')->getMock();
        $database = $this->getMockBuilder('\\Bdsm\\Database')->getMock();

        $upone = new UpOne('Foo');
        $handler = new UpOneHandler($locater, $log, $database);
        $handler->handle($upone);
    }

    /**
     * @expectedException Bdsm\Exception\MigrationAlreadyRan
     */
    function test_it_throws_when_migration_already_ran()
    {
        $locater = $this->getMockBuilder('\\Bdsm\\Locater\\Locater')->getMock();
        $locater->method('has')->willReturn(true);

        $log = $this->getMockBuilder('\\Bdsm\\Log')->getMock();
        $log->method('get')->willReturn(array(
            'Foo' => 'done',
        ));

        $database = $this->getMockBuilder('\\Bdsm\\Database')->getMock();

        $upone = new UpOne('Foo');
        $handler = new UpOneHandler($locater, $log, $database);
        $handler->handle($upone);
    }

    /**
     * @expectedException Exception
     */
    function test_it_runs_the_up_migration()
    {
        $migration = $this->getMockBuilder('\\Bdsm\\Migration')->getMock();
        $migration
            ->method('up')
            ->will($this->throwException(new \Exception('it works')));

        $locater = $this->getMockBuilder('\\Bdsm\\Locater\\Locater')->getMock();
        $locater
            ->method('has')
            ->with($this->equalTo('Foo'))
            ->willReturn(true);
        $locater
            ->method('get')
            ->with($this->equalTo('Foo'))
            ->willReturn($migration);

        $log = $this->getMockBuilder('\\Bdsm\\Log')->getMock();
        $log->method('get')->willReturn(array());

        $database = $this->getMockBuilder('\\Bdsm\\Database')->getMock();

        $upone = new UpOne('Foo');
        $handler = new UpOneHandler($locater, $log, $database);
        $handler->handle($upone);
    }
}
