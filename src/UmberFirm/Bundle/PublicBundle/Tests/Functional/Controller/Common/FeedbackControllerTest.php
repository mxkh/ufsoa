<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Customer;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CommonBundle\Entity\Feedback;
use UmberFirm\Bundle\CommonBundle\Entity\Subject;
use UmberFirm\Bundle\CommonBundle\Event\Subscriber\FeedbackListener;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class FeedbackControllerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Customer
 */
class FeedbackControllerTest extends BaseTestCase
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

        $shop = new Shop();
        $shop
            ->setName('Helen Marlen')
            ->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($shop);

        $customer = new Customer();
        $customer
            ->setEmail('test@email.me')
            ->setPhone('+380501234567')
            ->setShop($shop);
        static::getObjectManager()->persist($customer);

        $customer1 = new Customer();
        $customer1
            ->setEmail('test@email.me')
            ->setPhone('+380501234567')
            ->setShop($shop);
        static::getObjectManager()->persist($customer1);

        $subject = new Subject();
        $subject->setName('Пожелание', 'ru');
        $subject->setIsActive(true);
        $subject->setShop($shop);
        static::getObjectManager()->persist($subject);

        $feedback1 = new Feedback();
        $feedback1->setName('Joe Doe');
        $feedback1->setEmail('joedoe@gmail.com');
        $feedback1->setPhone('380501234567');
        $feedback1->setCustomer($customer);
        $feedback1->setMessage(
            'Lorem Ipsum - это текст-"рыба", часто используемый в печати и вэб-дизайне. Lorem Ipsum является стандартной "рыбой" для текстов на латинице с начала XVI века.'
        );
        $feedback1->setShop($shop);
        $feedback1->setSource('https://google.com.ua');
        $feedback1->setSubject($subject);
        static::getObjectManager()->persist($feedback1);

        static::getObjectManager()->flush();

        self::$entities = [
            'customer' => $customer,
            'customer1' => $customer1,
            'shop' => $shop,
            'feedback' => $feedback1,
            'subject' => $subject,
        ];
    }

    /**
     * {@inheritdoc]
     */
    protected function setUp()
    {
        parent::setUp();

        $this->payload = [
            'name' => 'Joe Doe',
            'email' => 'joedoe@gmail.com',
            'phone' => '380501234567',
            'message' => 'Lorem Ipsum - это текст-"рыба", часто используемый в печати и вэб-дизайне. Lorem Ipsum является стандартной "рыбой" для текстов на латинице с начала XVI века.',
            'subject' => self::$entities['subject']->getId()->toString(),
            'source' => 'https://google.com.ua',
        ];

        $listener = $this->createMock(FeedbackListener::class);
        $this->client->getContainer()->set('umberfirm.common.event_listener.entity.feedback_listener', $listener);
    }

    public function testCgetAction()
    {
        $uri = $this->router->generate('umberfirm__public__get_feedbacks');

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

        $this->client->request(
            'GET',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    public function testGetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_feedback',
            [
                'feedback' => static::$entities['feedback']->getId()->toString(),
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

        $this->client->request(
            'GET',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    public function testGetActionCustomerNotFound()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_feedback',
            [
                'feedback' => static::$entities['feedback']->getId()->toString(),
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer1']->getToken(),
        ];

        $this->client->request(
            'GET',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetActionFeedbackBadUuid(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_feedback',
            [
                'feedback' => $uuid,
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

        $this->client->request(
            'GET',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testPostActionWithAuthorizedCustomer()
    {
        $uri = $this->router->generate('umberfirm__public__post_feedback');

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

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

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostActionWithUnauthorizedCustomer()
    {
        $uri = $this->router->generate('umberfirm__public__post_feedback');

        $token = ['shop' => static::$entities['shop']->getApiKey()];

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

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostActionInvalidParams()
    {
        $uri = $this->router->generate('umberfirm__public__post_feedback');

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

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
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }
}
