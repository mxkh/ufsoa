<?php

namespace UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller;

use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;

/**
 * Class DatabaseSchemaTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller
 */
class DatabaseSchemaTest extends BaseTestCase
{
    public static function setUpBeforeClass()
    {
        self::runAppConsoleCommand('doctrine:database:drop -q --force --connection=mysql');
        self::runAppConsoleCommand('doctrine:database:create -q --connection=mysql');
        self::runAppConsoleCommand('doctrine:migrations:migrate -q --em=mysql');
    }

    public static function tearDownAfterClass()
    {
        self::runAppConsoleCommand('doctrine:database:drop -q --force --connection=mysql');
    }

    /**
     * Test database schema
     */
    public function testSchema()
    {
        $code = self::runAppConsoleCommand('doctrine:schema:validate -q --em=mysql');
        $this->assertEquals(0, $code, 'Database schema is not valid. Check DoctrineMigrations directory.');
    }
}
