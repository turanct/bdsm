<?php

namespace Bdsm\Command;

class CommandBusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Exception
     */
    function test_it_associates_commands_with_their_handlers()
    {
        $bus = new CommandBus();
        $bus->register('Bdsm\\Command\\FooCommand', new FooCommandHandler());
        $bus->handle(new FooCommand());
    }

    /**
     * @expectedException Bdsm\Exception\HandlerNotFound
     */
    function test_it_throws_when_handler_not_found()
    {
        $bus = new CommandBus();
        $bus->handle(new FooCommand());
    }
}

final class FooCommand {}

final class FooCommandHandler
{
    public function handle(FooCommand $foo)
    {
        throw new \Exception('success');
    }
}
