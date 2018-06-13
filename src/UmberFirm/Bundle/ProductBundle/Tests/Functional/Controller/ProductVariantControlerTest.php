<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ProductBundle\Entity\Attribute;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroupEnum;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductVariant;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class ProductVariantControllerTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller
 */
class ProductVariantControllerTest extends BaseTestCase
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
        $storeOcean->setAddress(
            'Київ, вул. Антоновича, 176, ТРЦ "Ocean Plaza", 1-й поверх',
            'ua'
        );
        $storeOcean->setDescription(
            'Helen Marlen Ocean - shop democratic men\'s and women\'s shoes and accessories.
        Here is presented the whole variety of styles - from classics to sports models - both European and
        American brands, including: Fabio Rusconi, Marc by Marc Jacobs, Vetiver, Eddy Daniele, Crime London, Veja,
        Stokton, Colors of California, MOU, Minna Parikka, Roberto Festa, Logan, Salar, Rebecca Minkoff, and others.',
            'ua'
        );
        $storeOcean->setSchedule('пн. - нд. з 10:00 до 22:00', 'ua');
        $storeOcean->setIsActive(true);
        $storeOcean->mergeNewTranslations();
        $manager->persist($storeOcean);

        $manufacturer = new Manufacturer();
        $manufacturer
            ->setName('Manufacturer')
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

        $product2 = new Product();
        $product2->setManufacturer($manufacturer);
        $product2->setName('product2', 'ua');
        $product2->setOutOfStock(true);
        $product2->setShop($hmShop);
        $product2->mergeNewTranslations();
        $manager->persist($product2);

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

        $attributeSizeM = new Attribute();
        $attributeSizeM->setAttributeGroup($attributeGroupSize)
            ->setName('M', 'ua')
            ->setName('M', 'en')
            ->mergeNewTranslations();
        $manager->persist($attributeSizeM);

        $attributeColorGreen = new Attribute();
        $attributeColorGreen->setAttributeGroup($attributeGroupColor)
            ->setColor('#00FF00')
            ->setName('Зелений', 'ua')
            ->setName('Green', 'en')
            ->mergeNewTranslations();
        $manager->persist($attributeColorGreen);

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
            ->addProductVariantAttribute($attributeSizeM)
            ->addProductVariantAttribute($attributeColorBlue);
        $manager->persist($productVariant2);

        $productVariant3 = new ProductVariant();
        $productVariant3->setProduct($product2)
            ->setShop($hmShop)
            ->addProductVariantAttribute($attributeSizeM)
            ->addProductVariantAttribute($attributeColorBlue);
        $manager->persist($productVariant3);

        $manager->flush();

        self::$entities = [
            'manufacturer' => $manufacturer,
            'Product:1' => $product,
            'Product:2' => $product2,
            'ProductVariant:1' => $productVariant1,
            'ProductVariant:2' => $productVariant2,
            'ProductVariant:3' => $productVariant3,
            'Attribute:Size:S' => $attributeSizeS,
            'Attribute:Size:M' => $attributeSizeM,
            'Attribute:Color:Green' => $attributeColorGreen,
            'Attribute:Color:Blue' => $attributeColorBlue,
            'supplier' => $supplier,
            'storeOcean' => $storeOcean,
            'hmShop' => $hmShop,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->payload = [
            'shop' => self::$entities['hmShop']->getId()->toString(),
            'productVariantAttributes' => [
                self::$entities['Attribute:Size:S']->getId()->toString(),
                self::$entities['Attribute:Color:Green']->getId()->toString(),
            ],
        ];

        parent::setUp();
        $this->loginEmployee();
    }

    public function testGetProductVariantList()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_variants',
            [
                'product' => self::$entities['Product:1']->getId()->toString(),
            ]
        );

        $this->client->request(
            'GET',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetProductVariantListProductNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_variants',
            [
                'product' => $uuid,
            ]
        );

        $this->client->request(
            'GET',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testGetProductVariant()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_variant',
            [
                'product' => self::$entities['Product:1']->getId()->toString(),
                'productVariant' => self::$entities['ProductVariant:1']->getId()->toString(),
            ]
        );

        $this->client->request(
            'GET',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    public function testGetProductVariantNotFoundWrongProduct()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_variant',
            [
                'product' => self::$entities['Product:2']->getId()->toString(),
                'productVariant' => self::$entities['ProductVariant:1']->getId()->toString(),
            ]
        );

        $this->client->request(
            'GET',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testGetProductVariantNotFoundWrongProductVariant()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_variant',
            [
                'product' => self::$entities['Product:1']->getId()->toString(),
                'productVariant' => self::$entities['ProductVariant:3']->getId()->toString(),
            ]
        );

        $this->client->request(
            'GET',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetProductVariantNotFoundProductVariant($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_variant',
            [
                'product' => self::$entities['Product:1']->getId()->toString(),
                'productVariant' => $uuid,
            ]
        );

        $this->client->request(
            'GET',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetProductVariantNotFoundProduct($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_variant',
            [
                'product' => $uuid,
                'productVariant' => self::$entities['ProductVariant:1']->getId()->toString(),
            ]
        );

        $this->client->request(
            'GET',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testPostProductVariant()
    {
        $uri = $this->router->generate(
            'umberfirm__product__post_product_variant',
            [
                'product' => self::$entities['Product:2']->getId()->toString(),
            ]
        );

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostProductVariantWithEmptyAttributes()
    {
        $uri = $this->router->generate(
            'umberfirm__product__post_product_variant',
            [
                'product' => self::$entities['Product:2']->getId()->toString(),
            ]
        );
        $this->payload['productVariantAttributes'] = [];
        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostProductVariantWithoutAttributes()
    {
        $uri = $this->router->generate(
            'umberfirm__product__post_product_variant',
            [
                'product' => self::$entities['Product:2']->getId()->toString(),
            ]
        );
        unset($this->payload['productVariantAttributes']);
        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostProductVariantBadRequest()
    {
        $uri = $this->router->generate(
            'umberfirm__product__post_product_variant',
            [
                'product' => self::$entities['Product:2']->getId()->toString(),
            ]
        );

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(['hello' => 'world'])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param $uuid
     */
    public function testPostProductVariantNotFoundProduct($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__post_product_variant',
            [
                'product' => $uuid,
            ]
        );

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testPutProductVariant()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_variant',
            [
                'product' => self::$entities['Product:1']->getId()->toString(),
                'productVariant' => self::$entities['ProductVariant:1']->getId()->toString(),
            ]
        );

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT_LANGUAGE' => 'en',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);

        $putResponse = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals('S', $putResponse->product_variant_attributes['0']->name);
    }

    public function testPutProductVariantWithEmptyAttributes()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_variant',
            [
                'product' => self::$entities['Product:1']->getId()->toString(),
                'productVariant' => self::$entities['ProductVariant:1']->getId()->toString(),
            ]
        );

        $this->payload['productVariantAttributes'] = [];
        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT_LANGUAGE' => 'es',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);

        $putResponse = json_decode($this->client->getResponse()->getContent());
        $this->assertCount(0, $putResponse->product_variant_attributes);
    }

    public function testPutProductVariantBadRequest()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_variant',
            [
                'product' => self::$entities['Product:1']->getId()->toString(),
                'productVariant' => self::$entities['ProductVariant:1']->getId()->toString(),
            ]
        );

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT_LANGUAGE' => 'es',
            ],
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPutProductVariantNotFound()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_variant',
            [
                'product' => self::$entities['Product:2']->getId()->toString(),
                'productVariant' => self::$entities['ProductVariant:1']->getId()->toString(),
            ]
        );

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT_LANGUAGE' => 'es',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param $uuid
     */
    public function testPutProductVariantNotFoundProduct($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_variant',
            [
                'product' => $uuid,
                'productVariant' => self::$entities['ProductVariant:1']->getId()->toString(),
            ]
        );

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT_LANGUAGE' => 'es',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param $uuid
     */
    public function testPutProductVariantNotFoundProductVariant($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_variant',
            [
                'product' => self::$entities['Product:2']->getId()->toString(),
                'productVariant' => $uuid,
            ]
        );

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT_LANGUAGE' => 'es',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testDeleteProductVariantNotFound()
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_product_variant',
            [
                'product' => self::$entities['Product:2']->getId()->toString(),
                'productVariant' => self::$entities['ProductVariant:1']->getId()->toString(),
            ]
        );

        $this->client->request(
            'DELETE',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testDeleteProductVariant()
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_product_variant',
            [
                'product' => self::$entities['Product:1']->getId()->toString(),
                'productVariant' => self::$entities['ProductVariant:1']->getId()->toString(),
            ]
        );

        $this->client->request(
            'DELETE',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertEquals(
            Response::HTTP_NO_CONTENT,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );

        $listUri = $this->router->generate(
            'umberfirm__product__get_product_variants',
            [
                'product' => self::$entities['Product:1']->getId()->toString(),
            ],
            Router::ABSOLUTE_URL
        );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains('Location', $listUri),
            $this->client->getResponse()->headers
        );
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param $uuid
     */
    public function testDeleteProductVariantNotFoundProduct($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_product_variant',
            [
                'product' => $uuid,
                'productVariant' => self::$entities['ProductVariant:1']->getId()->toString(),
            ]
        );

        $this->client->request(
            'DELETE',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param $uuid
     */
    public function testDeleteProductVariantNotFoundVariant($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_product_variant',
            [
                'product' => self::$entities['Product:1']->getId()->toString(),
                'productVariant' => $uuid,
            ]
        );

        $this->client->request(
            'DELETE',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }
}
