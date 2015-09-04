<?php

namespace Bdsm\Query;

class StatusTest extends \PHPUnit_Framework_TestCase
{
    function test_it_gets_current_status_data()
    {
        $locater = $this->getMockBuilder('\\Bdsm\\Locater\\Locater')->getMock();
        $locater
            ->method('findMigrations')
            ->willReturn(array(
                new DoneMigration1,
                new DoneMigration2,
                new NewMigration,
                new SkippedMigration,
            ));

        $log = $this->getMockBuilder('\\Bdsm\\Log\\Log')->getMock();
        $log
            ->method('get')
            ->willReturn(array(
                'Bdsm\\Query\\DoneMigration1' => 'done',
                'Bdsm\\Query\\DoneMigration2' => 'done',
                'Bdsm\\Query\\SkippedMigration' => 'skipped',
            ));

        $query = new Status($locater, $log);

        $this->assertEquals(
            array(
                'Bdsm\\Query\\DoneMigration1' => 'done',
                'Bdsm\\Query\\DoneMigration2' => 'done',
                'Bdsm\\Query\\NewMigration' => 'new',
                'Bdsm\\Query\\SkippedMigration' => 'skipped',
            ),
            $query->getResult()
        );
    }
}

class DoneMigration1 implements \Bdsm\Migration
{
    public function up(\Bdsm\Database $db)
    {
    }

    public function down(\Bdsm\Database $db)
    {
    }
}

class DoneMigration2 extends DoneMigration1 {}
class SkippedMigration extends DoneMigration1 {}
class NewMigration extends DoneMigration1 {}
