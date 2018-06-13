<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Tests\Unit\Component\Manager;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use UmberFirm\Bundle\OrderBundle\Component\Manager\ShoppingCartItemManager;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCartItem;
use UmberFirm\Bundle\OrderBundle\Repository\ShoppingCartItemRepository;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;

/**
 * Class ShoppingCartItemManagerTest
 *
 * @package UmberFirm\Bundle\OrderBundle\Tests\Unit\Component\Manager
 */
class ShoppingCartItemManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var Connection|\PHPUnit_Framework_MockObject_MockObject */
    private $connection;

    /** @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $entityManager;

    /** @var ShoppingCartItemRepository|\PHPUnit_Framework_MockObject_MockObject */
    private $shoppingCartItemRepository;

    /** @var ShoppingCartItem|\PHPUnit_Framework_MockObject_MockObject */
    private $shoppingCartItem;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'beginTransaction',
                    'commit',
                    'rollback',
                ]
            )
            ->getMock();

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager
            ->expects($this->any())
            ->method('getConnection')
            ->willReturn($this->connection);

        $this->shoppingCartItemRepository = $this->createMock(ShoppingCartItemRepository::class);

        $productVariant = $this->createMock(ProductVariant::class);
        $productVariant
            ->expects($this->any())
            ->method('getId')
            ->willReturn(Uuid::uuid4());

        $shoppingCart = $this->createMock(ShoppingCart::class);
        $shoppingCart
            ->expects($this->any())
            ->method('getId')
            ->willReturn(Uuid::uuid4());

        $this->shoppingCartItem = $this->createMock(ShoppingCartItem::class);
        $this->shoppingCartItem
            ->expects($this->any())
            ->method('getShoppingCart')
            ->willReturn($shoppingCart);
        $this->shoppingCartItem
            ->expects($this->any())
            ->method('getProductVariant')
            ->willReturn($productVariant);
    }

    public function testManageSave()
    {
        $this->shoppingCartItemRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);

        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(ShoppingCartItem::class)
            ->willReturn($this->shoppingCartItemRepository);

        $manager = new ShoppingCartItemManager($this->entityManager);
        $this->assertInstanceOf(ShoppingCartItem::class, $manager->manage($this->shoppingCartItem));
    }

    public function testManageSaveWithException()
    {
        $this->shoppingCartItemRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);

        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(ShoppingCartItem::class)
            ->willReturn($this->shoppingCartItemRepository);

        $this->entityManager
            ->expects($this->once())
            ->method('flush')
            ->willThrowException(new \Exception());

        $manager = new ShoppingCartItemManager($this->entityManager);
        $this->assertInstanceOf(ShoppingCartItem::class, $manager->manage($this->shoppingCartItem));
    }

    public function testManageUpdate()
    {
        $this->shoppingCartItemRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturn($this->shoppingCartItem);

        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(ShoppingCartItem::class)
            ->willReturn($this->shoppingCartItemRepository);

        $manager = new ShoppingCartItemManager($this->entityManager);
        $this->assertInstanceOf(ShoppingCartItem::class, $manager->manage($this->shoppingCartItem));
    }

    public function testManageUpdateWithException()
    {
        $this->shoppingCartItemRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturn($this->shoppingCartItem);

        $this->entityManager
            ->expects($this->once())
            ->method('getRepository')
            ->with(ShoppingCartItem::class)
            ->willReturn($this->shoppingCartItemRepository);

        $this->entityManager
            ->expects($this->once())
            ->method('flush')
            ->willThrowException(new \Exception());

        $manager = new ShoppingCartItemManager($this->entityManager);
        $this->assertInstanceOf(ShoppingCartItem::class, $manager->manage($this->shoppingCartItem));
    }

    public function testGetEntityManager()
    {
        $manager = new ShoppingCartItemManager($this->entityManager);
        $result = $manager->getEntityManager();

        $this->assertInstanceOf(EntityManagerInterface::class, $result);
    }
}
