<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Tests\Functional\Controller;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CommonBundle\Entity\Gender;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;

/**
 * Class GenderControllerTest
 *
 * @package UmberFirm\Bundle\CommonBundle\Tests\Functional\Controller
 */
class GenderControllerTest extends BaseTestCase
{
    /**
     * @var array
     */
    private $content;

    /**
     * @var array
     */
    private static $entities;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $gender = new Gender();
        $gender->setName('men', 'en');
        $gender->setName('чоловiк', 'ua');
        $gender->setName('мужчина', 'ru');
        $gender->mergeNewTranslations();
        static::getObjectManager()->persist($gender);

        static::getObjectManager()->flush();

        self::$entities = [
            'gender' => $gender,
        ];
    }

    protected function setUp()
    {
        parent::setUp();
        $this->loginEmployee();
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return json_encode($this->content);
    }

    /**
     * Test Get Action for viewing list of Genders
     */
    public function testGetListAction()
    {
        $uri = $this->router->generate('umberfirm__common__get_genders');
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
     * Test Get Action for viewing specified gender
     */
    public function testGetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__common__get_gender',
            [
                'gender' => static::$entities['gender']->getId()->toString(),
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
     * Try to get specified gender by wrong id
     *
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__common__get_gender',
            [
                'gender' => $uuid,
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
     * Test Get Action for viewing specified gender
     */
    public function testGetTranslationsAction()
    {
        $uri = $this->router->generate(
            'umberfirm__common__get_gender_translations',
            [
                'gender' => static::$entities['gender']->getId()->toString(),
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
     * Try to get specified gender by wrong id
     *
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetTranslationsNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__common__get_gender_translations',
            [
                'gender' => $uuid,
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
     * Try to create gender successfully
     */
    public function testPostAction()
    {
        $this->setContent(
            [
                'translations' => [
                    'en' => [
                        'name' => 'men',
                    ],
                    'es' => [
                        'name' => 'el hombre',
                    ],
                    'ru' => [
                        'name' => 'мужчина',
                    ],
                    'ua' => [
                        'name' => 'чоловiк',
                    ],
                ],
            ]
        );

        $uri = $this->router->generate('umberfirm__common__post_gender');

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            $this->getContent()
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostActionWithEmptyBodyParams()
    {
        //test with empty body params
        $this->setContent([]);

        $uri = $this->router->generate('umberfirm__common__post_gender');

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            $this->getContent()
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPostActionWithInvalidBodyParams($uuid)
    {
        //test with invalid body params
        $this->setContent(
            [
                'gender' => $uuid,
            ]
        );

        $uri = $this->router->generate('umberfirm__common__post_gender');

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            $this->getContent()
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Try to update gender data successfully
     */
    public function testPutAction()
    {
        $this->setContent(
            [
                'translations' => [
                    'en' => [
                        'name' => 'men',
                    ],
                    'es' => [
                        'name' => 'el hombre',
                    ],
                    'ru' => [
                        'name' => 'мужчина',
                    ],
                    'ua' => [
                        'name' => 'чоловiк',
                    ],
                ],
            ]
        );

        $uri = $this->router->generate(
            'umberfirm__common__put_gender',
            [
                'gender' => static::$entities['gender']->getId()->toString(),
            ]
        );

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            $this->getContent()
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    public function testPutWithInvalidParamsAction()
    {
        $this->setContent([]);
        $uri = $this->router->generate(
            'umberfirm__common__put_gender',
            [
                'gender' => self::$entities['gender']->getId()->toString(),
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
            $this->getContent()
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPutGenderNotFound($uuid)
    {
        $this->setContent([]);
        $uri = $this->router->generate(
            'umberfirm__common__put_gender',
            [
                'gender' => $uuid,
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
            $this->getContent()
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    /**
     * @param array $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}
