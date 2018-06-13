<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Functional\Subscription;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;
use UmberFirm\Bundle\SubscriptionBundle\Entity\Subscriber;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class SubscriptionControllerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Functional\Subscription
 */
class SubscriptionControllerTest extends BaseTestCase
{
    /**
     * @var UuidEntityInterface[]|Shop[]
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

        $hmGroup = new ShopGroup();
        $hmGroup->setName('HM Group');

        $shop = new Shop();
        $shop->setShopGroup($hmGroup)
            ->setName('Helen Marlen')
            ->setApiKey('00000000000000000000000000000000');

        $john = new Subscriber();
        $john->setEmail('john@doe.com');

        $melanie = new Subscriber();
        $melanie->setEmail('melanie@doe.com');

        self::getObjectManager()->persist($hmGroup);
        self::getObjectManager()->persist($shop);
        self::getObjectManager()->persist($john);
        self::getObjectManager()->persist($melanie);
        self::getObjectManager()->flush();

        self::$entities['shop'] = $shop;
        self::$entities['john'] = $john;
        self::$entities['melanie'] = $melanie;
    }

    public function testPostSubscriber()
    {
        $uri = $this->router->generate('umberfirm__public__post_subscribe');

        $token = ['shop' => self::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ],
            json_encode(['email' => 'adam@doe.com'])
        );
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostUniqueEmailValidation()
    {
        $uri = $this->router->generate('umberfirm__public__post_subscribe');

        $token = ['shop' => self::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ],
            json_encode($this->payload)
        );
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPostEmailValidation()
    {
        $uri = $this->router->generate('umberfirm__public__post_subscribe');

        $token = ['shop' => self::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ],
            json_encode(['email' => 'some@email'])
        );
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPostEmptyEmailValidation()
    {
        $uri = $this->router->generate('umberfirm__public__post_subscribe');

        $token = ['shop' => self::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ],
            json_encode(['email' => ''])
        );
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }
}
