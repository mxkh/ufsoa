<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CommonBundle\Entity\Currency;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;

/**
 * Class CurrencyControllerTest
 *
 * @package UmberFirm\Bundle\CommonBundle\Tests\Functional\Controller
 */
class CurrencyControllerTest extends BaseTestCase
{
    /**
     * @var Currency[]
     */
    private static $entities = [];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->loginEmployee();

        $this->payload = ['code' => 'USD', 'name' => 'U.S. Dollar'];
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $e1 = new Currency();
        $e1->setName('U.S. Dolar'); // This will be fixed on update test
        $e1->setCode('USD');
        static::getObjectManager()->persist($e1);
        static::getObjectManager()->flush();
        self::$entities['e1'] = $e1;
    }

    public function testCurrencyList()
    {
        $uri = $this->router->generate('umberfirm__common__get_currencies');
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

    public function testGetCurrency()
    {
        $id = self::$entities['e1']->getId()->toString();
        $uri = $this->router->generate(
            'umberfirm__common__get_currency',
            [
                'currency' => $id,
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
        $currency = json_decode($response->getContent());
        $this->assertJsonResponse($response, Response::HTTP_OK);
        $this->assertEquals($id, $currency->id);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetCurrencyNotFound(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__common__get_currency',
            [
                'currency' => $uuid,
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

    public function testPostCurrencyValidation()
    {
        $uri = $this->router->generate('umberfirm__common__post_currency');

        /** Test `code` field for ISO 4217 https://en.wikipedia.org/wiki/ISO_4217 */
        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(['code' => 'US'])
        );
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPostCurrency()
    {
        $uri = $this->router->generate('umberfirm__common__post_currency');
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

    public function testPutCurrency()
    {
        $uri = $this->router->generate(
            'umberfirm__common__put_currency',
            [
                'currency' => self::$entities['e1']->getId()->toString(),
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
        $currency = json_decode($response->getContent());
        $this->assertEquals('U.S. Dollar', $currency->name);
        $this->assertJsonResponse($response, Response::HTTP_OK);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPutCurrencyNotFound(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__common__put_currency',
            [
                'currency' => $uuid,
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

    public function testPutCurrencyValidation()
    {
        unset($this->payload['code']);
        $uri = $this->router->generate(
            'umberfirm__common__put_currency',
            [
                'currency' => self::$entities['e1']->getId()->toString(),
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
        $this->assertJsonResponse($response, Response::HTTP_BAD_REQUEST);
    }

    public function testDeleteCurrency()
    {
        $uri = $this->router->generate(
            'umberfirm__common__delete_currency',
            [
                'currency' => self::$entities['e1']->getId()->toString(),
            ]
        );
        $listUri = $this->router->generate(
            'umberfirm__common__get_currencies',
            [],
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
    public function testDeleteCurrencyNotFound(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__common__delete_currency',
            [
                'currency' => $uuid,
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
