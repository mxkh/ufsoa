<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Tests\Functional\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CommonBundle\Entity\Language;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;

/**
 * Class LanguageControllerTest
 *
 * @package UmberFirm\Bundle\CommonBundle\Tests\Functional\Controller
 */
class LanguageControllerTest extends BaseTestCase
{
    /**
     * @var Language[]
     */
    private static $entities = [];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->loginEmployee();

        $this->payload = ['code' => 'EN', 'name' => 'English'];
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $e1 = new Language();
        $e1->setName('Englesh'); // This will be fixed on update test
        $e1->setCode('USD');
        static::getObjectManager()->persist($e1);
        static::getObjectManager()->flush();
        self::$entities['e1'] = $e1;
    }

    public function testLanguageList()
    {
        $uri = $this->router->generate('umberfirm__common__get_languages');
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

    public function testGetLanguage()
    {
        $id = self::$entities['e1']->getId()->toString();
        $uri = $this->router->generate(
            'umberfirm__common__get_language',
            [
                'language' => $id,
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
        $language = json_decode($response->getContent());
        $this->assertJsonResponse($response, Response::HTTP_OK);
        $this->assertEquals($id, $language->id);
    }

    public function testGetLanguageNotFound()
    {
        $uri = $this->router->generate(
            'umberfirm__common__get_language',
            [
                'language' => Uuid::NIL,
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
        $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND);
    }

    public function testGetLanguageNotFoundBadUuid()
    {
        $uri = $this->router->generate(
            'umberfirm__common__get_language',
            [
                'language' => 'bad-uuid',
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
        $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND);
    }

    public function testPostLanguageValidation()
    {
        $uri = $this->router->generate('umberfirm__common__post_language');

        /** Test `code` field for ISO 3166-1 alpha-2 https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2 */
        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(['code' => 'ENG'])
        );
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPostLanguage()
    {
        $uri = $this->router->generate('umberfirm__common__post_language');
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

    public function testPutLanguage()
    {
        $uri = $this->router->generate(
            'umberfirm__common__put_language',
            [
                'language' => self::$entities['e1']->getId()->toString(),
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
        $language = json_decode($response->getContent());
        $this->assertEquals('English', $language->name);
        $this->assertJsonResponse($response, Response::HTTP_OK);
    }

    public function testPutLanguageNotFound()
    {
        $uri = $this->router->generate(
            'umberfirm__common__put_language',
            [
                'language' => Uuid::NIL,
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

    public function testPutLanguageNotFoundBadUuid()
    {
        $uri = $this->router->generate(
            'umberfirm__common__put_language',
            [
                'language' => 'bad-uuid',
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

    public function testPutLanguageValidation()
    {
        unset($this->payload['code']);
        $uri = $this->router->generate(
            'umberfirm__common__put_language',
            [
                'language' => self::$entities['e1']->getId()->toString(),
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

    public function testDeleteLanguage()
    {
        $uri = $this->router->generate(
            'umberfirm__common__delete_language',
            [
                'language' => self::$entities['e1']->getId()->toString(),
            ]
        );
        $listUri = $this->router->generate(
            'umberfirm__common__get_languages',
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

    public function testDeleteLanguageNotFound()
    {
        $uri = $this->router->generate(
            'umberfirm__common__delete_language',
            [
                'language' => Uuid::NIL,
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

    public function testDeleteLanguageNotFoundBadUuid()
    {
        $uri = $this->router->generate(
            'umberfirm__common__delete_language',
            [
                'language' => 'bad-uuid',
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
