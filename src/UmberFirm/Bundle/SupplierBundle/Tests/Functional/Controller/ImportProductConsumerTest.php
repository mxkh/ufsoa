<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Tests\Functional\Controller;

use Gaufrette\Filesystem;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ProductBundle\Entity\Department;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductImport;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ProductBundle\Entity\VariantImport;
use UmberFirm\Bundle\ProductBundle\Repository\ProductImportRepository;
use UmberFirm\Bundle\ProductBundle\Repository\ProductRepository;
use UmberFirm\Bundle\ProductBundle\Repository\VariantImportRepository;
use UmberFirm\Bundle\SupplierBundle\Entity\Import;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierAttributeMapping;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierFeatureMapping;
use UmberFirm\Bundle\SupplierBundle\Tests\Fixtures\ImportProductTestFixtures;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class ImportProductConsumer
 *
 * @package UmberFirm\Bundle\SupplierBundle\Tests\Controller
 */
class ImportProductConsumerTest extends BaseTestCase
{
    use ImportProductTestFixtures;

    /**
     * @var array|UuidEntityInterface[]
     */
    private static $entities;

    public static function tearDownAfterClass()
    {
        static::$entities['storage']->delete(static::$entities['filename']);

        parent::tearDownAfterClass();
    }

    public static function setUpBeforeClass()
    {
        //do nothing before class
    }

    protected function setUp()
    {
        self::setWebClient(static::createClient())
            ->setApplication(new Application(self::getWebClient()->getKernel()))
            ->setObjectManager(static::$kernel->getContainer()->get('doctrine')->getManager())
            ->setUpMysql();

        self::loadFixtures();
        $file = new UploadedFile(__DIR__.'/../../Fixtures/importExampleFormatV1.xml', 'importExampleFormatV1.xml');

        $import = new Import();
        $import->setSupplier(static::$entities['iconSupplier']);
        $import->setShop(static::$entities['hmShop']);
        $import->setStatus(Import::STATUS_IMPORTING);
        $import->setVersion('v1');
        $import->setFile($file);

        static::getObjectManager()->persist($import);
        static::getObjectManager()->flush();
        static::$entities['import'] = $import;
        static::$entities['filename'] = $import->getFilename();
        static::$entities['storage'] = static::$kernel->getContainer()
            ->get('umber_firm_supplier.component.storage.import_adapter');

        parent::setUp();
        $this->loginEmployee();
    }

    /**
     * Only for XML messages.
     */
    public function testConsumerXml()
    {
        $body = $this->transformXmlToSupplierManufacturerData();
        $xmlString = $body->asXML();

        $container = $this->client->getContainer();
        /** @var Filesystem $storage */
        $storage = $container->get('umber_firm_supplier.component.storage.import_adapter');
        $storage->get(static::$entities['filename'])->setContent($xmlString);

        $consumer = $container->get('umber_firm_supplier.component.consumer.import_product');
        $message = new AMQPMessage(json_encode(['import' => static::$entities['import']->getId()->toString()]));
        $this->assertTrue($consumer->execute($message));

        /* @var ProductImportRepository $productRepository */
        $productImportRepository = static::getObjectManager()->getRepository(ProductImport::class);
        $productImport1 = $productImportRepository->findOneBy(
            [
                'supplierReference' => $body->products->product[0]->id,
                'supplier' => static::$entities['iconSupplier'],
            ]
        );
        $productImport2 = $productImportRepository->findOneBy(
            [
                'supplierReference' => $body->products->product[1]->id,
                'supplier' => static::$entities['iconSupplier'],
            ]
        );
        $productImport3 = $productImportRepository->findOneBy(
            [
                'supplierReference' => $body->products->product[2]->id,
                'supplier' => static::$entities['iconSupplier'],
            ]
        );

        $product1 = $productImport1->getProduct();
        $this->assertNotNull($product1);
        $product2 = $productImport2->getProduct();
        $this->assertNotNull($product2);
        $product3 = $productImport3->getProduct();
        $this->assertNotNull($product3);
    }

    public function testOnSupplierManufacturerData()
    {
        $body = $this->transformXmlToSupplierManufacturerData();
        $xmlString = $body->asXML();

        $container = $this->client->getContainer();
        /** @var Filesystem $storage */
        $storage = $container->get('umber_firm_supplier.component.storage.import_adapter');
        $storage->get(static::$entities['filename'])->setContent($xmlString);

        $container = $this->client->getContainer();
        $consumer = $container->get('umber_firm_supplier.component.consumer.import_product');
        $message = new AMQPMessage(json_encode(['import' => static::$entities['import']->getId()->toString()]));
        $this->assertTrue($consumer->execute($message));
    }

    public function testOnSupplierStoreData()
    {
        $body = $this->transformXmlToSupplierStoreData();
        $xmlString = $body->asXML();

        $container = $this->client->getContainer();
        /** @var Filesystem $storage */
        $storage = $container->get('umber_firm_supplier.component.storage.import_adapter');
        $storage->get(static::$entities['filename'])->setContent($xmlString);

        $container = $this->client->getContainer();
        $consumer = $container->get('umber_firm_supplier.component.consumer.import_product');
        $message = new AMQPMessage(json_encode(['import' => static::$entities['import']->getId()->toString()]));
        $this->assertTrue($consumer->execute($message));
    }

