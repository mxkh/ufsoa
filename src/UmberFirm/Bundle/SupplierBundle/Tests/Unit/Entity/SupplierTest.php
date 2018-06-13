<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;

/**
 * Class SupplierTest
 *
 * @package UmberFirm\Bundle\SupplierBundle\Tests\Unit
 */
class SupplierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Supplier
     */
    private $supplier;

    /**
     * @var string
     */
    private $locale;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->supplier = new Supplier();
        $this->locale = $this->supplier->getCurrentLocale();
    }

    public function testName()
    {
        $this->assertInternalType('string', $this->supplier->getName());
        $this->assertInstanceOf(Supplier::class, $this->supplier->setName('Some name', $this->locale));
        $this->assertEquals('Some name', $this->supplier->getName());
        $this->assertInternalType('string', $this->supplier->getName());
        $this->assertInstanceOf(Supplier::class, $this->supplier->setName(null, $this->locale));
        $this->assertInternalType('string', $this->supplier->getName());
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameTypeError()
    {
        $this->supplier->setName(123, $this->locale);
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameTypeErrorLocale()
    {
        $this->supplier->setName('string', null);
    }

    public function testDescription()
    {
        $this->assertInternalType('string', $this->supplier->getDescription());
        $this->assertInstanceOf(Supplier::class, $this->supplier->setDescription('Some description', $this->locale));
        $this->assertEquals('Some description', $this->supplier->getDescription());
        $this->assertInternalType('string', $this->supplier->getDescription());
        $this->assertInstanceOf(Supplier::class, $this->supplier->setDescription(null, $this->locale));
        $this->assertInternalType('string', $this->supplier->getDescription());
    }

    /**
     * @expectedException \TypeError
     */
    public function testDescriptionTypeError()
    {
        $this->supplier->setDescription(123, $this->locale);
    }

    /**
     * @expectedException \TypeError
     */
    public function testDescriptionTypeErrorLocale()
    {
        $this->supplier->setDescription('string', null);
    }

    public function testIsActive()
    {
        $this->assertFalse($this->supplier->getIsActive());
        $this->assertInstanceOf(Supplier::class, $this->supplier->setIsActive(true));
        $this->assertTrue($this->supplier->getIsActive());
        $this->assertInstanceOf(Supplier::class, $this->supplier->setIsActive(null));
        $this->assertFalse($this->supplier->getIsActive());
    }

    /**
     * @expectedException \TypeError
     */
    public function testIsActiveTypeError()
    {
        $this->supplier->setIsActive('true');
    }

    public function testStores()
    {
        $store = new Store();
        $this->assertInstanceOf(Supplier::class, $this->supplier->addStore($store));
        $this->assertInstanceOf(ArrayCollection::class, $this->supplier->getStores());
        $this->assertTrue($this->supplier->getStores()->contains($store));
        $this->assertInstanceOf(Supplier::class, $this->supplier->removeStore($store));
        $this->assertFalse($this->supplier->getStores()->contains($store));
    }

    /**
     * @expectedException \TypeError
     */
    public function testStoresTypeError()
    {
        $this->supplier->addStore(null);
    }

    public function testShops()
    {
        $shop = new Shop();
        $this->assertInstanceOf(Supplier::class, $this->supplier->addShop($shop));
        $this->assertInstanceOf(ArrayCollection::class, $this->supplier->getShops());
        $this->assertTrue($this->supplier->getShops()->contains($shop));
        $this->assertInstanceOf(Supplier::class, $this->supplier->removeShop($shop));
        $this->assertFalse($this->supplier->getShops()->contains($shop));
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopsTypeError()
    {
        $this->supplier->addShop(null);
    }

    public function testUsername()
    {
        $this->assertInternalType('string', $this->supplier->getUsername());
        $this->assertInstanceOf(Supplier::class, $this->supplier->setUsername('username'));
        $this->assertEquals('username', $this->supplier->getUsername());
        $this->assertInternalType('string', $this->supplier->getUsername());
        $this->assertInstanceOf(Supplier::class, $this->supplier->setUsername(null));
        $this->assertInternalType('string', $this->supplier->getUsername());
    }

    /**
     * @expectedException \TypeError
     */
    public function testUsernameTypeError()
    {
        $this->supplier->setUsername(123);
    }

    public function testPassword()
    {
        $this->assertInternalType('string', $this->supplier->getPassword());
        $this->assertInstanceOf(Supplier::class, $this->supplier->setPassword('password'));
        $this->assertEquals('password', $this->supplier->getPassword());
        $this->assertInternalType('string', $this->supplier->getPassword());
        $this->assertInstanceOf(Supplier::class, $this->supplier->setPassword(null));
        $this->assertInternalType('string', $this->supplier->getPassword());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPasswordTypeError()
    {
        $this->supplier->setPassword(123);
    }

    public function testUserInterface()
    {
        $this->assertEquals(null, $this->supplier->getSalt());
        $this->assertEquals(null, $this->supplier->eraseCredentials());
        $this->assertInternalType('array', $this->supplier->getRoles());
    }

    public function testSerializable()
    {
        $this->supplier->setUsername('username');
        $this->supplier->setPassword('password');
        $serialized = $this->supplier->serialize();
        $this->supplier->setUsername(null);
        $this->supplier->setPassword(null);
        $this->assertInternalType('string', $serialized);
        $this->supplier->unserialize($serialized);
        $this->assertEquals('username', $this->supplier->getUsername());
        $this->assertEquals('password', $this->supplier->getPassword());
    }
}
