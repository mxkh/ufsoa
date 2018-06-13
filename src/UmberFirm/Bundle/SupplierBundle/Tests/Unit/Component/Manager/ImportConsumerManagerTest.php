<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Tests\Unit\Component\Manager;

use Doctrine\ORM\EntityManagerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use UmberFirm\Bundle\SupplierBundle\Component\Manager\ImportConsumerManager;
use UmberFirm\Bundle\SupplierBundle\Entity\Import;
use UmberFirm\Bundle\SupplierBundle\Services\Import\ImportProductInterface;

/**
 * Class ImportConsumerManagerTest
 *
 * @package UmberFirm\Bundle\SupplierBundle\Tests\Unit\Component\Manager
 */
class ImportConsumerManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ImportProductInterface
     */
    private $importProduct;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|AMQPMessage
     */
    private $AMQPMessage;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->importProduct = $this->createMock(ImportProductInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->AMQPMessage = $this->createMock(AMQPMessage::class);
    }

    public function testManage()
    {
        $import = $this->createMock(Import::class);
        $this->entityManager->expects($this->once())->method('find')->willReturn($import);
        $this->importProduct->expects($this->once())->method('loadData');
        $this->importProduct->expects($this->once())->method('import');
        $this->importProduct->expects($this->once())->method('validate')->willReturn(true);

        $importConsumerManager = new ImportConsumerManager($this->entityManager, $this->importProduct);
        $result = $importConsumerManager->manage('uid');
        $this->assertTrue($result);
    }

    public function testManageNullableImport()
    {
        $this->entityManager->expects($this->once())->method('find')->willReturn(null);
        $this->importProduct->expects($this->never())->method('loadData');
        $this->importProduct->expects($this->never())->method('import');
        $this->importProduct->expects($this->never())->method('validate')->willReturn(true);

        $importConsumerManager = new ImportConsumerManager($this->entityManager, $this->importProduct);
        $result = $importConsumerManager->manage('uid');
        $this->assertFalse($result);
    }

    public function testManageInvalidFormat()
    {
        $import = $this->createMock(Import::class);
        $this->entityManager->expects($this->once())->method('find')->willReturn($import);
        $this->importProduct->expects($this->once())->method('loadData');
        $this->importProduct->expects($this->once())->method('validate')->willReturn(false);
        $this->importProduct->expects($this->never())->method('import');

        $importConsumerManager = new ImportConsumerManager($this->entityManager, $this->importProduct);
        $result = $importConsumerManager->manage('uid');
        $this->assertFalse($result);
    }
}
