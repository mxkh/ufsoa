<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ProductBundle\Entity\Feature;
use UmberFirm\Bundle\ProductBundle\Entity\FeatureValue;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductFeature;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class ProductFeatureControllerTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller
 */
class ProductFeatureControllerTest extends BaseTestCase
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

        $hmShop = new Shop();
        $hmShop->setName('Helen Marlen');
        $hmShop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($hmShop);

        $fakeManufacturer = new Manufacturer();
        $fakeManufacturer
            ->setName('FakeManufacurer')
            ->setWebsite('faek-manufacturer.com')
            ->setAddress('Fake Manufacturer street', 'ua')
            ->mergeNewTranslations();
        $manager->persist($fakeManufacturer);

        $properties = new Feature();
        $properties
            ->setName('Властивості', 'ua')
            ->setName('Properties', 'en')
            ->setName('Cвойства', 'ru')
            ->setName('Propiedades', 'es')
            ->mergeNewTranslations();
        $manager->persist($properties);

        $compositions = new Feature();
        $compositions
            ->setName('Склад', 'ua')
            ->setName('Compositions', 'en')
            ->setName('Состав', 'ru')
            ->setName('Сomposiciones', 'es')
            ->mergeNewTranslations();
        $manager->persist($compositions);

        $shortSleeve = new FeatureValue();
        $shortSleeve->setFeature($properties)
            ->setValue('Короткий Рукав', 'ua')
            ->setValue('Short Sleeve', 'en')
            ->setValue('Короткий Рукав', 'ru')
            ->setValue('Manga Corta', 'es')
            ->mergeNewTranslations();
        $manager->persist($shortSleeve);

        $colorfulDress = new FeatureValue();
        $colorfulDress->setFeature($properties)
            ->setValue('Барвисті Сукні', 'ua')
            ->setValue('Colorful Dress', 'en')
            ->setValue('Красочные Платья', 'ru')
            ->setValue('Vestido Colorido', 'es')
            ->mergeNewTranslations();
        $manager->persist($colorfulDress);

        $shortDress = new FeatureValue();
        $shortDress->setFeature($properties)
            ->setValue('Короткі Сукні', 'ua')
            ->setValue('Short Dress', 'en')
            ->setValue('Короткие Платье', 'ru')
            ->setValue('Vestido Corto', 'es')
            ->mergeNewTranslations();
        $manager->persist($shortDress);

        $firstProduct = new Product();
        $firstProduct
            ->setName('first-product', 'ua')
            ->setShop($hmShop)
            ->setIsHidden(true)
            ->mergeNewTranslations();
        $manager->persist($firstProduct);

        $secondProduct = new Product();
        $secondProduct
            ->setName('first-product', 'ua')
            ->setShop($hmShop)
            ->setIsHidden(true)
            ->mergeNewTranslations();
        $manager->persist($secondProduct);

        $productFeature = new ProductFeature();
        $productFeature->setProduct($firstProduct)
            ->setFeature($properties)
            ->addProductFeatureValue($colorfulDress)
            ->addProductFeatureValue($shortDress);
        $manager->persist($productFeature);

        $manager->flush();

        self::$entities = [
            'Product:FirstProduct' => $firstProduct,
            'Product:SecondProduct' => $secondProduct,
            'Feature:Properties' => $properties,
            'Feature:Compositions' => $compositions,
            'Feature:Properties:ShortSleeve' => $shortSleeve,
            'Feature:Properties:ColorfulDress' => $colorfulDress,
            'Feature:Properties:ShortDress' => $shortDress,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->payload = [
            'feature' => self::$entities['Feature:Properties']->getId()->toString(),
            'productFeatureValues' => [
                self::$entities['Feature:Properties:ShortSleeve']->getId()->toString(),
            ],
        ];

        parent::setUp();
        $this->loginEmployee();
    }

    public function testGetProductFeatureList()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_product-features',
            [
                'product' => self::$entities['Product:FirstProduct']->getId()->toString(),
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
    public function testGetProductFeatureListProductNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_product-features',
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

    public function testGetProductFeature()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_product-feature',
            [
                'product' => self::$entities['Product:FirstProduct']->getId()->toString(),
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
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

    public function testGetProductFeatureNotAssignedFeature()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_product-feature',
            [
                'product' => self::$entities['Product:SecondProduct']->getId()->toString(),
                'feature' => self::$entities['Feature:Compositions']->getId()->toString(),
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
    public function testGetProductFeatureNotFoundFeature($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_product-feature',
            [
                'product' => self::$entities['Product:FirstProduct']->getId()->toString(),
                'feature' => $uuid,
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
    public function testGetProductFeatureNotFoundProduct($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_product-feature',
            [
                'product' => $uuid,
                'feature' => self::$entities['Feature:Compositions']->getId()->toString(),
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

    public function testPostProductFeature()
    {
        $uri = $this->router->generate(
            'umberfirm__product__post_product_product-feature',
            [
                'product' => self::$entities['Product:FirstProduct']->getId()->toString(),
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

    public function testPostProductFeatureWithEmptyProductFeatureValues()
    {
        $uri = $this->router->generate(
            'umberfirm__product__post_product_product-feature',
            [
                'product' => self::$entities['Product:FirstProduct']->getId()->toString(),
            ]
        );
        $this->payload['productFeatureValues'] = [];
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

    public function testPostProductFeatureWithoutProductFeatureValues()
    {
        $uri = $this->router->generate(
            'umberfirm__product__post_product_product-feature',
            [
                'product' => self::$entities['Product:FirstProduct']->getId()->toString(),
            ]
        );
        unset($this->payload['productFeatureValues']);
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

    public function testPostProductFeatureBadRequest()
    {
        $uri = $this->router->generate(
            'umberfirm__product__post_product_product-feature',
            [
                'product' => self::$entities['Product:FirstProduct']->getId()->toString(),
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
    public function testPostProductFeatureNotFoundProduct($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__post_product_product-feature',
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

    public function testPutProductFeature()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_product-feature',
            [
                'product' => self::$entities['Product:FirstProduct']->getId()->toString(),
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
            ]
        );
        unset($this->payload['feature']);
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
        $this->assertEquals('Manga Corta', $putResponse->product_feature_values['0']->value);
    }

    public function testPutProductFeatureWithEmptyProductFeatureValues()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_product-feature',
            [
                'product' => self::$entities['Product:FirstProduct']->getId()->toString(),
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
            ]
        );
        unset($this->payload['feature']);
        $this->payload['productFeatureValues'] = [];
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
        $this->assertCount(0, $putResponse->product_feature_values);
    }

    public function testPutProductFeatureBadRequest()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_product-feature',
            [
                'product' => self::$entities['Product:FirstProduct']->getId()->toString(),
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
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

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPutProductFeatureNotFound()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_product-feature',
            [
                'product' => self::$entities['Product:SecondProduct']->getId()->toString(),
                'feature' => self::$entities['Feature:Compositions']->getId()->toString(),
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
    public function testPutProductFeatureNotFoundProduct($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_product-feature',
            [
                'product' => $uuid,
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
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
    public function testPutProductFeatureNotFoundFeature($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product_product-feature',
            [
                'product' => self::$entities['Product:FirstProduct']->getId()->toString(),
                'feature' => $uuid,
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

    public function testDeleteProductFeature()
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_product_product-feature',
            [
                'product' => self::$entities['Product:FirstProduct']->getId()->toString(),
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
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
            'umberfirm__product__get_product_product-features',
            [
                'product' => self::$entities['Product:FirstProduct']->getId()->toString(),
            ],
            Router::ABSOLUTE_URL
        );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains('Location', $listUri),
            $this->client->getResponse()->headers
        );
    }

    public function testDeleteProductFeatureNotFound()
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_product_product-feature',
            [
                'product' => self::$entities['Product:SecondProduct']->getId()->toString(),
                'feature' => self::$entities['Feature:Compositions']->getId()->toString(),
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
    public function testDeleteProductFeatureNotFoundProduct($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_product_product-feature',
            [
                'product' => $uuid,
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
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
    public function testDeleteProductFeatureNotFoundFeature($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_product_product-feature',
            [
                'product' => self::$entities['Product:FirstProduct']->getId()->toString(),
                'feature' => $uuid,
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
