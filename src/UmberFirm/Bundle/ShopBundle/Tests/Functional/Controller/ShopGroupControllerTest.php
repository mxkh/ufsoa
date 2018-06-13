<?php

namespace UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class ShopGroupControllerTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller
 */
class ShopGroupControllerTest extends BaseTestCase
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
            'name' => 'UF GROUP',
        ];
    }

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $hmGroup = new ShopGroup();
        $hmGroup->setName('HM GROUP');
        static::getObjectManager()->persist($hmGroup);

        $mdGroup = new ShopGroup();
        $mdGroup->setName('MD GROUP');
        static::getObjectManager()->persist($mdGroup);

        static::getObjectManager()->flush();

        self::$entities['hmGroup'] = $hmGroup;
        self::$entities['mdGroup'] = $mdGroup;
    }

    /**
     * Test Get Action for viewing list of Shop Groups
     */
    public function testGetListAction()
    {
        $uri = $this->router->generate('umberfirm__shop__get_shop-groups');
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
     * Test Get Action for viewing specified shop group
     */
    public function testGetSpecifiedAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop-group',
            [
                'shopGroup' => self::$entities['hmGroup']->getId()->toString(),
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
     * Try to get shop group on not existed uid
     */
    public function testGetSpecifiedShopGroupNotFoundAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop-group',
            [
                'shopGroup' => Uuid::NIL,
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

    public function testGetSpecifiedShopGroupNotFoundWithBadUuidFormatAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop-group',
            [
                'shopGroup' => 'bad-uuid-format',
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
     * Try to create shop group successfully
     */
    public function testPostAction()
    {
        $uri = $this->router->generate('umberfirm__shop__post_shop-group');

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostActionWithEmptyBodyParams()
    {
        $uri = $this->router->generate('umberfirm__shop__post_shop-group');
        //test with empty body params

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Test create action with invalid body params
     */
    public function testPostActionWithInvalidBodyParams()
    {
        $uri = $this->router->generate('umberfirm__shop__post_shop-group');

        //test on unique
        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode(['name' => 'MD GROUP'])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Try to update shop data successfully
     */
    public function testPutAction()
    {
        $payload = ['name' => 'HELEN MARLEN GROUP'];
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop-group',
            [
                'shopGroup' => self::$entities['hmGroup']->getId()->toString(),
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
            json_encode($payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
        $putResponse = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals($payload['name'], $putResponse->name);
    }

    public function testPutNotFoundWithBadUuidFormatAction()
    {
        $payload = ['name' => 'HELEN MARLEN GROUP'];
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop-group',
            [
                'shopGroup' => 'bad-uuid-format',
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
            json_encode($payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testPutWithInvalidParamsAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop-group',
            [
                'shopGroup' => self::$entities['hmGroup']->getId()->toString(),
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
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Try to delete specified shop group
     */
    public function testDeleteAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_shop-group',
            [
                'shopGroup' => self::$entities['mdGroup']->getId()->toString(),
            ]
        );

        $this->client->request(
            'DELETE',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $this->assertEquals(
            Response::HTTP_NO_CONTENT,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );

        $listUri = $this->router->generate(
            'umberfirm__shop__get_shop-groups',
            [],
            Router::ABSOLUTE_URL
        );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains('Location', $listUri),
            $this->client->getResponse()->headers
        );
    }

    public function testDeleteNotFoundWithBadUuidFormatAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop-group',
            [
                'shopGroup' => 'bad-uuid-format',
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
     * Try to delete item on not existed uid
     */
    public function testDeleteOnNotFoundAction()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_shop-group',
            [
                'shopGroup' => Uuid::NIL,
            ]
        );

        $this->client->request(
            'DELETE',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }
}
