<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroupEnum;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class AttributeGroupControllerTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller
 */
class AttributeGroupControllerTest extends BaseTestCase
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

        $select = new AttributeGroupEnum();
        $select->setName('select');
        static::getObjectManager()->persist($select);

        $size = new AttributeGroup();
        $size->setIsColorGroup(false)
            ->setPosition(1)
            ->setCode('size')
            ->setAttributeGroupEnum($select);
        $size->translate('ru')
            ->setName('Розмiр')
            ->setPublicName('Розмiр');
        $size->translate('en')
            ->setName('Size')
            ->setPublicName('Size');
        $size->translate('es')
            ->setName('Tamato')
            ->setPublicName('Tamaño');
        $size->mergeNewTranslations();
        static::getObjectManager()->persist($size);

        static::getObjectManager()->flush();
        self::$entities['attribute_group__size'] = $size;
        self::$entities['attribute_group_enum__select'] = $select;
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->payload = [
            'translations' => [
                'ru' => [
                    'name' => 'Розмiр',
                    'publicName' => 'Розмiр',
                ],
                'es' => [
                    'name' => 'Tamaño',
                    'publicName' => 'Tamaño',
                ],
                'en' => [
                    'name' => 'Size',
                    'publicName' => 'Size',
                ],
            ],
            'attributeGroupEnum' => self::$entities['attribute_group_enum__select']->getId()->toString(),
            'position' => 1,
            'code' => 'size'
        ];

        parent::setUp();
        $this->loginEmployee();
    }

    public function testGetAttributeGroupList()
    {
        $uri = $this->router->generate('umberfirm__product__get_attribute-groups');

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

    public function testGetAttributeGroup()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_attribute-group',
            [
                'attributeGroup' => static::$entities['attribute_group__size']->getId()->toString(),
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
    public function testGetAttributeGroupNotFoundWithBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_attribute-group',
            [
                'attributeGroup' => $uuid,
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

    public function testGetAttributes()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_attribute-group_attributes',
            [
                'attributeGroup' => static::$entities['attribute_group__size']->getId()->toString(),
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
    public function testGetAttributesNotFoundWithBadUuidFormat($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_attribute-group_attributes',
            [
                'attributeGroup' => $uuid,
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

    public function testGetAttributeGroupTranslations()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_attribute-group_translations',
            [
                'attributeGroup' => static::$entities['attribute_group__size']->getId()->toString(),
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
    public function testGetAttributeGroupTranslationsNotFoundBadUuid($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_attribute-group_translations',
            [
                'attributeGroup' => $uuid,
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

    public function testPostAttributeGroup()
    {
        $uri = $this->router->generate('umberfirm__product__post_attribute-group');

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

    public function testPostAttributeGroupBadRequest()
    {
        unset($this->payload['attributeGroupEnum']);

        $uri = $this->router->generate('umberfirm__product__post_attribute-group');

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

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPutAttributeGroup()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_attribute-group',
            [
                'attributeGroup' => static::$entities['attribute_group__size']->getId()->toString(),
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
        $this->assertEquals('Tamaño', $putResponse->name);
    }

    public function testPutAttributeGroupBadRequest()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_attribute-group',
            [
                'attributeGroup' => static::$entities['attribute_group__size']->getId()->toString(),
            ]
        );

        $this->payload['attributeGroupEnum'] = Uuid::NIL;
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

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPutAttributeGroupNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_attribute-group',
            [
                'attributeGroup' => $uuid,
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

    public function testDeleteAttributeGroup()
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_attribute-group',
            [
                'attributeGroup' => static::$entities['attribute_group__size']->getId()->toString(),
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
            'umberfirm__product__get_attribute-groups',
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
    public function testDeleteAttributeGroupNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_attribute-group',
            [
                'attributeGroup' => $uuid,
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
