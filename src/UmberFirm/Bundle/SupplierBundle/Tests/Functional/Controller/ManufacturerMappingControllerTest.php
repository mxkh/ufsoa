<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierManufacturerMapping;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class ManufacturerMappingControllerTest
 *
 * @package UmberFirm\Bundle\SupplierBundle\Tests\Functional\Controller
 */
class ManufacturerMappingControllerTest extends BaseTestCase
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
                'manufacturer' => self::$entities['manufacturer3']->getId()->toString(),
                'supplierManufacturer' => 'sadsdads-sadadsdasd-dsda-s-da-saddsdad',
            ],
            'manufacturerMapping3' => [
                'manufacturer' => self::$entities['manufacturerMapping3']->getManufacturer(),
                'supplierManufacturer' => self::$entities['manufacturerMapping3']->getSupplierManufacturer(),
            ],
            'manufacturerMapping2' => [
                'manufacturer' => self::$entities['manufacturer2']->getId()->toString(),
                'supplierManufacturer' => self::$entities['manufacturerMapping2']->getSupplierManufacturer(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $manufacturer1 = new Manufacturer();
        $manufacturer1->setName('43Milano');
        $manufacturer1->setAddress('Via Alessandro Manzoni, 43, 20121 Milano, Italy', 'en');
        $manufacturer1->setWebsite('www.43cycles.com');
        $manufacturer1->mergeNewTranslations();

        $manufacturer2 = new Manufacturer();
        $manufacturer2->setName('A.L.C.');
        $manufacturer2->setAddress('210 Fifth Ave, 2nd Floor, New York, NY 10010', 'en');
        $manufacturer2->setWebsite('www.alcltd.com');
        $manufacturer2->mergeNewTranslations();

        $manufacturer3 = new Manufacturer();
        $manufacturer3->setName('Alberto Guardiani');
        $manufacturer3->setAddress('Milano (MI) Corso Venezia, 16 Italy', 'en');
        $manufacturer3->setWebsite('www.albertoguardiani.com');
        $manufacturer3->mergeNewTranslations();

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
        $flowSupplier->setUsername('supplier2');
        $flowSupplier->setPassword(password_hash('1234567890', PASSWORD_DEFAULT));
        $flowSupplier->mergeNewTranslations();

        $manufacturerMapping1 = new SupplierManufacturerMapping();
        $manufacturerMapping1->setSupplier($iconSupplier);
        $manufacturerMapping1->setSupplierManufacturer('Alexander WANGGG');
        $manufacturerMapping1->setManufacturer($manufacturer1);

        $manufacturerMapping2 = new SupplierManufacturerMapping();
        $manufacturerMapping2->setSupplier($iconSupplier);
        $manufacturerMapping2->setSupplierManufacturer('100002');
        $manufacturerMapping2->setManufacturer($manufacturer2);

        $manufacturerMapping3 = new SupplierManufacturerMapping();
        $manufacturerMapping3->setSupplier($flowSupplier);
        $manufacturerMapping3->setSupplierManufacturer('ALC');
        $manufacturerMapping3->setManufacturer($manufacturer3);

        static::getObjectManager()->persist($manufacturer1);
        static::getObjectManager()->persist($manufacturer2);
        static::getObjectManager()->persist($manufacturer3);
        static::getObjectManager()->persist($iconSupplier);
        static::getObjectManager()->persist($flowSupplier);
        static::getObjectManager()->persist($manufacturerMapping1);
        static::getObjectManager()->persist($manufacturerMapping2);
        static::getObjectManager()->persist($manufacturerMapping3);

        static::getObjectManager()->flush();

        static::$entities = [
            'manufacturer1' => $manufacturer1,
            'manufacturer2' => $manufacturer2,
            'manufacturer3' => $manufacturer3,
            'iconSupplier' => $iconSupplier,
            'flowSupplier' => $flowSupplier,
            'manufacturerMapping1' => $manufacturerMapping1,
            'manufacturerMapping2' => $manufacturerMapping2,
            'manufacturerMapping3' => $manufacturerMapping3,
        ];
    }

    public function testGetList()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__get_supplier_manufacturer-mappings',
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
            'umberfirm__supplier__get_supplier_manufacturer-mappings',
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
            'umberfirm__supplier__get_supplier_manufacturer-mapping',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
                'mapping' => self::$entities['manufacturerMapping1']->getId()->toString(),
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
            'umberfirm__supplier__get_supplier_manufacturer-mapping',
            [
                'supplier' => self::$entities['flowSupplier']->getId()->toString(),
                'mapping' => self::$entities['manufacturerMapping1']->getId()->toString(),
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
            'umberfirm__supplier__get_supplier_manufacturer-mapping',
            [
                'supplier' => $uuid,
                'mapping' => self::$entities['manufacturerMapping1']->getId()->toString(),
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
            'umberfirm__supplier__get_supplier_manufacturer-mapping',
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
            'umberfirm__supplier__post_supplier_manufacturer-mapping',
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
            'umberfirm__supplier__post_supplier_manufacturer-mapping',
            [
                'supplier' => self::$entities['flowSupplier']->getId()->toString(),
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
            json_encode($this->payload['manufacturerMapping3'])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testCreateOnEmptyFields()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__post_supplier_manufacturer-mapping',
            [
                'supplier' => self::$entities['flowSupplier']->getId()->toString(),
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
            'umberfirm__supplier__post_supplier_manufacturer-mapping',
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
            'umberfirm__supplier__put_supplier_manufacturer-mapping',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
                'mapping' => self::$entities['manufacturerMapping2']->getId()->toString(),
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
            json_encode($this->payload['manufacturerMapping2'])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
        $putResponse = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals(
            $this->payload['manufacturerMapping2']['supplierManufacturer'],
            $putResponse->supplier_manufacturer
        );
    }

    public function testUpdateInconsistentArguments()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__put_supplier_manufacturer-mapping',
            [
                'supplier' => self::$entities['flowSupplier']->getId()->toString(),
                'mapping' => self::$entities['manufacturerMapping1']->getId()->toString(),
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
            'umberfirm__supplier__put_supplier_manufacturer-mapping',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
                'mapping' => self::$entities['manufacturerMapping2']->getId()->toString(),
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
            'umberfirm__supplier__put_supplier_manufacturer-mapping',
            [
                'supplier' => $uuid,
                'mapping' => self::$entities['manufacturerMapping2']->getId()->toString(),
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
            json_encode($this->payload['manufacturerMapping2'])
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
            'umberfirm__supplier__put_supplier_manufacturer-mapping',
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
            json_encode($this->payload['manufacturerMapping2'])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testDeleteInconsistentArguments()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__delete_supplier_manufacturer-mapping',
            [
                'supplier' => self::$entities['flowSupplier']->getId()->toString(),
                'mapping' => self::$entities['manufacturerMapping1']->getId()->toString(),
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
            'umberfirm__supplier__delete_supplier_manufacturer-mapping',
            [
                'supplier' => self::$entities['flowSupplier']->getId()->toString(),
                'mapping' => self::$entities['manufacturerMapping3']->getId()->toString(),
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
            'umberfirm__supplier__get_supplier_manufacturer-mappings',
            [
                'supplier' => self::$entities['flowSupplier']->getId()->toString(),
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
            'umberfirm__supplier__delete_supplier_manufacturer-mapping',
            [
                'supplier' => $uuid,
                'mapping' => self::$entities['manufacturerMapping2']->getId()->toString(),
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
            'umberfirm__supplier__delete_supplier_manufacturer-mapping',
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
