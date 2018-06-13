<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ManufacturerBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class ManufacturerTest
 *
 * @package UmberFirm\Bundle\ManufacturerBundle\Tests\Unit\Entity
 */
class ManufacturerTest extends \PHPUnit_Framework_TestCase
{
    /** @var Manufacturer */
    private $manufacturer;

    /** @var string */
    private $locale;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->manufacturer = new Manufacturer();
        $this->locale = $this->manufacturer->getCurrentLocale();
    }

    public function testName()
    {
        $this->assertInstanceOf(Manufacturer::class, $this->manufacturer->setName('Gucci'));
        $this->assertEquals('Gucci', $this->manufacturer->getName());
        $this->assertInternalType('string', $this->manufacturer->getName());
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameWrongNameType()
    {
        $this->manufacturer->setName(213);
    }

    public function testAddress()
    {
        $this->assertInstanceOf(Manufacturer::class, $this->manufacturer->setAddress('address', $this->locale));
        $this->assertEquals('address', $this->manufacturer->getAddress());
        $this->assertInternalType('string', $this->manufacturer->getAddress());
    }

    /**
     * @expectedException \TypeError
     */
    public function testAddressNullable()
    {
        $this->assertEquals(null, $this->manufacturer->getAddress());
        $this->assertInternalType('null', $this->manufacturer->getAddress());
        $this->manufacturer->setAddress(null);
    }

    /**
     * @expectedException \TypeError
     */
    public function testAddressWrongNameType()
    {
        $this->manufacturer->setAddress(213, $this->locale);
    }

    /**
     * @expectedException \TypeError
     */
    public function testAddressWrongLocaleType()
    {
        $this->manufacturer->setAddress('address', 123);
    }

    public function testWebsite()
    {
        $this->assertInstanceOf(Manufacturer::class, $this->manufacturer->setWebsite('website.com'));
        $this->assertEquals('website.com', $this->manufacturer->getWebsite());
        $this->assertInternalType('string', $this->manufacturer->getWebsite());
    }

    public function testWebsiteNullable()
    {
        $this->assertEquals(null, $this->manufacturer->getWebsite());
        $this->manufacturer->setWebsite(null);
        $this->assertEquals(null, $this->manufacturer->getWebsite());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShops()
    {
        $shop = new Shop();
        $this->assertInstanceOf(Manufacturer::class, $this->manufacturer->addShop($shop));
        $this->assertInstanceOf(Collection::class, $this->manufacturer->getShops());
        $this->assertTrue($this->manufacturer->getShops()->contains($shop));
        $this->manufacturer->addShop(new \stdClass());
    }
}