    public function testOnSupplierFeatureValue()
    {
        $this->markTestIncomplete('We don\'t use features anymore');
    }

    public function testOnSupplierAttributes()
    {
        $body = $this->transformXmlToSupplierAttributesData();
        $xmlString = $body->asXML();

        $container = $this->client->getContainer();
        /** @var Filesystem $storage */
        $storage = $container->get('umber_firm_supplier.component.storage.import_adapter');
        $storage->get(static::$entities['filename'])->setContent($xmlString);

        $container = $this->client->getContainer();
        $consumer = $container->get('umber_firm_supplier.component.consumer.import_product');
        $message = new AMQPMessage(json_encode(['import' => static::$entities['import']->getId()->toString()]));
        $this->assertTrue($consumer->execute($message));

        /* @var VariantImportRepository $productRepository */
        $variantImportRepository = static::getObjectManager()->getRepository(VariantImport::class);
        $variantImport1 = $variantImportRepository->findOneBy(
            [
                'supplierReference' => $body->offers->offer[0]->offer_id,
                'supplier' => static::$entities['iconSupplier'],
            ]
        );
        $variantImport2 = $variantImportRepository->findOneBy(
            [
                'supplierReference' => $body->offers->offer[1]->offer_id,
                'supplier' => static::$entities['iconSupplier'],
            ]
        );
        $variantImport3 = $variantImportRepository->findOneBy(
            [
                'supplierReference' => $body->offers->offer[2]->offer_id,
                'supplier' => static::$entities['iconSupplier'],
            ]
        );

        /* @var ProductRepository $productRepository */
        $this->assertNotNull($variantImport1);
        $this->assertNotNull($variantImport2);
        $this->assertNotNull($variantImport3);

        $this->markTestIncomplete('Mapping is not creating yet');

//        $attributeMappingRepository = static::getObjectManager()->getRepository(SupplierAttributeMapping::class);
//        $attributes = $attributeMappingRepository->findAll();
//        $this->assertEquals(5, count($attributes));
    }

    public function testOnUpdateAndResetQuantityDepartments()
    {
        $xml = $this->transformDataToXml();
        $container = $this->client->getContainer();
        $consumer = $container->get('umber_firm_supplier.component.consumer.import_product');

        $xmlString = $xml->asXML();
        $variantSupplierReference = (string) $xml->offers->offer[3]->offer_id;
        $container = $this->client->getContainer();
        /** @var Filesystem $storage */
        $storage = $container->get('umber_firm_supplier.component.storage.import_adapter');
        $storage->get(static::$entities['filename'])->setContent($xmlString);

        $message = new AMQPMessage(json_encode(['import' => static::$entities['import']->getId()->toString()]));
        //import all variants
        $this->assertTrue($consumer->execute($message));

        /* @var ProductImportRepository $productRepository */
        $productImportRepository = static::getObjectManager()->getRepository(ProductImport::class);
        $productImport1 = $productImportRepository->findOneBy(
            [
                'supplierReference' => $xml->products->product[0]->id,
                'supplier' => static::$entities['iconSupplier'],
            ]
        );
        $productImport2 = $productImportRepository->findOneBy(
            [
                'supplierReference' => $xml->products->product[1]->id,
                'supplier' => static::$entities['iconSupplier'],
            ]
        );
        $productImport3 = $productImportRepository->findOneBy(
            [
                'supplierReference' => $xml->products->product[2]->id,
                'supplier' => static::$entities['iconSupplier'],
            ]
        );

        $product1 = $productImport1->getProduct();
        $product2 = $productImport2->getProduct();
        $product3 = $productImport3->getProduct();
        $this->assertNotNull($product1);
        $this->assertNotNull($product2);
        $this->assertNotNull($product3);

        $xml = $this->removeOneVariant($xml);
        $xmlString = $xml->asXML();
        $container = $this->client->getContainer();
        /** @var Filesystem $storage */
        $storage = $container->get('umber_firm_supplier.component.storage.import_adapter');
        $storage->get(static::$entities['filename'])->setContent($xmlString);

        $message = new AMQPMessage(json_encode(['import' => static::$entities['import']->getId()->toString()]));
        //import all except chosen variant to  reset quantity
        $this->assertTrue($consumer->execute($message));

        $variantImportRepository = static::getObjectManager()->getRepository(VariantImport::class);
        $variantImport = $variantImportRepository->findOneBy(
            [
                'supplier' => static::$entities['iconSupplier'],
                'supplierReference' => $variantSupplierReference,
            ]
        );
        $productVariant = $variantImport->getProductVariant();

        $departmentRepository = static::getObjectManager()->getRepository(Department::class);
        $department = $departmentRepository->findOneBy(
            [
                'productVariant' => $productVariant->getId()->toString(),
                'store' => static::$entities['storeMandarin']->getId()->toString(),
            ]
        );

        $this->assertEquals(0, $department->getQuantity());
    }
}
