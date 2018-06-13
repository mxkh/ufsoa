<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\DeliveryBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\DeliveryBundle\Entity\DeliveryGroup;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class DeliveryGroupControllerTest
 *
 * @package UmberFirm\Bundle\DeliveryBundle\Tests\Functional\Controller
 */
class DeliveryGroupControllerTest extends BaseTestCase
{
    /**
     * @var UuidEntityInterface[]
     */
    private static $entities;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->loginEmployee();

        $this->payload = [
            'code' => 'nova_poshta_new',
            'translations' => [
                'ua' => [
                    'name' => 'Нова Пошта',
                ],
                'ru' => [
                    'name' => 'Новая почта',
                ],
                'en' => [
                    'name' => 'Nova Poshta',
                ],
            ],
        ];
    }

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $novaPoshta = new DeliveryGroup();
        $novaPoshta->setName('Nova Poshta', 'en');
        $novaPoshta->setDescription('Some description for nova poshta', 'en');
        $novaPoshta->setCode('nova_poshta');
        $novaPoshta->mergeNewTranslations();
        static::getObjectManager()->persist($novaPoshta);

        $DHL = new DeliveryGroup();
        $DHL->setName('DHL', 'en');
        $DHL->setDescription('Some description for DHL', 'en');
        $DHL->setCode('dhl');
        $DHL->mergeNewTranslations();
        static::getObjectManager()->persist($DHL);

        self::$entities['novaPoshta'] = $novaPoshta;
        self::$entities['DHL'] = $DHL;

        static::getObjectManager()->flush();
    }

    public function testCGetDeliveryGroups()
    {
        $uri = $this->router->generate('umberfirm__delivery__get_delivery-groups');

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

    public function testGetDeliveryGroup()
    {
        $uri = $this->router->generate(
            'umberfirm__delivery__get_delivery-group',
            [
                'deliveryGroup' => self::$entities['DHL']->getId()->toString(),
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
     * @param string $uuid
     *
     * @dataProvider invalidUuidDataProvider
     */
    public function testGetDeliveryGroupNotFound(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__delivery__get_delivery-group',
            [
                'deliveryGroup' => $uuid,
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

    public function testGetDeliveryGroupTranslations()
    {
        $uri = $this->router->generate(
            'umberfirm__delivery__get_delivery-group_translations',
            [
                'deliveryGroup' => self::$entities['novaPoshta']->getId()->toString(),
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
     * @param string $uuid
     *
     * @dataProvider invalidUuidDataProvider
     */
    public function testGetDeliveryTranslationsNotFound(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__delivery__get_delivery-group_translations',
            [
                'deliveryGroup' => $uuid,
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
        $uri = $this->router->generate('umberfirm__delivery__post_delivery-group');

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

    public function testPostActionValidation()
    {
        $uri = $this->router->generate('umberfirm__delivery__post_delivery-group');

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

    public function testPutAction()
    {
        $uri = $this->router->generate(
            'umberfirm__delivery__put_delivery-group',
            [
                'deliveryGroup' => self::$entities['novaPoshta']->getId()->toString(),
            ]
        );

        $this->payload['code'] = 'novaPoshta';

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    public function testPutActionValidation()
    {
        $uri = $this->router->generate(
            'umberfirm__delivery__put_delivery-group',
            [
                'deliveryGroup' => self::$entities['novaPoshta']->getId()->toString(),
            ]
        );

        $this->payload['code'] = '';

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param string $uuid
     *
     * @dataProvider invalidUuidDataProvider
     */
    public function testPutActionNotFound(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__delivery__put_delivery-group',
            [
                'deliveryGroup' => $uuid,
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
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testDeleteAction()
    {
        $uri = $this->router->generate(
            'umberfirm__delivery__delete_delivery-group',
            [
                'deliveryGroup' => self::$entities['novaPoshta']->getId()->toString(),
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
            'umberfirm__delivery__get_delivery-groups',
            [],
            Router::ABSOLUTE_URL
        );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains('Location', $listUri),
            $this->client->getResponse()->headers
        );
    }

    /**
     * @param string $uuid
     *
     * @dataProvider invalidUuidDataProvider
     */
    public function testDeleteActionNotFound(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__delivery__delete_delivery-group',
            [
                'deliveryGroup' => $uuid,
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
