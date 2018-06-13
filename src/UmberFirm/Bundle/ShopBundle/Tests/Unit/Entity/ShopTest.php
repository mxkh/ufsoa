<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\CategoryBundle\Entity\Category;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;
use UmberFirm\Bundle\ShopBundle\Entity\ShopSettings;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;

/**
 * Class ShopTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity
 */
class ShopTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Shop
     */
    private $shop;

    /**
     *{@inheritdoc}
     */
    protected function setUp()
    {
        $this->shop = new Shop();
    }

    public function testName()
    {
        $this->assertInstanceOf(Shop::class, $this->shop->setName("shop"));
        $this->assertInternalType('string', $this->shop->getName());
        $this->assertEquals("shop", $this->shop->getName());
        $this->shop->setName("shop");
    }

    public function testStores()
    {
        $store = new Store();
        $this->assertInstanceOf(Shop::class, $this->shop->addStore($store));
        $this->assertInstanceOf(Collection::class, $this->shop->getStores());
        $this->assertInstanceOf(Store::class, $this->shop->getStores()->first());
        $this->assertEquals($store, $this->shop->getStores()->first());
        $this->shop->addStore($store);
    }

    public function testAddStore()
    {
        /** @var Store|\PHPUnit_Framework_MockObject_MockObject $order */
        $store = $this->createMock(Store::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $stores = $this->createMock(Collection::class);
        $stores
            ->expects($this->once())
            ->method('contains')
            ->with($store)
            ->willReturn(false);
        $stores
            ->expects($this->once())
            ->method('add')
            ->with($store);
        $store
            ->expects($this->once())
            ->method('addShop');

        $ordersReflect = new \ReflectionProperty(Shop::class, 'stores');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->shop, $stores);

        $this->shop->addStore($store);
    }

    public function testAddStoreExists()
    {
        /** @var Store|\PHPUnit_Framework_MockObject_MockObject $order */
        $store = $this->createMock(Store::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $stores = $this->createMock(Collection::class);
        $stores
            ->expects($this->once())
            ->method('contains')
            ->with($store)
            ->willReturn(true);
        $stores
            ->expects($this->never())
            ->method('add');
        $store
            ->expects($this->never())
            ->method('addShop');

        $ordersReflect = new \ReflectionProperty(Shop::class, 'stores');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->shop, $stores);

        $this->shop->addStore($store);
    }

    public function testShopGroup()
    {
        $shopGroup = new ShopGroup();
        $this->assertInstanceOf(Shop::class, $this->shop->setShopGroup($shopGroup));
        $this->assertInstanceOf(ShopGroup::class, $this->shop->getShopGroup());
        $this->assertEquals($shopGroup, $this->shop->getShopGroup());
        $this->shop->setShopGroup($shopGroup);
    }

    public function testSetShopGroup()
    {
        /** @var ShopGroup|\PHPUnit_Framework_MockObject_MockObject $order */
        $shopGroup = $this->createMock(ShopGroup::class);

        $shopGroup
            ->expects($this->once())
            ->method('addShop');

        $ordersReflect = new \ReflectionProperty(Shop::class, 'shopGroup');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->shop, $shopGroup);

        $this->shop->setShopGroup($shopGroup);
    }

    public function testManufacturers()
    {
        $manufacturer = new Manufacturer();
        $this->assertInstanceOf(Shop::class, $this->shop->addManufacturer($manufacturer));
        $this->assertInstanceOf(Collection::class, $this->shop->getManufacturers());
        $this->assertEquals($manufacturer, $this->shop->getManufacturers()->first());
        $this->shop->addManufacturer($manufacturer);
    }

    public function testAddManufacturer()
    {
        /** @var Manufacturer|\PHPUnit_Framework_MockObject_MockObject $order */
        $manufacturer = $this->createMock(Manufacturer::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $manufacturers = $this->createMock(Collection::class);
        $manufacturers
            ->expects($this->once())
            ->method('contains')
            ->with($manufacturer)
            ->willReturn(false);
        $manufacturers
            ->expects($this->once())
            ->method('add')
            ->with($manufacturer);
        $manufacturer
            ->expects($this->once())
            ->method('addShop');

        $ordersReflect = new \ReflectionProperty(Shop::class, 'manufacturers');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->shop, $manufacturers);

        $this->shop->addManufacturer($manufacturer);
    }

    public function testAddManufacturerExists()
    {
        /** @var Manufacturer|\PHPUnit_Framework_MockObject_MockObject $order */
        $manufacturer = $this->createMock(Manufacturer::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $manufacturers = $this->createMock(Collection::class);
        $manufacturers
            ->expects($this->once())
            ->method('contains')
            ->with($manufacturer)
            ->willReturn(true);
        $manufacturers
            ->expects($this->never())
            ->method('add');
        $manufacturer
            ->expects($this->never())
            ->method('addShop');

        $ordersReflect = new \ReflectionProperty(Shop::class, 'manufacturers');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->shop, $manufacturers);

        $this->shop->addManufacturer($manufacturer);
    }

    public function testSuppliers()
    {
        $supplier = new Supplier();
        $this->assertInstanceOf(Shop::class, $this->shop->addSupplier($supplier));
        $this->assertInstanceOf(Collection::class, $this->shop->getSuppliers());
        $this->assertEquals($supplier, $this->shop->getSuppliers()->first());
        $this->shop->addSupplier($supplier);
    }

    public function testAddSupplier()
    {
        /** @var Supplier|\PHPUnit_Framework_MockObject_MockObject $order */
        $supplier = $this->createMock(Supplier::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $suppliers = $this->createMock(Collection::class);
        $suppliers
            ->expects($this->once())
            ->method('contains')
            ->with($supplier)
            ->willReturn(false);
        $suppliers
            ->expects($this->once())
            ->method('add')
            ->with($supplier);
        $supplier
            ->expects($this->once())
            ->method('addShop');

        $ordersReflect = new \ReflectionProperty(Shop::class, 'suppliers');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->shop, $suppliers);

        $this->shop->addSupplier($supplier);
    }

    public function testAddSupplierExists()
    {
        /** @var Supplier|\PHPUnit_Framework_MockObject_MockObject $order */
        $supplier = $this->createMock(Supplier::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $suppliers = $this->createMock(Collection::class);
        $suppliers
            ->expects($this->once())
            ->method('contains')
            ->with($supplier)
            ->willReturn(true);
        $suppliers
            ->expects($this->never())
            ->method('add');
        $supplier
            ->expects($this->never())
            ->method('addShop');

        $ordersReflect = new \ReflectionProperty(Shop::class, 'suppliers');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->shop, $suppliers);

        $this->shop->addSupplier($supplier);
    }

    public function testShopSettings()
    {
        $shopSettings = new ShopSettings();
        $this->assertInstanceOf(Shop::class, $this->shop->addShopSettings($shopSettings));
        $this->assertInstanceOf(Collection::class, $this->shop->getShopSettings());
        $this->assertEquals($shopSettings, $this->shop->getShopSettings()->first());
        $this->shop->addShopSettings($shopSettings);
    }

    public function testAddShopSettings()
    {
        /** @var ShopSettings|\PHPUnit_Framework_MockObject_MockObject $order */
        $shopSettings = $this->createMock(ShopSettings::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $shopSettingses = $this->createMock(Collection::class);
        $shopSettingses
            ->expects($this->once())
            ->method('contains')
            ->with($shopSettings)
            ->willReturn(false);
        $shopSettingses
            ->expects($this->once())
            ->method('add')
            ->with($shopSettings);
        $shopSettings
            ->expects($this->once())
            ->method('setShop');

        $ordersReflect = new \ReflectionProperty(Shop::class, 'shopSettings');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->shop, $shopSettingses);

        $this->shop->addShopSettings($shopSettings);
    }

    public function testAddShopSettingsExists()
    {
        /** @var ShopSettings|\PHPUnit_Framework_MockObject_MockObject $order */
        $shopSettings = $this->createMock(ShopSettings::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $shopSettingses = $this->createMock(Collection::class);
        $shopSettingses
            ->expects($this->once())
            ->method('contains')
            ->with($shopSettings)
            ->willReturn(true);
        $shopSettingses
            ->expects($this->never())
            ->method('add');
        $shopSettings
            ->expects($this->never())
            ->method('setShop');

        $ordersReflect = new \ReflectionProperty(Shop::class, 'shopSettings');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->shop, $shopSettingses);

        $this->shop->addShopSettings($shopSettings);
    }

    public function testCategories()
    {
        $category = new Category();
        $this->assertInstanceOf(Shop::class, $this->shop->addCategories($category));
        $this->assertInstanceOf(Collection::class, $this->shop->getCategories());
        $this->assertInstanceOf(Category::class, $this->shop->getCategories()->first());
        $this->assertEquals($category, $this->shop->getCategories()->first());
        $this->shop->addCategories($category);
    }

    public function testAddCategory()
    {
        /** @var Category|\PHPUnit_Framework_MockObject_MockObject $order */
        $category = $this->createMock(Category::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $categories = $this->createMock(Collection::class);
        $categories
            ->expects($this->once())
            ->method('contains')
            ->with($category)
            ->willReturn(false);
        $categories
            ->expects($this->once())
            ->method('add')
            ->with($category);
        $category
            ->expects($this->once())
            ->method('setShop');

        $ordersReflect = new \ReflectionProperty(Shop::class, 'categories');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->shop, $categories);

        $this->shop->addCategories($category);
    }

    public function testAddCategoryExists()
    {
        /** @var Category|\PHPUnit_Framework_MockObject_MockObject $order */
        $category = $this->createMock(Category::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $categories = $this->createMock(Collection::class);
        $categories
            ->expects($this->once())
            ->method('contains')
            ->with($category)
            ->willReturn(true);
        $categories
            ->expects($this->never())
            ->method('add');
        $category
            ->expects($this->never())
            ->method('setShop');

        $ordersReflect = new \ReflectionProperty(Shop::class, 'categories');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->shop, $categories);

        $this->shop->addCategories($category);
    }

    public function testGetCurrencies()
    {
        $this->assertInstanceOf(Collection::class, $this->shop->getCategories());
    }

    public function testGetLanguages()
    {
        $this->assertInstanceOf(Collection::class, $this->shop->getLanguages());
    }

    public function testApiKey()
    {
        $this->assertInstanceOf(Shop::class, $this->shop->setApiKey('12312312sfsdfdsf'));
        $this->assertEquals('12312312sfsdfdsf', $this->shop->getApiKey());
    }
}
