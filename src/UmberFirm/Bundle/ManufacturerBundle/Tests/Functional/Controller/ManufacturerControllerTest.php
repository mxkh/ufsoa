<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ManufacturerBundle\Tests\Functional\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;

/**
 * Class ManufacturerControllerTest
 *
 * @package UmberFirmManufacturerBundle\Tests\Controller
 */
class ManufacturerControllerTest extends BaseTestCase
{
    /**
     * @var object
     */
    public static $postResponse;

    /**
     * @var array
     */
    public $put;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->payload = [
            'website' => '123',
            'translations' => [
                'ua' => [
                    'address' => '123',
                ],
                'ru' => [
                    'address' => '123',
                ],
                'es' => [
                    'address' => '123',
                ],
                'en' => [
                    'address' => null,
                ],
            ],
        ];

        $this->put = [
            'website' => '123',
            'translations' => [
                'ua' => [
                    'address' => '123',
                ],
                'ru' => [
                    'address' => '123',
                ],
                'es' => [
                    'address' => '123',
                ],
                'en' => [
                    'address' => '123',
                ],
            ],
        ];

        parent::setUp();
        $this->loginEmployee();
    }

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
    }

    public function testGetManufacturerList()
    {
        $uri = $this->router->generate('umberfirm__manufacturer__get_manufacturers');

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
    public function testGetNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__manufacturer__get_manufacturer',
            [
                'manufacturer' => $uuid,
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
    public function testGetTranslationsNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__manufacturer__get_manufacturer_translations',
            [
                'manufacturer' => $uuid,
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
        $uri = $this->router->generate('umberfirm__manufacturer__post_manufacturer');
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

        self::$postResponse = json_decode($this->client->getResponse()->getContent());
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testGetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__manufacturer__get_manufacturer',
            [
                'manufacturer' => self::$postResponse->id,
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
        $getResponse = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals(self::$postResponse->id, $getResponse->id);
    }

    public function testGetTranslations()
    {
        $uri = $this->router->generate(
            'umberfirm__manufacturer__get_manufacturer_translations',
            [
                'manufacturer' => self::$postResponse->id,
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

    public function testPutAction()
    {
        $this->markTestSkipped('Rewrite the whole test from scratch.');

        $uri = $this->router->generate(
            'umberfirm__manufacturer__put_manufacturer',
            [
                'manufacturer' => self::$postResponse->id,
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
            json_encode($this->put)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    public function testPutAttributeBadRequest()
    {
        $uri = $this->router->generate(
            'umberfirm__manufacturer__put_manufacturer',
            [
                'manufacturer' => self::$postResponse->id,
            ]
        );
        $this->payload['attributeGroup'] = Uuid::NIL;
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

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPutAttributeNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__manufacturer__put_manufacturer',
            [
                'manufacturer' => $uuid,
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

    public function testDeleteAction()
    {
        $uri = $this->router->generate(
            'umberfirm__manufacturer__delete_manufacturer',
            [
                'manufacturer' => self::$postResponse->id,
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
            'umberfirm__manufacturer__get_manufacturers',
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
    public function testDeleteNotFoundAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__manufacturer__delete_manufacturer',
            [
                'manufacturer' => $uuid,
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
