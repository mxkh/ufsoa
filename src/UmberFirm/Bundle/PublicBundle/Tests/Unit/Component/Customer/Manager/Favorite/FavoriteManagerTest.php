<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Customer\Manager\Favorite;

use Predis\ClientInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\PublicBundle\Component\Customer\Manager\Favorite\FavoriteManager;

/**
 * Class FavoriteManagerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Customer\Manager\Favorite
 */
class FavoriteManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ClientInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $client;

    /**
     * @var string
     */
    private $index;

    /**
     * @var Customer|\PHPUnit_Framework_MockObject_MockObject
     */
    private $customer;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->client = $this->createMock(ClientInterface::class);
        $this->index = 'favorite';
        $this->customer = $this->createMock(Customer::class);
        $uuid = $this->createMock(UuidInterface::class);
        $uuid->expects($this->any())->method('toString')->willReturn(Uuid::uuid4());
        $this->customer->expects($this->any())->method('getId')->willReturn($uuid);
    }

    public function testAdd()
    {
        /** @var Product|\PHPUnit_Framework_MockObject_MockObject $product */
        $product = $this->createMock(Product::class);
        $uuid = $this->createMock(UuidInterface::class);
        $uuid->expects($this->any())->method('toString')->willReturn(Uuid::uuid4());
        $product->expects($this->any())->method('getId')->willReturn($uuid);

        $favoriteManager = new FavoriteManager($this->client, $this->index, 'test');

        $this->client
            ->expects($this->once())
            ->method('__call')
            ->with($this->equalTo('hsetnx'))
            ->willReturn(1);

        $this->assertInstanceOf(FavoriteManager::class, $favoriteManager->setCustomer($this->customer));
        $this->assertEquals(1, $favoriteManager->add($product));
    }

    public function testRemove()
    {
        $products = [1, 2, 3, 4];
        $favoriteManager = new FavoriteManager($this->client, $this->index);

        $this->client
            ->expects($this->once())
            ->method('__call')
            ->with($this->equalTo('hdel'))
            ->willReturn(4);

        $this->assertInstanceOf(FavoriteManager::class, $favoriteManager->setCustomer($this->customer));
        $this->assertEquals(4, $favoriteManager->remove($products));
    }

    public function testGet()
    {
        $products = [1, 3, 4, 3, 2, 1];
        $favoriteManager = new FavoriteManager($this->client, $this->index);

        $this->client
            ->expects($this->once())
            ->method('__call')
            ->with($this->equalTo('hmget'))
            ->willReturn([1, 3, 4, 3, 2, 1]);

        $this->assertInstanceOf(FavoriteManager::class, $favoriteManager->setCustomer($this->customer));
        $this->assertEquals([1, 3, 4, 2], $favoriteManager->get($products));
    }

    public function testGetAll()
    {
        $favoriteManager = new FavoriteManager($this->client, $this->index);

        $this->client
            ->expects($this->once())
            ->method('__call')
            ->with($this->equalTo('hkeys'))
            ->willReturn([1, 2, 3, 4]);

        $this->assertInstanceOf(FavoriteManager::class, $favoriteManager->setCustomer($this->customer));
        $this->assertEquals([1, 2, 3, 4], $favoriteManager->getAll());
    }
}
