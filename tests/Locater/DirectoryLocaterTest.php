<?php

namespace Bdsm\Locater;

class DirectoryLocaterTest extends \PHPUnit_Framework_TestCase
{
    function test_it_is_a_locater()
    {
        $locater = new DirectoryLocater('foo');

        $this->assertTrue($locater instanceof Locater);
    }

    function test_it_returns_empty_array_when_no_migrations_found()
    {
        $dir = __DIR__ . '/fixtures/empty';

        $locater = new DirectoryLocater($dir);

        $this->assertEquals(array(), $locater->findMigrations());
    }

    function test_it_returns_migrations_instances_when_found()
    {
        $dir = __DIR__ . '/fixtures/non-empty';

        $locater = new DirectoryLocater($dir);

        require_once $dir . '/Foo.php';
        require_once $dir . '/Bar.php';

        $this->assertEquals(
            array(
                new \Bar(),
                new \Foo(),
            ),
            $locater->findMigrations()
        );
    }

    /**
     * @dataProvider dataHas()
     */
    function test_it_checks_for_availability_of_migrations($id, $expected)
    {
        $dir = __DIR__ . '/fixtures/non-empty';

        $locater = new DirectoryLocater($dir);

        $this->assertEquals($expected, $locater->has($id));
    }

    function dataHas()
    {
        return array(
            array('Foo', true),
            array('Bar', true),
            array('Baz', false),
            array('Qux', false),
        );
    }

    /**
     * @expectedException Bdsm\Exception\MigrationDoesNotExist
     */
    function test_it_throws_when_migration_does_not_exist()
    {
        $dir = __DIR__ . '/fixtures/non-empty';

        $locater = new DirectoryLocater($dir);

        $locater->get('Qux');
    }

    function test_it_returns_instance_when_migration_exists()
    {
        $dir = __DIR__ . '/fixtures/non-empty';

        $locater = new DirectoryLocater($dir);

        $this->assertEquals(new \Foo(), $locater->get('Foo'));
    }
}
