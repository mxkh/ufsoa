<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\OrderBundle\Entity\PromocodeEnum;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class PromocodeEnumControllerTest
 *
 * @package UmberFirm\Bundle\OrderBundle\Tests\Functional\Controller
 */
class PromocodeEnumControllerTest extends BaseTestCase
{
    /**
     * @var array|UuidEntityInterface[]|PromocodeEnum[]
     */
    private static $entities = [];

    /**
     * @var string
     */
    private static $locale;

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $sum = new PromocodeEnum();
        self::$locale = $sum->getCurrentLocale();
        $sum->setCode('sum');
        $sum->setName('Sum', self::$locale);
        $sum->setCalculate('%s - %s');
        $sum->mergeNewTranslations();
        static::getObjectManager()->persist($sum);

        $percent = new PromocodeEnum();
        $percent->setCode('percent');
        $percent->setName('Percent', self::$locale);
        $percent->setCalculate('%s * (%s / 100)');
        $percent->mergeNewTranslations();
        static::getObjectManager()->persist($percent);

        static::getObjectManager()->flush();

        self::$entities = [
            'PromocodeEnum:Sum' => $sum,
            'PromocodeEnum:Percent' => $percent,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->payload = [
            'code' => 'pay-more',
            'calculate' => '%s * %s',
            'translations' => [
                'ua' => [
                    'name' => 'Pay More',
                ],
                'en' => [
                    'name' => 'Pay More en',
                ],
            ],
        ];

        parent::setUp();
        $this->loginEmployee();
    }

    public function testGetListAction()
    {
        $uri = $this->router->generate('umberfirm__order__get_promocode-enums');

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
            'umberfirm__order__get_promocode-enum',
            [
                'promocodeEnum' => self::$entities['PromocodeEnum:Sum']->getId()->toString(),
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
    public function testTryNotFoundGetAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__order__get_promocode-enum',
            [
                'promocodeEnum' => $uuid,
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

    public function testGetTranslationsAction()
    {
        $uri = $this->router->generate(
            'umberfirm__order__get_promocode-enum_translations',
            [
                'promocodeEnum' => static::$entities['PromocodeEnum:Sum']->getId()->toString(),
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
            'umberfirm__order__get_promocode-enum_translations',
            [
                'promocodeEnum' => $uuid,
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
        $uri = $this->router->generate('umberfirm__order__post_promocode-enum');

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
        $uri = $this->router->generate('umberfirm__order__post_promocode-enum');

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
            'umberfirm__order__put_promocode-enum',
            [
                'promocodeEnum' => self::$entities['PromocodeEnum:Sum']->getId()->toString(),
            ]
        );

        $this->payload['code'] = self::$entities['PromocodeEnum:Sum']->getCode();

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
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testNotFoundPutAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__order__put_promocode-enum',
            [
                'promocodeEnum' => $uuid,
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
            'umberfirm__order__put_promocode-enum',
            [
                'promocodeEnum' => self::$entities['PromocodeEnum:Sum']->getId()->toString(),
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
            'umberfirm__order__delete_promocode-enum',
            [
                'promocodeEnum' => static::$entities['PromocodeEnum:Sum']->getId()->toString(),
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
            'umberfirm__order__get_promocode-enums',
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
            'umberfirm__order__delete_promocode-enum',
            [
                'promocodeEnum' => $uuid,
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
