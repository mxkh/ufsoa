<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PaymentBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\PaymentBundle\Entity\Payment;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class PaymentControllerTest
 *
 * @package UmberFirm\Bundle\PaymentBundle\Tests\Functional\Controller
 */
class PaymentControllerTest extends BaseTestCase
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

        $cash = new Payment();
        $cash->setCode('cash');
        $cash->setName('Оплата наличными', 'ru');
        $cash->setType(Payment::OFFLINE);
        $cash->setDescription('Оплата наличными', 'ru');
        $cash->mergeNewTranslations();
        static::getObjectManager()->persist($cash);

        $card = new Payment();
        $card->setCode('card');
        $card->setName('Оплата картой', 'ru');
        $card->setType(Payment::ONLINE);
        $card->setDescription('Оплата картой', 'ru');
        $card->mergeNewTranslations();
        static::getObjectManager()->persist($card);

        static::getObjectManager()->flush();

        self::$entities = [
            'Payment:Cash' => $cash,
            'Payment:Card' => $card,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->payload = [
            'code' => 'pos-terminal',
            'type' => Payment::OFFLINE,
            'translations' => [
                'ru' => [
                    'name' => 'POS-Терминал',
                    'description' => 'POS-Терминал',
                ],
            ],
        ];

        parent::setUp();
        $this->loginEmployee();
    }

    public function testGetListAction()
    {
        $uri = $this->router->generate('umberfirm__payment__get_payments');

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
            'umberfirm__payment__get_payment',
            [
                'payment' => self::$entities['Payment:Cash']->getId()->toString(),
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
     * Try to get specified order on not existed uid.
     *
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testTryNotFoundGetAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__payment__get_payment',
            [
                'payment' => $uuid,
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

    public function testGetTranslations()
    {
        $uri = $this->router->generate(
            'umberfirm__payment__get_payment_translations',
            [
                'payment' => self::$entities['Payment:Cash']->getId()->toString(),
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
    public function testGetTranslationsNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__payment__get_payment_translations',
            [
                'payment' => $uuid,
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
        $uri = $this->router->generate('umberfirm__payment__post_payment');

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
        $uri = $this->router->generate('umberfirm__payment__post_payment');

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
            'umberfirm__payment__put_payment',
            [
                'payment' => self::$entities['Payment:Cash']->getId()->toString(),
            ]
        );

        $this->payload['code'] = 'pos-terminal-new';

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
    }

    /**
     * Try to update order with not existed uid.
     *
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testNotFoundPutAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__payment__put_payment',
            [
                'payment' => $uuid,
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
            'umberfirm__payment__put_payment',
            [
                'payment' => self::$entities['Payment:Cash']->getId()->toString(),
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
            'umberfirm__payment__delete_payment',
            [
                'payment' => static::$entities['Payment:Card']->getId()->toString(),
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
            'umberfirm__payment__get_payments',
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
            'umberfirm__payment__delete_payment',
            [
                'payment' => $uuid,
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
