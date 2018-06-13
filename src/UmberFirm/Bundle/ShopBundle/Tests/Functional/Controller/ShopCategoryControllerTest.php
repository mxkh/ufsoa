<?php

namespace UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CategoryBundle\Entity\Category;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class ShopCategoryControllerTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller
 */
class ShopCategoryControllerTest extends BaseTestCase
{
    /**
     * @var Shop[]|Category[]
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
            'translations' => [
                'ua' => [
                    'title' => 'Їжа',
                ],
                'ru' => [
                    'title' => 'Еда',
                ],
                'es' => [
                    'title' => 'Comida',
                ],
                'en' => [
                    'title' => 'Food',
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $shop = new Shop();
        $shop->setName('Shop');
        $shop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($shop);
        static::getObjectManager()->flush();
        self::$entities['shop'] = $shop;

        $secondShop = new Shop();
        $secondShop->setName('Shop');
        $secondShop->setApiKey('11111111111111111111111111111111');
        static::getObjectManager()->persist($secondShop);
        static::getObjectManager()->flush();
        self::$entities['secondShop'] = $secondShop;

        $e1 = new Category();
        $e1->setTitle('Їжа', 'ua');
        $e1->setTitle('Еда', 'ru');
        $e1->setTitle('Comida', 'es');
        $e1->setTitle('Fod', 'en'); // This will be fixed on update test
        $e1->setShop($shop);
        $e1->mergeNewTranslations();
        static::getObjectManager()->persist($e1);
        static::getObjectManager()->flush();
        self::$entities['e1'] = $e1;
    }

    public function testShopCategoryList()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_categories',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
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

    public function testGetShopCategory()
    {
        $id = self::$entities['e1']->getId()->toString();
        $shop = self::$entities['shop']->getId()->toString();
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_category',
            [
                'shop' => $shop,
                'category' => $id,
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
        $response = $this->client->getResponse();
        $category = json_decode($response->getContent());
        $this->assertJsonResponse($response, Response::HTTP_OK);
        $this->assertEquals($id, $category->id);
        $this->assertEquals($shop, $category->shop->id);
    }

    public function testGetShopNotContainsCategory()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_category',
            [
                'shop' => self::$entities['secondShop']->getId()->toString(),
                'category' => self::$entities['e1']->getId()->toString(),
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

    public function testGetShopCategoryNotFoundShop()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_category',
            [
                'shop' => Uuid::NIL,
                'category' => self::$entities['e1']->getId()->toString(),
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

    public function testGetShopCategoryNotFoundShopBadUuid()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_category',
            [
                'shop' => 'bad-uuid',
                'category' => 'bad-uui',
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

    public function testGetShopCategoryNotFoundCategory()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_category',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'category' => Uuid::NIL,
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

    public function testGetShopCategoryNotFoundCategoryBadUuid()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_category',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'category' => 'bad-uui',
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

    public function testPostShopCategory()
    {
        $shop = self::$entities['shop']->getId()->toString();
        $uri = $this->router->generate(
            'umberfirm__shop__post_shop_category',
            [
                'shop' => $shop,
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
        $response = $this->client->getResponse();
        $category = json_decode($response->getContent());
        $this->assertJsonResponse($response, Response::HTTP_CREATED);
        $this->assertEquals($shop, $category->shop->id);
    }

    public function testPostShopCategoryValidation()
    {
        unset($this->payload['translations']);
        $uri = $this->router->generate(
            'umberfirm__shop__post_shop_category',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
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
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_BAD_REQUEST);
    }

    public function testPutShopCategory()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_category',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'category' => self::$entities['e1']->getId()->toString(),
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
        $response = $this->client->getResponse();
        $category = json_decode($response->getContent());
        $this->assertJsonResponse($response, Response::HTTP_OK);
        $this->assertEquals('Food', $category->title);
    }

    public function testPutShopNotContainsCategory()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_category',
            [
                'shop' => self::$entities['secondShop']->getId()->toString(),
                'category' => self::$entities['e1']->getId()->toString(),
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
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND);
    }

    public function testPutShopCategoryNotFoundShop()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_category',
            [
                'shop' => Uuid::NIL,
                'category' => Uuid::NIL,
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
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND);
    }

    public function testPutShopCategoryNotFoundCategory()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_category',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'category' => Uuid::NIL,
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
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND);
    }

    public function testPutShopCategoryNotFoundShopBadUuid()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_category',
            [
                'shop' => 'bad=uuid',
                'category' => 'bad-uuid',
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
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND);
    }

    public function testPutShopCategoryNotFoundCategoryBadUuid()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_category',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'category' => 'bad-uuid',
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
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND);
    }

    public function testPutShopCategoryValidation()
    {
        unset($this->payload['translations']);
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_category',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'category' => self::$entities['e1']->getId()->toString(),
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
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_BAD_REQUEST);
    }

    public function testDeleteShopNotContainsCategory()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_shop_category',
            [
                'shop' => self::$entities['secondShop']->getId()->toString(),
                'category' => self::$entities['e1']->getId()->toString(),
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
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND);
    }

    public function testDeleteShopCategory()
    {
        $shop = self::$entities['shop']->getId()->toString();
        $uri = $this->router->generate(
            'umberfirm__shop__delete_shop_category',
            [
                'shop' => $shop,
                'category' => self::$entities['e1']->getId()->toString(),
            ]
        );
        $listUri = $this->router->generate(
            'umberfirm__shop__get_shop_categories',
            [
                'shop' => $shop,
            ],
            Router::ABSOLUTE_URL
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
        $this->assertTrue(
            $this->client->getResponse()->headers->contains('Location', $listUri),
            $this->client->getResponse()->headers
        );
    }

    public function testDeleteShopCategoryNotFoundShop()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_shop_category',
            [
                'shop' => Uuid::NIL,
                'category' => self::$entities['e1']->getId()->toString(),
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
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND);
    }

    public function testDeleteShopCategoryNotFoundShopBadUuid()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_shop_category',
            [
                'shop' => 'bad-uuid',
                'category' => self::$entities['e1']->getId()->toString(),
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
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND);
    }

    public function testDeleteShopCategoryNotFoundCategory()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_shop_category',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'category' => Uuid::NIL,
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
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND);
    }

    public function testDeleteShopCategoryNotFoundCategoryBadUuid()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_shop_category',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'category' => 'bad-uui',
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
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND);
    }
}
