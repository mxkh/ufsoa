<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierStoreMapping;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class StoreMappingControllerTest
 *
 * @package UmberFirm\Bundle\SupplierBundle\Tests\Functional\Controller
 */
class StoreMappingControllerTest extends BaseTestCase
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
            'store' => self::$entities['storeMandarin']->getId()->toString(),
            'supplierStore' => 'sadsdads-sadadsdasd-dsda-s-da-saddsdad',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $iconSupplier = new Supplier();
        $iconSupplier->setName('icon', 'en');
        $iconSupplier->setDescription('description icon', 'en');
        $iconSupplier->setIsActive(true);
        $iconSupplier->setUsername('supplier');
        $iconSupplier->setPassword(password_hash('1234567890', PASSWORD_DEFAULT));
        $iconSupplier->mergeNewTranslations();

        $flowSupplier = new Supplier();
        $flowSupplier->setName('flow', 'en');
        $flowSupplier->setDescription('description flow', 'en');
        $flowSupplier->setIsActive(true);
        $flowSupplier->setUsername('flow supplier');
        $flowSupplier->setPassword(password_hash('1234567890', PASSWORD_DEFAULT));
        $flowSupplier->mergeNewTranslations();

        $storeOcean = new Store();
        $storeOcean->setName('Ocean Plaza');
        $storeOcean->setAddress(
            'Київ, вул. Антоновича, 176, ТРЦ "Ocean Plaza", 1-й поверх',
            'ua'
        );
        $storeOcean->setDescription(
            'Helen Marlen Ocean - shop democratic men\'s and women\'s shoes and accessories.'.
            ' Here is presented the whole variety of styles - from classics to sports models'.
            ' - both European and American brands, including: Fabio Rusconi, Marc by Marc Jacobs,'.
            ' Vetiver, Eddy Daniele, Crime London, Veja, Stokton, Colors of California, MOU, Minna Parikka,'.
            ' Roberto Festa, Logan, Salar, Rebecca Minkoff, and others.',
            'ua'
        );
        $storeOcean->setSchedule('пн. - нд. з 10:00 до 22:00', 'ua');
        $storeOcean->setIsActive(true);
        $storeOcean->mergeNewTranslations();

        $storeMandarin = new Store();
        $storeMandarin->setName('Mandarin');
        $storeMandarin->setAddress(
            'Київ, вул. Антоновича, 176, ТРЦ "Ocean Plaza", 1-й поверх',
            'ua'
        );
        $storeMandarin->setDescription(
            'Helen Marlen Ocean - shop democratic men\'s and women\'s shoes and accessories.'.
            ' Here is presented the whole variety of styles - from classics to sports models'.
            ' - both European and American brands, including: Fabio Rusconi, Marc by Marc Jacobs,'.
            ' Vetiver, Eddy Daniele, Crime London, Veja, Stokton, Colors of California, MOU,'.
            ' Minna Parikka, Roberto Festa, Logan, Salar, Rebecca Minkoff, and others.',
            'ua'
        );
        $storeMandarin->setSchedule('пн. - нд. з 10:00 до 22:00', 'ua');
        $storeMandarin->setIsActive(true);
        $storeMandarin->mergeNewTranslations();

        $storeOceanIconMapping = new SupplierStoreMapping();
        $storeOceanIconMapping->setSupplier($iconSupplier);
        $storeOceanIconMapping->setSupplierStore('Ocean Plaza');
        $storeOceanIconMapping->setStore($storeOcean);

        static::getObjectManager()->persist($iconSupplier);
        static::getObjectManager()->persist($flowSupplier);
        static::getObjectManager()->persist($storeOcean);
        static::getObjectManager()->persist($storeOceanIconMapping);
        static::getObjectManager()->persist($storeMandarin);

        static::getObjectManager()->flush();

        static::$entities = [
            'iconSupplier' => $iconSupplier,
            'flowSupplier' => $flowSupplier,
            'storeOcean' => $storeOcean,
            'storeOceanIconMapping' => $storeOceanIconMapping,
            'storeMandarin' => $storeMandarin,
        ];
    }

    public function testGetList()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__get_supplier_store-mappings',
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
            'umberfirm__supplier__get_supplier_store-mappings',
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
            'umberfirm__supplier__get_supplier_store-mapping',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
                'mapping' => self::$entities['storeOceanIconMapping']->getId()->toString(),
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
            'umberfirm__supplier__get_supplier_store-mapping',
            [
                'supplier' => self::$entities['flowSupplier']->getId()->toString(),
                'mapping' => self::$entities['storeOceanIconMapping']->getId()->toString(),
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
            'umberfirm__supplier__get_supplier_store-mapping',
            [
                'supplier' => $uuid,
                'mapping' => self::$entities['storeOceanIconMapping']->getId()->toString(),
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
            'umberfirm__supplier__get_supplier_store-mapping',
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
            'umberfirm__supplier__post_supplier_store-mapping',
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
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testCreateOnUniqueFields()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__post_supplier_store-mapping',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
            ]
        );

        $this->payload['supplierStore'] = self::$entities['storeOceanIconMapping']->getSupplierStore();

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testCreateOnEmptyFields()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__post_supplier_store-mapping',
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
            'umberfirm__supplier__post_supplier_store-mapping',
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
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testUpdate()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__put_supplier_store-mapping',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
                'mapping' => self::$entities['storeOceanIconMapping']->getId()->toString(),
            ]
        );

        $this->payload['supplierStore'] = 'Ocean Plaza Store';

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
        $putResponse = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals($this->payload['supplierStore'], $putResponse->supplier_store);
    }

    public function testUpdateInconsistentArguments()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__put_supplier_store-mapping',
            [
                'supplier' => self::$entities['flowSupplier']->getId()->toString(),
                'mapping' => self::$entities['storeOceanIconMapping']->getId()->toString(),
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
            'umberfirm__supplier__put_supplier_store-mapping',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
                'mapping' => self::$entities['storeOceanIconMapping']->getId()->toString(),
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
            'umberfirm__supplier__put_supplier_store-mapping',
            [
                'supplier' => $uuid,
                'mapping' => self::$entities['storeOceanIconMapping']->getId()->toString(),
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
            json_encode($this->payload)
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
            'umberfirm__supplier__put_supplier_store-mapping',
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
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testDeleteInconsistentArguments()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__delete_supplier_store-mapping',
            [
                'supplier' => self::$entities['flowSupplier']->getId()->toString(),
                'mapping' => self::$entities['storeOceanIconMapping']->getId()->toString(),
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
            'umberfirm__supplier__delete_supplier_store-mapping',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
                'mapping' => self::$entities['storeOceanIconMapping']->getId()->toString(),
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
            'umberfirm__supplier__get_supplier_store-mappings',
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
            'umberfirm__supplier__delete_supplier_store-mapping',
            [
                'supplier' => $uuid,
                'mapping' => self::$entities['storeOceanIconMapping']->getId()->toString(),
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
            'umberfirm__supplier__delete_supplier_store-mapping',
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
