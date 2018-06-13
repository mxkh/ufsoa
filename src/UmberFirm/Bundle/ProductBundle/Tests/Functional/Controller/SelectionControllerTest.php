<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\ProductImport;
use UmberFirm\Bundle\ProductBundle\Entity\Selection;
use UmberFirm\Bundle\ProductBundle\Entity\SelectionItem;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class SelectionControllerTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller
 */
class SelectionControllerTest extends BaseTestCase
{
    /**
     * @var array|Selection[]
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
            ->setAddress('Manufacturer street', 'ru');
        static::getObjectManager()->persist($manufacturer);

        $hmShop = new Shop();
        $hmShop->setName('Helen Marlen');
        $hmShop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($hmShop);

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

        $selectionWinter = new Selection();
        /** @var string $locale */
        $selectionWinter->setName('Winter Selection', 'ru');
        $selectionWinter->setDescription('Winter Selection Description', 'ru');
        $selectionWinter->setIsActive(true);
        $selectionWinter->setShop($hmShop);
        $selectionWinter->mergeNewTranslations();
        static::getObjectManager()->persist($selectionWinter);

        $selectionSummer = new Selection();
        $selectionSummer->setName('Summer Selection', 'ru');
        $selectionSummer->setDescription('Summer Selection Description', 'ru');
        $selectionSummer->setIsActive(true);
        $selectionSummer->setShop($hmShop);
        $selectionSummer->mergeNewTranslations();
        static::getObjectManager()->persist($selectionSummer);

        $selectionItem = new SelectionItem();
        $selectionItem->setSelection($selectionSummer);
        $selectionItem->setProduct($product1);
        static::getObjectManager()->persist($selectionItem);

        static::getObjectManager()->flush();

        self::$entities = [
            'Shop:HM' => $hmShop,
            'Selection:Winter' => $selectionWinter,
            'Selection:Summer' => $selectionSummer,
            'Product:1' => $product1,
            'Product:2' => $product2,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->payload = [
            'isActive' => true,
            'shop' => self::$entities['Shop:HM']->getId()->toString(),
            'translations' => [
                'ru' => [
                    'name' => 'selection Spring',
                ],
            ],
        ];

        parent::setUp();
        $this->loginEmployee();
    }

    public function testGetListAction()
    {
        $uri = $this->router->generate('umberfirm__product__get_selections');

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

    public function testGetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_selection',
            [
                'selection' => self::$entities['Selection:Winter']->getId()->toString(),
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
            'umberfirm__product__get_selection',
            [
                'selection' => $uuid,
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
            'umberfirm__product__get_selection_translations',
            [
                'selection' => self::$entities['Selection:Winter']->getId()->toString(),
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
            'umberfirm__product__get_selection_translations',
            [
                'selection' => $uuid,
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

    public function testPostAction()
    {
        $uri = $this->router->generate('umberfirm__product__post_selection');

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

    public function testInvalidParamsPostAction()
    {
        $uri = $this->router->generate('umberfirm__product__post_selection');

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

    public function testPutAction()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_selection',
            [
                'selection' => self::$entities['Selection:Summer']->getId()->toString(),
            ]
        );

        $this->payload['isActive'] = false;

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
        $this->assertEquals($this->payload['isActive'], $putResponse->is_active);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testNotFoundPutAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_selection',
            [
                'selection' => $uuid,
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
            'umberfirm__product__put_selection',
            [
                'selection' => self::$entities['Selection:Summer']->getId()->toString(),
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
            'umberfirm__product__delete_selection',
            [
                'selection' => static::$entities['Selection:Winter']->getId()->toString(),
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
            'umberfirm__product__get_selections',
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
            'umberfirm__product__delete_selection',
            [
                'selection' => $uuid,
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

    public function testPostItemActionWithEmptyData()
    {
        $uri = $this->router->generate('umberfirm__product__post_selection_item', [
            'selection' => self::$entities['Selection:Summer']->getId()->toString()
        ]);

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
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPostItemActionBadUUid($uuid)
    {
        $uri = $this->router->generate('umberfirm__product__post_selection_item', [
            'selection' => $uuid
        ]);

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(['product' => self::$entities['Product:2']->getId()->toString()])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testPostItemActionWithUniqueFailure()
    {
        $uri = $this->router->generate('umberfirm__product__post_selection_item', [
            'selection' => self::$entities['Selection:Summer']->getId()->toString()
        ]);

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(['product' => self::$entities['Product:1']->getId()->toString()])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPostItemAction()
    {
        $uri = $this->router->generate('umberfirm__product__post_selection_item', [
            'selection' => self::$entities['Selection:Summer']->getId()->toString()
        ]);

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(['product' => self::$entities['Product:2']->getId()->toString()])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testNotFoundSelectionDeleteItemAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_selection_item',
            [
                'selection' => $uuid,
                'product' => static::$entities['Product:1']->getId()->toString(),
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
     * @param string $uuid
     */
    public function testNotFoundProductDeleteItemAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_selection_item',
            [
                'selection' => static::$entities['Selection:Summer']->getId()->toString(),
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

    public function testDeleteItemAction()
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_selection_item',
            [
                'selection' => static::$entities['Selection:Summer']->getId()->toString(),
                'product' => static::$entities['Product:1']->getId()->toString(),
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
            'umberfirm__product__get_selection',
            ['selection' => static::$entities['Selection:Summer']->getId()->toString()],
            Router::ABSOLUTE_URL
        );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains('Location', $listUri),
            $this->client->getResponse()->headers
        );
    }
}
