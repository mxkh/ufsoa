<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CategoryBundle\Tests\Functional\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CategoryBundle\Entity\Category;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;

/**
 * Class CategoryControllerTest
 *
 * @package UmberFirm\Bundle\CategoryBundle\Tests\Functional\Controller
 */
class CategoryControllerTest extends BaseTestCase
{
    /**
     * @var Category[]
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
                'ru' => [
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

        $e1 = new Category();
        $e1->setTitle('Їжа', 'ru');
        $e1->setTitle('Еда', 'ru');
        $e1->setTitle('Comida', 'es');
        $e1->setTitle('Fod', 'en'); // This will be fixed on update test
        $e1->mergeNewTranslations();

        $e2 = new Category();
        $e2->setTitle('Їжа', 'ru');
        $e2->setParent($e1);
        $e2->mergeNewTranslations();

        static::getObjectManager()->persist($e1);
        static::getObjectManager()->persist($e2);
        static::getObjectManager()->flush();
        self::$entities['e1'] = $e1;
        self::$entities['e2'] = $e2;
    }

    public function testCategoryList()
    {
        $uri = $this->router->generate('umberfirm__category__get_categories');

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

    public function testGetCategory()
    {
        $id = self::$entities['e1']->getId()->toString();
        $uri = $this->router->generate(
            'umberfirm__category__get_category',
            [
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
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetCategoryNotFoundBadUuid($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__category__get_category',
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

    public function testGetCategoryTranslations()
    {
        $uri = $this->router->generate(
            'umberfirm__category__get_category_translations',
            [
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

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetCategoryTranslationsNotFoundBadUuid($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__category__get_category_translations',
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

    public function testPostCategory()
    {
        $uri = $this->router->generate('umberfirm__category__post_category');

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

    public function testPutCategory()
    {
        $uri = $this->router->generate(
            'umberfirm__category__put_category',
            [
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

    public function testPutCategoryParentBadRequest()
    {
        $id = self::$entities['e2']->getId()->toString();
        $this->payload['parent'] = $id;
        $uri = $this->router->generate(
            'umberfirm__category__put_category',
            [
                'category' => $id,
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

    public function testPutCategoryBadRequest()
    {
        unset($this->payload['translations']);
        $uri = $this->router->generate(
            'umberfirm__category__put_category',
            [
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

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPutCategoryNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__category__put_category',
            [
                'category' => $uuid,
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

    public function testDeleteCategory()
    {
        $uri = $this->router->generate(
            'umberfirm__category__delete_category',
            [
                'category' => self::$entities['e2']->getId()->toString(),
            ]
        );
        $listUri = $this->router->generate(
            'umberfirm__category__get_categories',
            [],
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

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testDeleteNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__category__delete_category',
            [
                'category' => $uuid,
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

    public function testPostCategoryTranslationValidation()
    {
        unset($this->payload['translations']);
        $uri = $this->router->generate('umberfirm__category__post_category');

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

    public function testPostCategoryParentValidation()
    {
        $this->payload['parent'] = Uuid::NIL;
        $uri = $this->router->generate('umberfirm__category__post_category');

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

    public function testPostCategoryLocale()
    {
        $uri = $this->router->generate('umberfirm__category__post_category');

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT_LANGUAGE' => 'es',
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($this->payload)
        );
        $response = $this->client->getResponse();
        $category = json_decode($response->getContent());
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
        $this->assertEquals('Comida', $category->title);
    }

    public function testPostCategoryDefaultLocale()
    {
        $uri = $this->router->generate('umberfirm__category__post_category');

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT_LANGUAGE' => '',
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($this->payload)
        );
        $response = $this->client->getResponse();
        $category = json_decode($response->getContent());
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
        $this->assertEquals('Еда', $category->title);
    }
}
