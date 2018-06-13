<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CategoryBundle\Entity\Category;
use UmberFirm\Bundle\CategoryBundle\Entity\CategorySeo;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class CategorySeoController
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller
 */
class CategorySeoControllerTest extends BaseTestCase
{
    /**
     * @var array|Category[]|Shop[]
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
            'shop' => self::$entities['hmShop']->getId()->toString(),
            'translations' => [
                'ru' => [
                    'title' => 'new title',
                    'description' => 'new description',
                    'keywords' => 'new keywords',
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

        $hmShop = new Shop();
        $hmShop->setName('Helen Marlen');
        $hmShop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($hmShop);

        $food1 = new Category();
        $food1->setTitle('Їжа', 'ua');
        $food1->mergeNewTranslations();

        $food2 = new Category();
        $food2->setTitle('Some cat', 'ua');
        $food2->mergeNewTranslations();

        $food1Seo = new Category();
        $food1Seo->setTitle('Їжа 2', 'ua');
        $food1Seo->setParent($food1);
        $food1Seo->mergeNewTranslations();

        $food1Seo = new CategorySeo();
        $food1Seo->setTitle('seo title', 'ua');
        $food1Seo->setKeywords('seo keywords', 'ua');
        $food1Seo->setDescription('seo description', 'ua');
        $food1Seo->setCategory($food1);
        $food1Seo->setShop($hmShop);
        $food1Seo->mergeNewTranslations();

        static::getObjectManager()->persist($food1);
        static::getObjectManager()->persist($food2);
        static::getObjectManager()->persist($food1Seo);
        static::getObjectManager()->persist($food1Seo);
        static::getObjectManager()->flush();

        self::$entities = [
            'food1' => $food1,
            'food2' => $food2,
            'hmShop' => $hmShop,
            'food1Seo' => $food1Seo,
        ];
    }

    public function testCgetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__category__get_category_seos',
            [
                'category' => static::$entities['food1']->getId()->toString(),
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
            'umberfirm__category__get_category_seos',
            [
                'category' => $uuid,
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
            'umberfirm__category__get_category_seo',
            [
                'category' => static::$entities['food2']->getId()->toString(),
                'seo' => static::$entities['food1Seo']->getId()->toString(),
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
            'umberfirm__category__get_category_seo_translations',
            [
                'category' => static::$entities['food2']->getId()->toString(),
                'seo' => static::$entities['food1Seo']->getId()->toString(),
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
            'umberfirm__category__put_category_seo',
            [
                'category' => static::$entities['food2']->getId()->toString(),
                'seo' => static::$entities['food1Seo']->getId()->toString(),
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
            'umberfirm__category__get_category_seo',
            [
                'category' => static::$entities['food1']->getId()->toString(),
                'seo' => static::$entities['food1Seo']->getId()->toString(),
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
            'umberfirm__category__get_category_seo',
            [
                'category' => $uuid,
                'seo' => static::$entities['food1Seo']->getId()->toString(),
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
            'umberfirm__category__get_category_seo',
            [
                'category' => static::$entities['food1']->getId()->toString(),
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
            'umberfirm__category__get_category_seo_translations',
            [
                'category' => static::$entities['food1']->getId()->toString(),
                'seo' => static::$entities['food1Seo']->getId()->toString(),
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
            'umberfirm__category__get_category_seo_translations',
            [
                'category' => $uuid,
                'seo' => static::$entities['food1Seo']->getId()->toString(),
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
            'umberfirm__category__get_category_seo_translations',
            [
                'category' => static::$entities['food1']->getId()->toString(),
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
            'umberfirm__category__put_category_seo',
            [
                'category' => self::$entities['food2']->getId()->toString(),
                'seo' => static::$entities['food1Seo']->getId()->toString(),
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
            'umberfirm__category__post_category_seo',
            [
                'category' => static::$entities['food2']->getId()->toString(),
                'seo' => static::$entities['food1Seo']->getId()->toString(),
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
            'umberfirm__category__post_category_seo',
            [
                'category' => static::$entities['food1']->getId()->toString(),
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
            'umberfirm__category__post_category_seo',
            [
                'category' => $uuid,
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
            'umberfirm__category__post_category_seo',
            [
                'category' => static::$entities['food2']->getId()->toString(),
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
            'umberfirm__category__put_category_seo',
            [
                'category' => static::$entities['food1']->getId()->toString(),
                'seo' => static::$entities['food1Seo']->getId()->toString(),
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
            'umberfirm__category__put_category_seo',
            [
                'category' => static::$entities['food1']->getId()->toString(),
                'seo' => static::$entities['food1Seo']->getId()->toString(),
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
            'umberfirm__category__put_category_seo',
            [
                'category' => static::$entities['food1']->getId()->toString(),
                'seo' => static::$entities['food1Seo']->getId()->toString(),
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
            'umberfirm__category__put_category_seo',
            [
                'category' => $uuid,
                'seo' => static::$entities['food1Seo']->getId()->toString(),
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
            'umberfirm__category__put_category_seo',
            [
                'category' => static::$entities['food1']->getId()->toString(),
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
