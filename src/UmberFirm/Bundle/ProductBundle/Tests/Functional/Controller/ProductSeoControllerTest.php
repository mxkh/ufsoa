<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductSeo;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;

/**
 * Class ProductSeoController
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller
 */
class ProductSeoControllerTest extends BaseTestCase
{
    /**
     * @var array|Product[]|Shop[]
     */
    private static $entities = [];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->loginEmployee();

        $this->payload = [
            'shop' =>  self::$entities['hmShop']->getId()->toString(),
            'translations' => [
                'ru' => [
                    'title' => 'new title',
                    'description' => 'new description',
                    'keywords' => 'new keywords',
                ]
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $manufacturer = new Manufacturer();
        $manufacturer
            ->setName('Manufacturer')
            ->setWebsite('Manufacturer.com')
            ->setAddress('Manufacturer street', 'ru');
        static::getObjectManager()->persist($manufacturer);

        $hmShop = new Shop();
        $hmShop->setName('Helen Marlen');
        $hmShop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($hmShop);

        $suppliers = new Supplier();
        $suppliers
            ->setName('Supplier', 'ru')
            ->setIsActive(true)
            ->setUsername('Supplier')
            ->setPassword('Supplier');
        static::getObjectManager()->persist($suppliers);

        $product1 = new Product();
        $product1
            ->setManufacturer($manufacturer)
            ->setName('product1', 'ru')
            ->setShop($hmShop)
            ->setIsHidden(true)
            ->mergeNewTranslations();
        static::getObjectManager()->persist($product1);

        $product2 = new Product();
        $product2
            ->setManufacturer($manufacturer)
            ->setName('product2', 'ru')
            ->setShop($hmShop)
            ->setIsHidden(true)
            ->mergeNewTranslations();
        static::getObjectManager()->persist($product2);

        $productSeo2 = new ProductSeo();
        $productSeo2->setKeywords('product2 keywords', 'ru');
        $productSeo2->setDescription('product2 description', 'ru');
        $productSeo2->setTitle('product2 title', 'ru');
        $productSeo2->setProduct($product2);
        $productSeo2->setShop($hmShop);
        static::getObjectManager()->persist($productSeo2);

        static::getObjectManager()->flush();

        self::$entities = [
            'hmShop' => $hmShop,
            'product1' => $product1,
            'product2' => $product2,
            'productSeo2' => $productSeo2,
        ];
    }

    public function testCgetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_seos',
            [
                'product' => static::$entities['product2']->getId()->toString()
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
    public function testCgetActionNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_seos',
            [
                'product' => $uuid
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

    public function testGetActionNoReferences()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_seo',
            [
                'product' => static::$entities['product1']->getId()->toString(),
                'seo' => static::$entities['productSeo2']->getId()->toString(),
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

    public function testGetTranslationActionNoReferences()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_seo_translations',
            [
                'product' => static::$entities['product1']->getId()->toString(),
                'seo' => static::$entities['productSeo2']->getId()->toString(),
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

    public function testPutActionNoReferences()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_seo',
            [
                'product' => static::$entities['product1']->getId()->toString(),
                'seo' => static::$entities['productSeo2']->getId()->toString(),
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
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testGetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_seo',
            [
                'product' => static::$entities['product2']->getId()->toString(),
                'seo' => static::$entities['productSeo2']->getId()->toString(),
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
    public function testGetActionNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_seo',
            [
                'product' => $uuid,
                'seo' => static::$entities['productSeo2']->getId()->toString(),
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
    public function testGetActionNotFoundSeo($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_seo',
            [
                'product' => static::$entities['product2']->getId()->toString(),
                'seo' => $uuid,
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

    public function testGetTranslationsAction()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_seo_translations',
            [
                'product' => static::$entities['product2']->getId()->toString(),
                'seo' => static::$entities['productSeo2']->getId()->toString(),
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
    public function testGetTranslationsActionNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_seo_translations',
            [
                'product' => $uuid,
                'seo' => static::$entities['productSeo2']->getId()->toString(),
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
    public function testGetActionNotFoundSeoTranslation($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_seo_translations',
            [
                'product' => static::$entities['product2']->getId()->toString(),
                'seo' => $uuid,
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

    public function testPutActionProductWithNoSeo()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_seo',
            [
                'product' => self::$entities['product1']->getId()->toString(),
                'seo' => static::$entities['productSeo2']->getId()->toString(),
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

    public function testPostInvalidAction()
    {
        $uri = $this->router->generate(
            'umberfirm__product__post_product_seo',
            [
                'product' => static::$entities['product1']->getId()->toString(),
                'seo' => static::$entities['productSeo2']->getId()->toString(),
            ]
        );
        unset($this->payload['shop']);
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

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPostActionOnExistedSeo()
    {
        $uri = $this->router->generate(
            'umberfirm__product__post_product_seo',
            [
                'product' => static::$entities['product2']->getId()->toString()
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

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPostActionNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__post_product_seo',
            [
                'product' => $uuid
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

    public function testPostAction()
    {
        $uri = $this->router->generate(
            'umberfirm__product__post_product_seo',
            [
                'product' => static::$entities['product1']->getId()->toString(),
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

    public function testPutInvalidAction()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_seo',
            [
                'product' => static::$entities['product2']->getId()->toString(),
                'seo' => static::$entities['productSeo2']->getId()->toString(),
            ]
        );
        $this->payload['extra-field'] = '-';
        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPutAction()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_seo',
            [
                'product' => static::$entities['product2']->getId()->toString(),
                'seo' => static::$entities['productSeo2']->getId()->toString(),
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
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);

        $putResponse = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals($this->payload['translations']['ru']['title'], $putResponse->title);
    }

    public function testPutActionEmpty()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_seo',
            [
                'product' => static::$entities['product2']->getId()->toString(),
                'seo' => static::$entities['productSeo2']->getId()->toString(),
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

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPutActionNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_seo',
            [
                'product' => $uuid,
                'seo' => static::$entities['productSeo2']->getId()->toString(),
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
     * @param string $uuid
     */
    public function testPutActionNotFoundSeo($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_seo',
            [
                'product' => static::$entities['product2']->getId()->toString(),
                'seo' => $uuid,
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
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }
}
