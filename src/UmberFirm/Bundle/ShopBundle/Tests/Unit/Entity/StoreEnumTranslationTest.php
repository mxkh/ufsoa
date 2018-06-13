<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\ShopBundle\Entity\StoreEnumTranslation;

/**
 * Class StoreEnumTranslationTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity
 */
class StoreEnumTranslationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var StoreEnumTranslation
     */
    private $storeEnumTranslation;

    /**
     *{@inheritdoc}
     */
    protected function setUp()
    {
        $this->storeEnumTranslation = new StoreEnumTranslation();
    }

    public function testName()
    {
        $this->assertInstanceOf(StoreEnumTranslation::class, $this->storeEnumTranslation->setName("100"));
        $this->assertInternalType('string', $this->storeEnumTranslation->getName());
        $this->assertEquals("100", $this->storeEnumTranslation->getName());
        $this->storeEnumTranslation->setName("100");
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongName()
    {
        $this->storeEnumTranslation->setName(123);
    }
}
