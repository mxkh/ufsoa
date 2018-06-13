<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ProductBundle\Entity\Attribute;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroupEnum;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class AttributeControllerTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller
 */
class AttributeControllerTest extends BaseTestCase
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
            ->setAttributeGroupEnum($select);
        $size->translate('ru')
            ->setName('Розмiр')
            ->setPublicName('Розмiр');
        $size->translate('en')
            ->setName('Size')
            ->setPublicName('Size');
        $size->translate('es')
            ->setName('Tamaño')
            ->setPublicName('Tamaño');
        $size->mergeNewTranslations();
        static::getObjectManager()->persist($size);

        $attribute = new Attribute();
        $attribute->setPosition(1)
            ->setAttributeGroup($size);
        $attribute->translate('ru')
            ->setName('Маленький');
        $attribute->translate('en')
            ->setName('Small');
        $attribute->translate('es')
            ->setName('Piccolo');
        $attribute->mergeNewTranslations();

        static::getObjectManager()->flush();
        self::$entities['attribute_group_enum__select'] = $select;
        self::$entities['attribute_group__size'] = $size;
        self::$entities['attribute'] = $attribute;
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->payload = [
            'translations' => [
                'ru' => [
                    'name' => 'Маленький',
                ],
                'es' => [
                    'name' => 'Pequeña',
                ],
                'en' => [
                    'name' => 'Small',
                ],
            ],
            'attributeGroup' => self::$entities['attribute_group__size']->getId()->toString(),
            'position' => 1,
        ];

        parent::setUp();
        $this->loginEmployee();
    }

    public function testGetAttributeList()
    {
        $uri = $this->router->generate('umberfirm__product__get_attributes');

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

    public function testGetAttribute()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_attribute',
            [
                'attribute' => static::$entities['attribute']->getId()->toString(),
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
    public function testGetAttributeNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_attribute',
            [
                'attribute' => $uuid,
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

    public function testGetTranslations()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_attribute_translations',
            [
                'attribute' => static::$entities['attribute']->getId()->toString(),
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
            'umberfirm__product__get_attribute_translations',
            [
                'attribute' => $uuid,
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

    public function testPostAttribute()
    {
        $uri = $this->router->generate('umberfirm__product__post_attribute');

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

    public function testPostAttributeBadRequest()
    {
        unset($this->payload['attributeGroup']);

        $uri = $this->router->generate('umberfirm__product__post_attribute');

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

    public function testPutAttribute()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_attribute',
            [
                'attribute' => static::$entities['attribute']->getId()->toString(),
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
        $this->assertEquals('Pequeña', $putResponse->name);
    }

    public function testPutAttributeBadRequest()
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_attribute',
            [
                'attribute' => static::$entities['attribute']->getId()->toString(),
            ]
        );
        $this->payload['attributeGroup'] = Uuid::NIL;
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
    public function testPutAttributeNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__put_attribute',
            [
                'attribute' => $uuid,
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

    public function testDeleteAttribute()
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_attribute',
            [
                'attribute' => static::$entities['attribute']->getId()->toString(),
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
            'umberfirm__product__get_attributes',
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
    public function testDeleteAttributeNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__product__delete_attribute',
            [
                'attribute' => $uuid,
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
