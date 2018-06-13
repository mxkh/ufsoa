<?php

namespace UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\SettingsAttribute;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class SettingsAttributeController
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller
 */
class SettingsAttributeControllerTest extends BaseTestCase
{
    /**
     * @var array|UuidEntityInterface[]
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
            'name' => 'email',
            'type' => 'string',
        ];
    }

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $email = new SettingsAttribute();
        $email
            ->setName('email')
            ->setType('integer');
        static::getObjectManager()->persist($email);

        $currencyDefault = new SettingsAttribute();
        $currencyDefault
            ->setName('currencyDefault')
            ->setType('uid');
        static::getObjectManager()->persist($currencyDefault);

        static::getObjectManager()->flush();

        self::$entities = [
            'email' => $email,
            'currencyDefault' => $currencyDefault,
        ];
    }

    /**
     * Testing get list of settings attributes
     */
    public function testGetListAction()
    {
        $uri = $this->router->generate('umberfirm__shop__get_settings-attributes');

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
     * Try to get specified settings attribute
     */
    public function testGetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_settings-attribute',
            [
                'attribute' => self::$entities['currencyDefault']->getId()->toString(),
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

    public function testGetOnBadUuidFormatAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_settings-attribute',
            [
                'attribute' => 'uuid-bad-foramt',
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
     * Try to get specified settings attribute of not existed uid
     */
    public function testNotFoundGetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_settings-attribute',
            [
                'attribute' => Uuid::NIL,
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
     * Testing successful creating settings attribute
     */
    public function testPostAction()
    {
        $uri = $this->router->generate('umberfirm__shop__post_settings-attribute');

        $this->payload['name'] = 'new_email';

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
     * Testing validation on empty body params post action
     */
    public function testValidationOnEmptyPostAction()
    {
        $uri = $this->router->generate('umberfirm__shop__post_settings-attribute');

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
     * Testing validation on empty body params post action
     */
    public function testValidationOnUniquePostAction()
    {
        $uri = $this->router->generate('umberfirm__shop__post_settings-attribute');

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
     * Testing successful Updating settings attribute
     */
    public function testPutAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_settings-attribute',
            [
                'attribute' => self::$entities['email']->getId()->toString(),
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
        $this->assertEquals($this->payload['type'], $putResponse->type);
    }

    public function testPutOnBadUuidFormatAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_settings-attribute',
            [
                'attribute' => 'uuid-bad-foramt',
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

    public function testPutWithInvalidParamsAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_settings-attribute',
            [
                'attribute' => self::$entities['email']->getId()->toString(),
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
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPutAttributeNotFound()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_settings-attribute',
            [
                'attribute' => Uuid::NIL,
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
     * Testings delete action
     */
    public function testDeleteAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_settings-attribute',
            [
                'attribute' => self::$entities['currencyDefault']->getId()->toString(),
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
            'umberfirm__shop__get_settings-attributes',
            [],
            Router::ABSOLUTE_URL
        );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains('Location', $listUri),
            $this->client->getResponse()->headers
        );
    }

    public function testDeleteOnBadUuidFormatAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_settings-attribute',
            [
                'attribute' => 'uuid-bad-foramt',
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
     * Try to delete settings attribute with not existed uid
     */
    public function testNotFoundDeleteAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_settings-attribute',
            [
                'attribute' => Uuid::NIL,
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
