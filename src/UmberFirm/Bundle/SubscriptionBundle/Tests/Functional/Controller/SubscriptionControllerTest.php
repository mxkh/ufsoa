<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SubscriptionBundle\Tests\Functional\Controller;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\SubscriptionBundle\Entity\Subscriber;

/**
 * Class SubscriptionControllerTest
 *
 * @package UmberFirm\Bundle\SubscriptionBundle\Tests\Functional\Controller
 */
class SubscriptionControllerTest extends BaseTestCase
{
    /**
     * @var Subscriber[]
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
            'email' => 'john@doe.com',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $john = new Subscriber();
        $john->setEmail('john@doe.com');

        $melanie = new Subscriber();
        $melanie->setEmail('melanie@doe.com');

        static::getObjectManager()->persist($john);
        static::getObjectManager()->persist($melanie);
        static::getObjectManager()->flush();

        self::$entities['john'] = $john;
        self::$entities['melanie'] = $melanie;
    }

    public function testPostSubscriber()
    {
        $uri = $this->router->generate('umberfirm__subscription__post_subscribe');

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(['email' => 'adam@doe.com'])
        );
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostUniqueEmailValidation()
    {
        $uri = $this->router->generate('umberfirm__subscription__post_subscribe');

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

    public function testPostEmailValidation()
    {
        $uri = $this->router->generate('umberfirm__subscription__post_subscribe');

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(['email' => 'some@email'])
        );
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPostEmptyEmailValidation()
    {
        $uri = $this->router->generate('umberfirm__subscription__post_subscribe');

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(['email' => ''])
        );
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }
}
