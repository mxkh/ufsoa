<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CommonBundle\Entity\Subject;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class SubjectControllerTest
 *
 * @package UmberFirm\Bundle\CommonBundle\Tests\Functional\Controller
 */
class SubjectControllerTest extends BaseTestCase
{
    /**
     * @var Subject[]
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
            'translations' => [
                'ru' => [
                    'name' => 'Предложения',
                ],
            ],
            'isActive' => true,
        ];
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

        $subject = new Subject();
        $subject->setName('Пожелание', 'ru');
        $subject->setShop($shop);
        $subject->setIsActive(true);
        static::getObjectManager()->persist($subject);

        static::getObjectManager()->flush();

        self::$entities = [
            'subject' => $subject,
            'shop' => $shop,
        ];
    }

    public function testSubjectList()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_subjects',
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

    public function testGetSubject()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_subject',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'subject' => self::$entities['subject']->getId()->toString(),
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
    public function testGetSubjectNotFound(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__get_shop_subject',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'subject' => $uuid,
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

    public function testPostSubjectValidation()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__post_shop_subject',
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

    public function testPostSubject()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__post_shop_subject',
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

    public function testPutSubject()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_subject',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'subject' => self::$entities['subject']->getId()->toString(),
            ]
        );

        $this->payload['translations']['ru']['name'] = 'Угрозы';

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
    public function testPutSubjectNotFound(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_subject',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'subject' => $uuid,
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

    public function testPutSubjectValidation()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__put_shop_subject',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'subject' => self::$entities['subject']->getId()->toString(),
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

    public function testDeleteSubject()
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_shop_subject',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'subject' => self::$entities['subject']->getId()->toString(),
            ]
        );
        $listUri = $this->router->generate(
            'umberfirm__shop__get_shop_subjects',
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
    public function testDeleteSubjectNotFound(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__shop__delete_shop_subject',
            [
                'shop' => self::$entities['shop']->getId()->toString(),
                'subject' => $uuid,
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
