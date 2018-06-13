<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ProductBundle\Entity\Attribute;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroupEnum;
use UmberFirm\Bundle\ProductBundle\Entity\Department;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductImport;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class BulkUpdateControllerTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller
 */
class BulkUpdateControllerTest extends BaseTestCase
{
    /**
     * @var array|UuidEntityInterface[]
     */
    private static $entities = [];

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $manager = static::getObjectManager();

        $storeOcean = new Store();
        $storeOcean->setName('Ocean Plaza');
        $storeOcean->setIsActive(true);
        $manager->persist($storeOcean);

        $manufacturer = new Manufacturer();
        $manufacturer
            ->setName('Manufacturer', 'ua')
            ->setWebsite('Manufacturer.com')
            ->setAddress('Manufacturer street', 'ua');
        $manager->persist($manufacturer);

        $supplier = new Supplier();
        $supplier
            ->setName('Supplier', 'ua')
            ->setIsActive(true)
            ->setUsername('Supplier')
            ->setPassword('Supplier');
        $manager->persist($supplier);

        $supplier2 = new Supplier();
        $supplier2
            ->setName('Supplier 2', 'ua')
            ->setIsActive(true)
            ->setUsername('Supplier 2')
            ->setPassword('Supplier 2');
        $manager->persist($supplier2);

        $hmShop = new Shop();
        $hmShop->setName('Helen Marlen');
        $hmShop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($hmShop);

        $product = new Product();
        $product->setManufacturer($manufacturer);
        $product->setName('pidjack', 'ua');
        $product->setOutOfStock(true);
        $product->setShop($hmShop);
        $product->mergeNewTranslations();
        $manager->persist($product);

        $attributeGroupEnumSelect = new AttributeGroupEnum();
        $attributeGroupEnumSelect->setName('select');
        $manager->persist($attributeGroupEnumSelect);

        $attributeGroupEnumColor = new AttributeGroupEnum();
        $attributeGroupEnumColor->setName('color');
        $manager->persist($attributeGroupEnumColor);

        $attributeGroupSize = new AttributeGroup();
        $attributeGroupSize->setIsColorGroup(false)
            ->setAttributeGroupEnum($attributeGroupEnumSelect)
            ->setName('Розмiр', 'ua')
            ->setPublicName('Розмiр', 'ua')
            ->setName('Size', 'en')
            ->setPublicName('Size', 'en')
            ->mergeNewTranslations();
        $manager->persist($attributeGroupSize);

        $attributeGroupColor = new AttributeGroup();
        $attributeGroupColor->setIsColorGroup(true)
            ->setAttributeGroupEnum($attributeGroupEnumColor)
            ->setName('Колір', 'ua')
            ->setPublicName('Колір', 'ua')
            ->setName('Color', 'en')
            ->setPublicName('Color', 'en')
            ->mergeNewTranslations();
        $manager->persist($attributeGroupColor);

        $attributeSizeS = new Attribute();
        $attributeSizeS->setAttributeGroup($attributeGroupSize)
            ->setName('S', 'ua')
            ->setName('S', 'en')
            ->mergeNewTranslations();
        $manager->persist($attributeSizeS);

        $attributeColorBlue = new Attribute();
        $attributeColorBlue->setAttributeGroup($attributeGroupColor)
            ->setColor('#0000FF')
            ->setName('Синій', 'ua')
            ->setName('Blue', 'en')
            ->mergeNewTranslations();
        $manager->persist($attributeColorBlue);

        $productVariant1 = new ProductVariant();
        $productVariant1->setProduct($product)
            ->setShop($hmShop)
            ->addProductVariantAttribute($attributeSizeS)
            ->addProductVariantAttribute($attributeColorBlue);
        $manager->persist($productVariant1);

        $productVariant2 = new ProductVariant();
        $productVariant2->setProduct($product)
            ->setShop($hmShop)
            ->addProductVariantAttribute($attributeSizeS)
            ->addProductVariantAttribute($attributeColorBlue);
        $manager->persist($productVariant2);

        $department1 = new Department();
        $department1
            ->setProductVariant($productVariant1)
            ->setEan13('1')
            ->setPrice(100.00)
            ->setSalePrice(80.00)
            ->setQuantity(4)
            ->setSupplier($supplier)
            ->setShop($hmShop);
        $manager->persist($department1);

        $department2 = new Department();
        $department2
            ->setProductVariant($productVariant1)
            ->setUpc('2')
            ->setPrice(100.00)
            ->setSalePrice(88.00)
            ->setQuantity(4)
            ->setSupplier($supplier2)
            ->setShop($hmShop);
        $manager->persist($department2);

        $manager->flush();

        self::$entities = [
            'ProductVariant:1' => $productVariant1,
            'ProductVariant:2' => $productVariant2,
            'Department:1' => $department1,
            'Department:2' => $department2,
            'supplier' => $supplier,
            'storeOcean' => $storeOcean,
            'hmShop' => $hmShop,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        $this->loginEmployee();
    }

    public function testPostUpdateAction() {
        $uri = $this->router->generate(
            'umberfirm__product__post_product_bulk_update'
        );
        
        $this->payload = [
            'file' => new UploadedFile(__DIR__ . '/../../Fixtures/bulkUpdate.csv', 'test.csv')
        ];

        $this->client->request(
            'POST',
            $uri,
            [],
            $this->payload,
            [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostUpdateNoRowAction() {
        $uri = $this->router->generate(
            'umberfirm__product__post_product_bulk_update'
        );
        
        $this->payload = [
            'file' => new UploadedFile(__DIR__ . '/../../Fixtures/bulkUpdateNoRow.csv', 'test.csv')
        ];

        $this->client->request(
            'POST',
            $uri,
            [],
            $this->payload,
            [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostUpdateOneRowAction() {
        $uri = $this->router->generate(
            'umberfirm__product__post_product_bulk_update'
        );
        
        $this->payload = [
            'file' => new UploadedFile(__DIR__ . '/../../Fixtures/bulkUpdateOneRow.csv', 'test.csv')
        ];

        $this->client->request(
            'POST',
            $uri,
            [],
            $this->payload,
            [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostUpdateEmptyFileValidation()
    {
        $uri = $this->router->generate(
            'umberfirm__product__post_product_bulk_update'
        );
        
        $this->payload = [
            'file' => new UploadedFile(__DIR__ . '/../../Fixtures/bulkUpdateEmpty.csv', 'test.csv')
        ];
        
        $this->client->request(
            'POST',
            $uri,
            [],
            $this->payload,
            [
            'HTTP_ACCEPT' => 'application/json',
            ]
        );
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPostUpdateBigSizeValidation()
    {
        $uri = $this->router->generate(
            'umberfirm__product__post_product_bulk_update'
        );
        
        $this->payload = [
            'file' => new UploadedFile(__DIR__ . '/../../Fixtures/bulkUpdateBigSize.csv', 'test.csv')
        ];
        
        $this->client->request(
            'POST',
            $uri,
            [],
            $this->payload,
            [
            'HTTP_ACCEPT' => 'application/json',
            ]
        );
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPostUpdateInternalServerError()
    {
        $uri = $this->router->generate(
            'umberfirm__product__post_product_bulk_update'
        );
        
        $this->payload = [
            'file' => new UploadedFile(__DIR__ . '/../../Fixtures/bulkUpdateNotCorrectFormat.csv', 'test.csv')
        ];

        $this->client->request(
            'POST',
            $uri,
            [],
            $this->payload,
            [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }
}
