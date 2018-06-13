<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ProductBundle\Entity\Feature;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class FeatureControllerTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller
 */
class FeatureControllerTest extends BaseTestCase
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

        $manager = static::getObjectManager();
        $compositions = new Feature();
        $compositions
            ->setName('Склад', 'ua')
            ->setName('Compositions', 'en')
            ->setName('Состав', 'ru')
            ->setName('Сomposiciones', 'es')
            ->mergeNewTranslations();
        $manager->persist($compositions);

        $styles = new Feature();
        $styles
            ->setName('Стилі', 'ua')
            ->setName('Styles', 'en')
            ->setName('Стили', 'ru')
            ->setName('Estilos', 'es')
            ->mergeNewTranslations();
        $manager->persist($styles);

        $manager->flush();

        self::$entities = [
            'Feature:Compositions' => $compositions,
            'Feature:Styles' => $styles,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->payload = [
            'translations' => [
                'ua' => [
                    'name' => 'Властивості',
                ],
                'en' => [
                    'name' => 'Properties',
                ],
                'ru' => [
                    'name' => 'Cвойства',
                ],
                'es' => [
                    'name' => 'Própiédãdës', //'Propiedades',
                ],
            ],
        ];

        parent::setUp();
        $this->loginEmployee();
    }

    public function testGetFeatureList()
    {
        $uri = $this->router->generate('umberfirm__product__get_features');

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

    public function testGetFeature()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature',
            [
                'feature' => static::$entities['Feature:Compositions']->getId()->toString(),
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
    public function testGetFeatureNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature',
            [
                'feature' => $uuid,
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

    public function testGetFeatureTranslations()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature_translations',
            [
                'feature' => static::$entities['Feature:Compositions']->getId()->toString(),
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
    public function testGetFeatureTranslationsNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature_translations',
            [
                'feature' => $uuid,
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

    public function testPostFeature()
    {
        $uri = $this->router->generate('umberfirm__product__post_feature');

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

    public function testPostFeatureBadRequest()
    {
        $uri = $this->router->generate('umberfirm__product__post_feature');

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(['hello' => 'world'])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPutFeature()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_feature',
            [
                'feature' => static::$entities['Feature:Compositions']->getId()->toString(),
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
                'HTTP_ACCEPT_LANGUAGE' => 'es',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);

        $putResponse = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals('Própiédãdës', $putResponse->name);
    }

    public function testPutFeatureBadRequest()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_feature',
            [
                'feature' => static::$entities['Feature:Compositions']->getId()->toString(),
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
                'HTTP_ACCEPT_LANGUAGE' => 'es',
            ],
            json_encode(['hello' => 'world'])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPutFeatureNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_feature',
            [
                'feature' => $uuid,
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
                'HTTP_ACCEPT_LANGUAGE' => 'es',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testDeleteFeature()
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_feature',
            [
                'feature' => static::$entities['Feature:Compositions']->getId()->toString(),
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
            'umberfirm__product__get_features',
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
    public function testDeleteFeatureNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_feature',
            [
                'feature' => $uuid,
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
