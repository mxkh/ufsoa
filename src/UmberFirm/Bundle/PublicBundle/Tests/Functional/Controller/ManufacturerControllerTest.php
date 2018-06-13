<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class ManufacturerControllerTest
 *
 * @package UmberFirmPublicBundle\Tests\Controller
 */
class ManufacturerControllerTest extends BaseTestCase
{
    /**
     * @var object
     */
    public static $postResponse;

    /**
     * @var array|Shop[]|Manufacturer[]
     */
    public static $entities;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $shop = new Shop();
        $shop->setName('Їжа');
        $shop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($shop);

        $manufacturer = new Manufacturer();
        $manufacturer->setName('test');
        $manufacturer->setWebsite('http://test.com');
        static::getObjectManager()->persist($manufacturer);

        static::getObjectManager()->flush();

        static::$entities['shop'] = $shop;
        static::$entities['manufacturer'] = $manufacturer;
    }

    public function testGetManufacturerList()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_manufacturers'
        );

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'GET',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    public function testGetManufacturer()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_manufacturer',
            [
                'manufacturer' => static::$entities['manufacturer']->getId()->toString(),
            ]
        );

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'GET',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetManufacturerNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_manufacturer',
            [
                'manufacturer' => $uuid,
            ]
        );

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'GET',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testGetProductsByManufacturer()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_manufacturer_products',
            [
                'manufacturer' => static::$entities['manufacturer']->getId()->toString(),
            ]
        );

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'GET',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetProductsByManufacturerNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_manufacturer_products',
            [
                'manufacturer' => $uuid,
            ]
        );

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'GET',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }
}
