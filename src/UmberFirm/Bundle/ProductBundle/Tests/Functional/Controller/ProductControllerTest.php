<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class ProductControllerTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller
 */
class ProductControllerTest extends BaseTestCase
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

        $manufacturer = new Manufacturer();
        $manufacturer
            ->setName('Manufacturer')
            ->setWebsite('Manufacturer.com')
            ->setAddress('Manufacturer street', 'ua');
        static::getObjectManager()->persist($manufacturer);

        $hmShop = new Shop();
        $hmShop->setName('Helen Marlen');
        $hmShop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($hmShop);

        $product1 = new Product();
        $product1
            ->setManufacturer($manufacturer)
            ->setShop($hmShop)
            ->setName('product1', 'ua')
            ->setIsHidden(false)
            ->mergeNewTranslations();
        static::getObjectManager()->persist($product1);

        $product2 = new Product();
        $product2
            ->setManufacturer($manufacturer)
            ->setShop($hmShop)
            ->setName('product2', 'ua')
            ->setIsHidden(false)
            ->mergeNewTranslations();
        static::getObjectManager()->persist($product2);

        static::getObjectManager()->flush();

        self::$entities = [
            'product1' => $product1,
            'product2' => $product2,
            'manufacturer' => $manufacturer,
            'hmShop' => $hmShop,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->payload = [
            'salePrice' => 1000,
            'price' => 1500,
            'isHidden' => true,
            'manufacturer' => self::$entities['manufacturer']->getId()->toString(),
            'shop' => self::$entities['hmShop']->getId()->toString(),
            'translations' => [
                'ua' => [
                    'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                    'shortDescription' => 'Lorem Ipsum is simply...',
                    'name' => 'product003',
                ],
            ],
        ];

        parent::setUp();
        $this->loginEmployee();
    }

    /**
     * Try to get list of products
     */
    public function testGetListAction()
    {
        $uri = $this->router->generate('umberfirm__product__get_products');

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
     * Try to get specified product
     */
    public function testGetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product',
            [
                'product' => self::$entities['product1']->getId()->toString(),
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
    public function testTryNotFoundGetAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product',
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

    public function testGetTranslationsAction()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_translations',
            [
                'product' => self::$entities['product1']->getId()->toString(),
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
    public function testTryGetTranslationsNotFoundGetAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_product_translations',
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

    /**
     * Try to create product
     */
    public function testPostAction()
    {
        $uri = $this->router->generate('umberfirm__product__post_product');

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

    /**
     * Try to create product with invalid params
     */
    public function testInvalidParamsPostAction()
    {
        $uri = $this->router->generate('umberfirm__product__post_product');

        //with empty params
        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Try to update product
     */
    public function testPutAction()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product',
            [
                'product' => self::$entities['product2']->getId()->toString(),
            ]
        );

        $this->payload['isHidden'] = true;

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
        $this->assertEquals($this->payload['isHidden'], $putResponse->is_hidden);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testNotFoundPutAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product',
            [
                'product' => $uuid,
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

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testInvalidParamsPutAction()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_product',
            [
                'product' => self::$entities['product2']->getId()->toString(),
            ]
        );

        //with empty params
        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testDeleteAction()
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_product',
            [
                'product' => static::$entities['product1']->getId()->toString(),
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
            'umberfirm__product__get_products',
            [],
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
     * @param string $uuid
     */
    public function testNotFoundDeleteAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_product',
            [
                'product' => $uuid,
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
