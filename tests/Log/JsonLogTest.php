<?php

namespace Bdsm\Log;

class JsonLogTest extends \PHPUnit_Framework_TestCase
{
    function test_it_is_a_log()
    {
        $log = new JsonLog(__DIR__ . '/fixtures/foo.json');

        $this->assertTrue($log instanceof Log);
    }

    /**
     * @expectedException Bdsm\Exception\CouldNotAccessLog
     */
    function test_it_throws_when_unknown_log()
    {
        $log = new JsonLog(__DIR__ . '/fixtures/unexisting.json');
    }

    function test_it_gets_the_list_of_logs()
    {
        $log = new JsonLog(__DIR__ . '/fixtures/foo.json');

        $this->assertEquals(
            array('Foo' => 'done', 'Bar' => 'done', 'Baz' => 'skipped'),
            $log->get()
        );
    }

    function test_it_drops_a_log()
    {
        $log = new JsonLog(__DIR__ . '/fixtures/foo.json');

        $log->drop('Baz');

        $this->assertEquals(
            array('Foo' => 'done', 'Bar' => 'done'),
            $log->get()
        );
    }

    function test_it_sets_a_log()
    {
        $log = new JsonLog(__DIR__ . '/fixtures/foo.json');

        $log->set('Baz', 'skipped');
        $log->set('Bar', 'skipped');

        $this->assertEquals(
            array('Foo' => 'done', 'Bar' => 'skipped', 'Baz' => 'skipped'),
            $log->get()
        );

        $log->set('Bar', 'done');

        $this->assertEquals(
            array('Foo' => 'done', 'Bar' => 'done', 'Baz' => 'skipped'),
            $log->get()
        );
    }
}
