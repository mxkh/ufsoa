<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Tests\Functional\Controller;

use Gaufrette\Filesystem;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductImport;
use UmberFirm\Bundle\ProductBundle\Entity\VariantImport;
use UmberFirm\Bundle\ProductBundle\Repository\ProductImportRepository;
use UmberFirm\Bundle\ProductBundle\Repository\ProductRepository;
use UmberFirm\Bundle\ProductBundle\Repository\VariantImportRepository;
use UmberFirm\Bundle\SupplierBundle\Entity\Import;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierFeatureMapping;
use UmberFirm\Bundle\SupplierBundle\Tests\Fixtures\ImportProductTestFixtures;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;

/**
 * Class ImportConsumerProductMappingTest
 *
 * @package UmberFirm\Bundle\SupplierBundle\Tests\Functional\Controller
 */
class ImportConsumerProductMappingTest extends BaseTestCase
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
        self::createManufacturerMapping();
        self::createFeatureValueMapping();
        self::createAttributeMapping();
        self::createStoreMapping();

        $file = new UploadedFile(__DIR__.'/../../Fixtures/importExampleFormatV1.xml', 'importExampleFormatV1.xml');

        $import = new Import();
        $import->setSupplier(static::$entities['iconSupplier']);
        $import->setShop(static::$entities['hmShop']);
        $import->setVersion('v1');
        $import->setStatus(Import::STATUS_IMPORTING);
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

    public function testOnManufacturerMapping()
    {
        $body = $this->transformXmlToSupplierManufacturerData();
        $xmlString = $body->asXML();

        $container = $this->client->getContainer();
        /** @var Filesystem $storage */
        $storage = $container->get('umber_firm_supplier.component.storage.import_adapter');
        $storage->get(static::$entities['filename'])->setContent($xmlString);

        $container = static::$kernel->getContainer();
        $consumer = $container->get('umber_firm_supplier.component.consumer.import_product');
        $message = new AMQPMessage(json_encode(['import' => static::$entities['import']->getId()->toString()]));
        $this->assertTrue($consumer->execute($message));

        /* @var ProductImportRepository $productImportRepository */
        $productImportRepository = static::getObjectManager()->getRepository(ProductImport::class);

        /** @var ProductImport $productImport1 */
        $productImport1 = $productImportRepository->findOneBy(
            [
                'supplierReference' => $body->products->product[0]->id,
                'supplier' => static::$entities['iconSupplier'],
            ]
        );
        $this->assertNotNull($productImport1);
        $this->assertNotNull($productImport1->getProduct()->getManufacturer());

        $productImport2 = $productImportRepository->findOneBy(
            [
                'supplierReference' => $body->products->product[1]->id,
                'supplier' => static::$entities['iconSupplier'],
            ]
        );
        $this->assertNotNull($productImport2);
        $this->assertNotNull($productImport2->getProduct()->getManufacturer());

        $productImport3 = $productImportRepository->findOneBy(
            [
                'supplierReference' => $body->products->product[2]->id,
                'supplier' => static::$entities['iconSupplier'],
            ]
        );
        $this->assertNotNull($productImport3);
        $this->assertNotNull($productImport3->getProduct()->getManufacturer());
    }

    public function testOnStoreMapping()
    {
        $body = $this->transformXmlToSupplierStoreData();
        $xmlString = $body->asXML();

        $container = $this->client->getContainer();
        /** @var Filesystem $storage */
        $storage = $container->get('umber_firm_supplier.component.storage.import_adapter');
        $storage->get(static::$entities['filename'])->setContent($xmlString);

        $container = static::$kernel->getContainer();
        $consumer = $container->get('umber_firm_supplier.component.consumer.import_product');
        $message = new AMQPMessage(json_encode(['import' => static::$entities['import']->getId()->toString()]));
        $this->assertTrue($consumer->execute($message));

        /* @var VariantImportRepository $variantImportRepository */
        $variantImportRepository = static::getObjectManager()->getRepository(VariantImport::class);
        /** @var VariantImport $variantImport1 */
        $variantImport1 = $variantImportRepository->findOneBy(
            [
                'supplierReference' => $body->offers->offer[0]->offer_id,
                'supplier' => static::$entities['iconSupplier'],
            ]
        );
        /** @var VariantImport $variantImport2 */
        $variantImport2 = $variantImportRepository->findOneBy(
            [
                'supplierReference' => $body->offers->offer[1]->offer_id,
                'supplier' => static::$entities['iconSupplier'],
            ]
        );
        /** @var VariantImport $variantImport3 */
        $variantImport3 = $variantImportRepository->findOneBy(
            [
                'supplierReference' => $body->offers->offer[2]->offer_id,
                'supplier' => static::$entities['iconSupplier'],
            ]
        );

        $this->assertNotNull($variantImport1->getProductVariant()->getDepartments()->get(0)->getStore());
        $this->assertNotNull($variantImport2->getProductVariant()->getDepartments()->get(0)->getStore());
        $this->assertNotNull($variantImport3->getProductVariant()->getDepartments()->get(0)->getStore());
    }

    public function testOnMappingFeatureValue()
    {
        $body = $this->transformXmlToSupplierManufacturerData();
        $xmlString = $body->asXML();

        $container = $this->client->getContainer();
        /** @var Filesystem $storage */
        $storage = $container->get('umber_firm_supplier.component.storage.import_adapter');
        $storage->get(static::$entities['filename'])->setContent($xmlString);

        $container = static::$kernel->getContainer();
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

        /* @var ProductRepository $productRepository */
        $productRepository = static::getObjectManager()->getRepository(Product::class);
        $featureMappingRepository = static::getObjectManager()->getRepository(SupplierFeatureMapping::class);

        $product1 = $productImport1->getProduct();
        $this->assertNotNull($product1);

        $product2 = $productImport2->getProduct();
        $this->assertNotNull($product2);

        $product3 = $productImport3->getProduct();
        $this->assertNotNull($product3);

        $featureMappingMan = $featureMappingRepository->findOneBy(
            [
                'supplier' => static::$entities['iconSupplier']->getId()->toString(),
                'supplierFeatureKey' => 'gender',
                'supplierFeatureValue' => 'Man',
            ]
        );
        $this->assertNotNull($featureMappingMan);

        $featureMappingWoman = $featureMappingRepository->findOneBy(
            [
                'supplier' => static::$entities['iconSupplier']->getId()->toString(),
                'supplierFeatureKey' => 'gender',
                'supplierFeatureValue' => 'Man',
            ]
        );
        $this->assertNotNull($featureMappingWoman);
    }

    public function testOnAttributesMapping()
    {
        $body = $this->transformXmlToSupplierManufacturerData();
        $xmlString = $body->asXML();

        $container = $this->client->getContainer();
        /** @var Filesystem $storage */
        $storage = $container->get('umber_firm_supplier.component.storage.import_adapter');
        $storage->get(static::$entities['filename'])->setContent($xmlString);

        $container = static::$kernel->getContainer();
        $consumer = $container->get('umber_firm_supplier.component.consumer.import_product');
        $message = new AMQPMessage(json_encode(['import' => static::$entities['import']->getId()->toString()]));
        $this->assertTrue($consumer->execute($message));

        /* @var VariantImportRepository $variantImportRepository */
        $variantImportRepository = static::getObjectManager()->getRepository(VariantImport::class);
        /** @var VariantImport $variantImport1 */
        $variantImport1 = $variantImportRepository->findOneBy(
            [
                'supplierReference' => $body->offers->offer[0]->offer_id,
                'supplier' => static::$entities['iconSupplier'],
            ]
        );
        /** @var VariantImport $variantImport2 */
        $variantImport2 = $variantImportRepository->findOneBy(
            [
                'supplierReference' => $body->offers->offer[1]->offer_id,
                'supplier' => static::$entities['iconSupplier'],
            ]
        );
        /** @var VariantImport $variantImport3 */
        $variantImport3 = $variantImportRepository->findOneBy(
            [
                'supplierReference' => $body->offers->offer[2]->offer_id,
                'supplier' => static::$entities['iconSupplier'],
            ]
        );

        /* @var ProductRepository $productRepository */
        $this->assertNotNull($variantImport1->getProductVariant()->getProductVariantAttributes()->get(0));
        $this->assertNotNull($variantImport1->getProductVariant()->getProductVariantAttributes()->get(1));

        $this->assertNotNull($variantImport2->getProductVariant()->getProductVariantAttributes()->get(0));
        $this->assertNotNull($variantImport2->getProductVariant()->getProductVariantAttributes()->get(1));

        $this->assertNotNull($variantImport3->getProductVariant()->getProductVariantAttributes()->get(0));
        $this->assertNotNull($variantImport3->getProductVariant()->getProductVariantAttributes()->get(1));
    }
}
