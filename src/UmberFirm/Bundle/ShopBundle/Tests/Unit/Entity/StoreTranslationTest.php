<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\ShopBundle\Entity\StoreTranslation;

/**
 * Class StoreTranslationTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity
 */
class StoreTranslationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var StoreTranslation
     */
    private $storeTranslation;

    /**
     *{@inheritdoc}
     */
    protected function setUp()
    {
        $this->storeTranslation = new StoreTranslation();
    }

    public function testAddress()
    {
        $this->assertInstanceOf(
            StoreTranslation::class,
            $this->storeTranslation->setAddress("Harkivske shose 201/203")
        );
        $this->assertInternalType('string', $this->storeTranslation->getAddress());
        $this->assertEquals("Harkivske shose 201/203", $this->storeTranslation->getAddress());
        $this->storeTranslation->setAddress("Harkivske shose 201/203");
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongAddress()
    {
        $this->storeTranslation->setAddress(123);
    }

    /**
     * @expectedException \TypeError
     */
    public function testNullAddress()
    {
        $this->storeTranslation->setAddress();
    }

    public function testAlias()
    {
        $this->assertInstanceOf(
            StoreTranslation::class,
            $this->storeTranslation->setSchedule("пн. - вc. с 10:00 до 22:00")
        );
        $this->assertInternalType('string', $this->storeTranslation->getSchedule());
        $this->assertEquals("пн. - вc. с 10:00 до 22:00", $this->storeTranslation->getSchedule());
        $this->storeTranslation->setSchedule("пн. - вc. с 10:00 до 22:00");
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongAlias()
    {
        $this->storeTranslation->setSchedule(123);
    }

    /**
     * @expectedException \TypeError
     */
    public function testNullAlias()
    {
        $this->storeTranslation->setSchedule();
    }

    public function testDescription()
    {
        $this->assertInstanceOf(StoreTranslation::class, $this->storeTranslation->setDescription("100"));
        $this->assertInternalType('string', $this->storeTranslation->getDescription());
        $this->assertEquals("100", $this->storeTranslation->getDescription());
        $this->storeTranslation->setDescription("100");
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongTypeDescription()
    {
        $this->storeTranslation->setDescription(123);
    }
}
