<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroupEnum;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class AttributeGroupEnumControllerTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller
 */
class AttributeGroupEnumControllerTest extends BaseTestCase
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

        $color = new AttributeGroupEnum();
        $color->setName('color');
        static::getObjectManager()->persist($color);

        $select = new AttributeGroupEnum();
        $select->setName('select');
        static::getObjectManager()->persist($select);

        $radio = new AttributeGroupEnum();
        $radio->setName('radio');
        static::getObjectManager()->persist($radio);

        static::getObjectManager()->flush();

        self::$entities = [
            'color' => $color,
            'select' => $select,
            'radio' => $radio,
        ];
    }

    protected function setUp()
    {
        parent::setUp();
        $this->loginEmployee();
    }

    public function testGetAttributeGroupEnumList()
    {
        $uri = $this->router->generate('umberfirm__product__get_attribute-group-enums');

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
     * @param string $name
     *
     * @dataProvider attributeGroupEnumDataProvider
     */
    public function testGetAttributeGroupEnum($name)
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_attribute-group-enum',
            [
                'attributeGroupEnum' => static::$entities[$name]->getId()->toString(),
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

    public function attributeGroupEnumDataProvider()
    {
        return [
            ['color'],
            ['select'],
            ['radio'],
        ];
    }

    public function testGetAttributeGroupEnumNotFound()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_attribute-group-enum',
            [
                'attributeGroupEnum' => Uuid::NIL,
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

    public function testGetAttributeGroupEnumNotFoundWithBadUuidFormat()
    {
        $uri = $this->router->generate(
            'umberfirm__product__get_attribute-group-enum',
            [
                'attributeGroupEnum' => 'bad-uuid-format',
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
}
