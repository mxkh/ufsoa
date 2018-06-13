<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SupplierBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ProductBundle\Entity\Attribute;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroupEnum;
use UmberFirm\Bundle\SupplierBundle\Entity\Supplier;
use UmberFirm\Bundle\SupplierBundle\Entity\SupplierAttributeMapping;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class AttributeMappingControllerTest
 *
 * @package UmberFirm\Bundle\SupplierBundle\Tests\Functional\Controller
 */
class AttributeMappingControllerTest extends BaseTestCase
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
                'attribute' => self::$entities['size2']->getId()->toString(),
                'supplierAttributeKey' => 'стєлька',
                'supplierAttributeValue' => '27.5',
            ],
            'mappedAttributeUnique' => [
                'attribute' => self::$entities['mappedAttribute']->getAttribute()->getId()->toString(),
                'supplierAttributeKey' => self::$entities['mappedAttribute']->getSupplierAttributeKey(),
                'supplierAttributeValue' => self::$entities['mappedAttribute']->getSupplierAttributeValue(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $select = new AttributeGroupEnum();
        $select->setName('select');

        $shoesSize = new AttributeGroup();
        $shoesSize->setIsColorGroup(false)
            ->setAttributeGroupEnum($select);
        $shoesSize->translate('ua')
            ->setName('Розмiр взуття')
            ->setPublicName('Розмiр взуття');
        $shoesSize->translate('en')
            ->setName('Shoes Size')
            ->setPublicName('Shoes Size');
        $shoesSize->mergeNewTranslations();

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

        $size1 = new Attribute();
        $size1->setAttributeGroup($shoesSize);
        $size1->translate('ua')->setName('43');
        $size1->translate('en')->setName('8');
        $size1->translate('ru')->setName('43Сепар');
        $size1->mergeNewTranslations();

        $size2 = new Attribute();
        $size2->setAttributeGroup($shoesSize);
        $size2->translate('ua')->setName('42');
        $size2->translate('en')->setName('7.5');
        $size2->translate('ru')->setName('42Сепар');
        $size2->mergeNewTranslations();

        $supplierAttributeKey = 'Підошва';
        $supplierAttributeValue = '00043';
        $mappedAttribute = new SupplierAttributeMapping();
        $mappedAttribute->setSupplier($iconSupplier);
        $mappedAttribute->setSupplierAttributeKey($supplierAttributeKey);
        $mappedAttribute->setSupplierAttributeValue($supplierAttributeValue);
        $mappedAttribute->setAttribute($size1);

        static::getObjectManager()->persist($flowSupplier);
        static::getObjectManager()->persist($iconSupplier);
        static::getObjectManager()->persist($select);
        static::getObjectManager()->persist($shoesSize);
        static::getObjectManager()->persist($size1);
        static::getObjectManager()->persist($size2);
        static::getObjectManager()->persist($mappedAttribute);

        static::getObjectManager()->flush();

        static::$entities = [
            'mappedAttribute' => $mappedAttribute,
            'size2' => $size2,
            'iconSupplier' => $iconSupplier,
            'flowSupplier' => $flowSupplier,
        ];
    }

    public function testGetList()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__get_supplier_attribute-mappings',
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
            'umberfirm__supplier__get_supplier_attribute-mappings',
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
            'umberfirm__supplier__get_supplier_attribute-mapping',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
                'mapping' => self::$entities['mappedAttribute']->getId()->toString(),
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
            'umberfirm__supplier__get_supplier_attribute-mapping',
            [
                'supplier' => self::$entities['flowSupplier']->getId()->toString(),
                'mapping' => self::$entities['mappedAttribute']->getId()->toString(),
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
            'umberfirm__supplier__get_supplier_attribute-mapping',
            [
                'supplier' => $uuid,
                'mapping' => self::$entities['mappedAttribute']->getId()->toString(),
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
            'umberfirm__supplier__get_supplier_attribute-mapping',
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
            'umberfirm__supplier__post_supplier_attribute-mapping',
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
            'umberfirm__supplier__post_supplier_attribute-mapping',
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
            json_encode($this->payload['mappedAttributeUnique'])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testCreateOnEmptyFields()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__post_supplier_attribute-mapping',
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
            'umberfirm__supplier__post_supplier_attribute-mapping',
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
            'umberfirm__supplier__put_supplier_attribute-mapping',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
                'mapping' => self::$entities['mappedAttribute']->getId()->toString(),
            ]
        );

        $this->payload['mappedAttributeUnique']['supplierAttributeValue'] = '25';

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($this->payload['mappedAttributeUnique'])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
        $putResponse = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals(
            $this->payload['mappedAttributeUnique']['supplierAttributeValue'],
            $putResponse->supplier_attribute_value
        );
    }

    public function testUpdateOneInconsistentArguments()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__put_supplier_attribute-mapping',
            [
                'supplier' => self::$entities['flowSupplier']->getId()->toString(),
                'mapping' => self::$entities['mappedAttribute']->getId()->toString(),
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
            'umberfirm__supplier__put_supplier_attribute-mapping',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
                'mapping' => self::$entities['mappedAttribute']->getId()->toString(),
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
            'umberfirm__supplier__put_supplier_attribute-mapping',
            [
                'supplier' => $uuid,
                'mapping' => self::$entities['mappedAttribute']->getId()->toString(),
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
            'umberfirm__supplier__put_supplier_attribute-mapping',
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

    public function testDeleteOneInconsistentArguments()
    {
        $uri = $this->router->generate(
            'umberfirm__supplier__delete_supplier_attribute-mapping',
            [
                'supplier' => self::$entities['flowSupplier']->getId()->toString(),
                'mapping' => self::$entities['mappedAttribute']->getId()->toString(),
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
            'umberfirm__supplier__delete_supplier_attribute-mapping',
            [
                'supplier' => self::$entities['iconSupplier']->getId()->toString(),
                'mapping' => self::$entities['mappedAttribute']->getId()->toString(),
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
            'umberfirm__supplier__get_supplier_attribute-mappings',
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
            'umberfirm__supplier__delete_supplier_attribute-mapping',
            [
                'supplier' => $uuid,
                'mapping' => self::$entities['mappedAttribute']->getId()->toString(),
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
            'umberfirm__supplier__delete_supplier_attribute-mapping',
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
