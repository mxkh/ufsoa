<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CommonBundle\Entity\Feedback;
use UmberFirm\Bundle\CommonBundle\Entity\Subject;
use UmberFirm\Bundle\CommonBundle\Event\Subscriber\FeedbackListener;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class FeedbackControllerTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Functional\Controller
 */
class FeedbackControllerTest extends BaseTestCase
{
    /**
     * @var Feedback[]
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
            'name' => 'Joe Doe',
            'email' => 'joedoe@gmail.com',
            'phone' => '380501234567',
            'customer' => self::$entities['customer']->getId()->toString(),
            'message' => 'Lorem Ipsum - это текст-"рыба", часто используемый в печати и вэб-дизайне. Lorem Ipsum является стандартной "рыбой" для текстов на латинице с начала XVI века.',
            'subject' => self::$entities['subject']->getId()->toString(),
            'source' => 'https://google.com.ua',
        ];

        $listener = $this->createMock(FeedbackListener::class);
        $this->client->getContainer()->set('umberfirm.common.event_listener.entity.feedback_listener', $listener);
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        $shop = new Shop();
        $shop->setName('Helen Marlen');
        $shop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($shop);

        $customer = new Customer();
        $customer
            ->setEmail('test@gmail.com')
            ->setShop($shop)
            ->setPhone('+380951234567');
        static::getObjectManager()->persist($customer);

        $subject = new Subject();
        $subject->setName('Пожелание', 'ru');
        $subject->setShop($shop);
        $subject->setIsActive(true);
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
            'shop' => $shop,
            'feedback' => $feedback1,
            'subject' => $subject,
        ];
    }

    public function testFeedbackList()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_feedbacks',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
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

    public function testGetFeedback()
    {
        $id = self::$entities['feedback']->getId()->toString();
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_feedback',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'feedback' => $id,
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
        $this->assertJsonResponse($response, Response::HTTP_OK);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetFeedbackNotFound(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_feedback',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'feedback' => $uuid,
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

    public function testPostFeedbackValidation()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__post_shop_feedback',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
            ]
        );

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ]
        );
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPostFeedback()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__post_shop_feedback',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
            ]
        );
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
    }

    public function testPutFeedback()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_feedback',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'feedback' => self::$entities['feedback']->getId()->toString(),
            ]
        );

        $this->payload['name'] = 'Joe Jr. Doe';

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
        $this->assertJsonResponse($response, Response::HTTP_OK);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPutFeedbackNotFound(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_feedback',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'feedback' => $uuid,
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

    public function testPutFeedbackValidation()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_feedback',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'feedback' => self::$entities['feedback']->getId()->toString(),
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
            ]
        );

        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_BAD_REQUEST);
    }

    public function testDeleteFeedback()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_shop_feedback',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'feedback' => self::$entities['feedback']->getId()->toString(),
            ]
        );
        $listUri = $this->router->generate(
            'umberfirm__shop__get_shop_feedbacks',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
            ],
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
    public function testDeleteFeedbackNotFound(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_shop_feedback',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'feedback' => $uuid,
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
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND);
    }
}
