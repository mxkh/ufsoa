<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ProductBundle\Entity\Feature;
use UmberFirm\Bundle\ProductBundle\Entity\FeatureValue;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class FeatureValueControllerTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller
 */
class FeatureValueControllerTest extends BaseTestCase
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

        $properties = new Feature();
        $properties
            ->setName('Властивості', 'ua')
            ->setName('Properties', 'en')
            ->setName('Cвойства', 'ru')
            ->setName('Propiedades', 'es')
            ->mergeNewTranslations();
        $manager->persist($properties);

        $compositions = new Feature();
        $compositions
            ->setName('Склад', 'ua')
            ->setName('Compositions', 'en')
            ->setName('Состав', 'ru')
            ->setName('Сomposiciones', 'es')
            ->mergeNewTranslations();
        $manager->persist($compositions);

        $shortSleeve = new FeatureValue();
        $shortSleeve->setFeature($properties)
            ->setValue('Короткий Рукав', 'ua')
            ->setValue('Short Sleeve', 'en')
            ->setValue('Короткий Рукав', 'ru')
            ->setValue('Manga Corta', 'es')
            ->mergeNewTranslations();
        $manager->persist($shortSleeve);

        $colorfulDress = new FeatureValue();
        $colorfulDress->setFeature($properties)
            ->setValue('Барвисті Сукні', 'ua')
            ->setValue('Colorful Dress', 'en')
            ->setValue('Красочные Платья', 'ru')
            ->setValue('Vestido Colorido', 'es')
            ->mergeNewTranslations();
        $manager->persist($colorfulDress);

        $shortDress = new FeatureValue();
        $shortDress->setFeature($properties)
            ->setValue('Короткі Сукні', 'ua')
            ->setValue('Short Dress', 'en')
            ->setValue('Короткие Платье', 'ru')
            ->setValue('Vestido Corto', 'es')
            ->mergeNewTranslations();
        $manager->persist($shortDress);

        $longDress = new FeatureValue();
        $longDress->setFeature($compositions)
            ->setValue('Короткі Сукні', 'ua')
            ->setValue('Short Dress', 'en')
            ->setValue('Короткие Платье', 'ru')
            ->setValue('Vestido Corto', 'es')
            ->mergeNewTranslations();
        $manager->persist($longDress);

        $manager->flush();

        self::$entities = [
            'Feature:Properties' => $properties,
            'Feature:Compositions' => $compositions,
            'Feature:Properties:ShortSleeve' => $shortSleeve,
            'Feature:Properties:ColorfulDress' => $colorfulDress,
            'Feature:Properties:ShortDress' => $shortDress,
            'Feature:Properties:LongDress' => $longDress,
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
                    'value' => 'Короткий Рукав',
                ],
                'en' => [
                    'value' => 'Short Sleeve',
                ],
                'ru' => [
                    'value' => 'Короткий Рукав',
                ],
                'es' => [
                    'value' => 'Mángà Córtá', //'Manga Corta',
                ],
            ],
        ];

        parent::setUp();
        $this->loginEmployee();
    }

    public function testGetFeatureValueList()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature_feature-values',
            [
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
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
    public function testGetFeatureValueListFeatureNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature_feature-values',
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

    public function testGetFeatureValue()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature_feature-value',
            [
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
                'featureValue' => self::$entities['Feature:Properties:ColorfulDress']->getId()->toString(),
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
    public function testGetFeatureValueNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature_feature-value',
            [
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
                'featureValue' => $uuid,
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

    public function testGetFeatureValueNotFoundWithWrongFeature()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature_feature-value',
            [
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
                'featureValue' => self::$entities['Feature:Properties:LongDress']->getId()->toString(),
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

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetFeatureValueFeatureNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature_feature-value',
            [
                'feature' => $uuid,
                'featureValue' => self::$entities['Feature:Properties:ColorfulDress']->getId()->toString(),
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

    public function testGetFeatureValueFeatureTranslations()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature_feature-value_translations',
            [
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
                'featureValue' => self::$entities['Feature:Properties:ColorfulDress']->getId()->toString(),
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
    public function testGetFeatureValueTranslationsNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature_feature-value_translations',
            [
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
                'featureValue' => $uuid,
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

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetFeatureValueFeatureTranslationsNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature_feature-value_translations',
            [
                'feature' => $uuid,
                'featureValue' => self::$entities['Feature:Properties:ColorfulDress']->getId()->toString(),
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

    public function testGetFeatureValueTranslationsNotFoundWithWrongFeature()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature_feature-value_translations',
            [
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
                'featureValue' => self::$entities['Feature:Properties:LongDress']->getId()->toString(),
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

    public function testPostFeatureValue()
    {
        $uri = $this->router->generate(
            'umberfirm__product__post_feature_feature-value',
            [
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
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

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostFeatureValueBadRequest()
    {
        $uri = $this->router->generate(
            'umberfirm__product__post_feature_feature-value',
            [
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
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
            json_encode(['hello' => 'world'])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param $uuid
     */
    public function testPostFeatureValueFeatureNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__post_feature_feature-value',
            [
                'feature' => $uuid,
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

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testPutFeatureValue()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature_feature-value',
            [
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
                'featureValue' => self::$entities['Feature:Properties:ColorfulDress']->getId()->toString(),
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
        $this->assertEquals('Mángà Córtá', $putResponse->value);
    }

    public function testPutFeatureValueBadRequest()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature_feature-value',
            [
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
                'featureValue' => self::$entities['Feature:Properties:ColorfulDress']->getId()->toString(),
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
    public function testPutFeatureValueNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature_feature-value',
            [
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
                'featureValue' => $uuid,
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

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPutFeatureValueFeatureNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature_feature-value',
            [
                'feature' => $uuid,
                'featureValue' => self::$entities['Feature:Properties:ColorfulDress']->getId()->toString(),
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

    public function testPutFeatureValueNotFoundWithWrongFeature()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature_feature-value',
            [
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
                'featureValue' => self::$entities['Feature:Properties:LongDress']->getId()->toString(),
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

    public function testDeleteFeatureValueNotFoundWithWrongFeature()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature_feature-value',
            [
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
                'featureValue' => self::$entities['Feature:Properties:LongDress']->getId()->toString(),
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

    public function testDeleteFeatureValue()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature_feature-value',
            [
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
                'featureValue' => self::$entities['Feature:Properties:ColorfulDress']->getId()->toString(),
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
            'umberfirm__product__get_feature_feature-values',
            [
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
            ],
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
    public function testDeleteFeatureValueNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature_feature-value',
            [
                'feature' => self::$entities['Feature:Properties']->getId()->toString(),
                'featureValue' => $uuid,
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

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testDeleteFeatureValueFeatureNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_feature_feature-value',
            [
                'feature' => $uuid,
                'featureValue' => self::$entities['Feature:Properties:ShortDress']->getId()->toString(),
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
