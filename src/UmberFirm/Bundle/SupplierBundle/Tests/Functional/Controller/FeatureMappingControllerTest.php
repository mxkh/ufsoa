<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ProductBundle\Entity\Feature;
use UmberFirm\Bundle\ProductBundle\Entity\FeatureValue;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierFeatureMapping;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class FeatureMappingControllerTest
 *
 * @package UmberFirm\Bundle\SupplierBundle\Tests\Functional\Controller
 */
class FeatureMappingControllerTest extends BaseTestCase
{
    /**
     * @var array|UuidEntityInterface[]
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
            'new' => [
                'featureValue' => self::$entities['military']->getId()->toString(),
                'supplierFeatureKey' => 'стиль',
                'supplierFeatureValue' => 'воєнний',
            ],
            'mappedFeatureUnique' => [
                'featureValue' => self::$entities['mappedFeature']->getFeatureValue()->getId()->toString(),
                'supplierFeatureKey' => self::$entities['mappedFeature']->getSupplierFeatureKey(),
                'supplierFeatureValue' => self::$entities['mappedFeature']->getSupplierFeatureValue(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $styles = new Feature();
        $styles
            ->setName('Стилі', 'ua')
            ->setName('Styles', 'en')
            ->setName('Стили', 'ru')
            ->setName('Estilos', 'es')
            ->mergeNewTranslations();
        static::getObjectManager()->persist($styles);

        $casual = new FeatureValue();
        $casual->setFeature($styles)
            ->setValue('Повсякденный', 'ua')
            ->setValue('Casual', 'en')
            ->setValue('Повседневный', 'ru')
            ->setValue('Casual', 'es')
            ->mergeNewTranslations();
        static::getObjectManager()->persist($casual);

        $military = new FeatureValue();
        $military->setFeature($styles)
            ->setValue('Військовий', 'ua')
            ->setValue('Military', 'en')
            ->setValue('Военный', 'ru')
            ->setValue('Militar', 'es')
            ->mergeNewTranslations();
        static::getObjectManager()->persist($military);

        $iconSupplier = new Supplier();
        $iconSupplier->setName('icon', 'en');
        $iconSupplier->setDescription('description icon', 'en');
        $iconSupplier->setIsActive(true);
        $iconSupplier->setUsername('supplier');
        $iconSupplier->setPassword(password_hash('1234567890', PASSWORD_DEFAULT));
        $iconSupplier->mergeNewTranslations();
        static::getObjectManager()->persist($iconSupplier);

        $flowSupplier = new Supplier();
        $flowSupplier->setName('flow', 'en');
        $flowSupplier->setDescription('description flow', 'en');
        $flowSupplier->setIsActive(true);
        $flowSupplier->setUsername('flow supplier');
        $flowSupplier->setPassword(password_hash('1234567890', PASSWORD_DEFAULT));
        $flowSupplier->mergeNewTranslations();
        static::getObjectManager()->persist($flowSupplier);

        $mappedFeature = new SupplierFeatureMapping();
        $mappedFeature->setSupplier($iconSupplier);
        $mappedFeature->setSupplierFeatureKey('Мода');
        $mappedFeature->setSupplierFeatureValue('Кєжуальна');
        $mappedFeature->setFeatureValue($casual);
        static::getObjectManager()->persist($mappedFeature);

        static::getObjectManager()->flush();

        static::$entities = [
            'mappedFeature' => $mappedFeature,
            'iconSupplier' => $iconSupplier,
            'flowSupplier' => $flowSupplier,
            'styles' => $styles,
            'casual' => $casual,
            'military' => $military,
        ];
    }

    public function testGetList()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__get_supplier_feature-mappings',
            [
                'supplier' => static::$entities['iconSupplier']->getId()->toString(),
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
    public function testNotFoundGetListOnNotExistedSupplier($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__get_supplier_feature-mappings',
            [
                'supplier' => $uuid,
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

    public function testGetOne()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__get_supplier_feature-mapping',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
                'mapping' => self::$entities['mappedFeature']->getId()->toString(),
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

    public function testGetOneInconsistentArguments()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__get_supplier_feature-mapping',
            [
                'supplier' => self::$entities['flowSupplier']->getId()->toString(),
                'mapping' => self::$entities['mappedFeature']->getId()->toString(),
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
    public function testNotFoundGetOneOnNotExistedSupplier($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__get_supplier_feature-mapping',
            [
                'supplier' => $uuid,
                'mapping' => self::$entities['mappedFeature']->getId()->toString(),
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
    public function testNotFoundGetOneOnNotExistedMapping($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__get_supplier_feature-mapping',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
                'mapping' => $uuid,
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

    public function testCreate()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__post_supplier_feature-mapping',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
            ]
        );

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($this->payload['new'])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testCreateOnUniqueFields()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__post_supplier_feature-mapping',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
            ]
        );

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($this->payload['mappedFeatureUnique'])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testCreateOnEmptyFields()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__post_supplier_feature-mapping',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
            ]
        );

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testCreateOnNotExistedSupplier($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__post_supplier_feature-mapping',
            [
                'supplier' => $uuid,
            ]
        );

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($this->payload['new'])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testUpdate()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__put_supplier_feature-mapping',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
                'mapping' => self::$entities['mappedFeature']->getId()->toString(),
            ]
        );

        $this->payload['mappedFeatureUnique']['supplierFeatureValue'] = 'something';

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($this->payload['mappedFeatureUnique'])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
        $putResponse = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals(
            $this->payload['mappedFeatureUnique']['supplierFeatureValue'],
            $putResponse->supplier_feature_value
        );
    }

    public function testUpdateInconsistentArguments()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__put_supplier_feature-mapping',
            [
                'supplier' => self::$entities['flowSupplier']->getId()->toString(),
                'mapping' => self::$entities['mappedFeature']->getId()->toString(),
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

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testUpdateOnEmptyData()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__put_supplier_feature-mapping',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
                'mapping' => self::$entities['mappedFeature']->getId()->toString(),
            ]
        );

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testUpdateOnNotExistedSupplier($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__put_supplier_feature-mapping',
            [
                'supplier' => $uuid,
                'mapping' => self::$entities['mappedFeature']->getId()->toString(),
            ]
        );

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($this->payload['new'])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testUpdateOnNotExistedMapping($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__put_supplier_feature-mapping',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
                'mapping' => $uuid,
            ]
        );

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($this->payload['new'])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testDeleteInconsistentArguments()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__delete_supplier_feature-mapping',
            [
                'supplier' => self::$entities['flowSupplier']->getId()->toString(),
                'mapping' => self::$entities['mappedFeature']->getId()->toString(),
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

    public function testDelete()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__delete_supplier_feature-mapping',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
                'mapping' => self::$entities['mappedFeature']->getId()->toString(),
            ]
        );

        $this->client->request(
            'DELETE',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $this->assertEquals(
            Response::HTTP_NO_CONTENT,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );

        $listUri = $this->router->generate(
            'umberfirm__supplier__get_supplier_feature-mappings',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
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
    public function testDeleteOnNotExistedSupplier($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__delete_supplier_feature-mapping',
            [
                'supplier' => $uuid,
                'mapping' => self::$entities['mappedFeature']->getId()->toString(),
            ]
        );

        $this->client->request(
            'DELETE',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testDeleteOnNotExistedMapping($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__delete_supplier_feature-mapping',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
                'mapping' => $uuid,
            ]
        );

        $this->client->request(
            'DELETE',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }
}
