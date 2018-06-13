<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerGroup;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class CustomerGroupControllerTest
 *
 * @package UmberFirm\Bundle\ProductBundle\Tests\Functional\Controller
 */
class CustomerGroupControllerTest extends BaseTestCase
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

        $hmGroup = new ShopGroup();
        $hmGroup->setName('HM Group');
        static::getObjectManager()->persist($hmGroup);

        $hmShop = new Shop();
        $hmShop
            ->setShopGroup($hmGroup)
            ->setName('Helen Marlen');
        $hmShop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($hmShop);

        $customerGroup = new CustomerGroup();
        $customerGroup->setName('Visitors', 'en');
        $customerGroup->mergeNewTranslations();
        static::getObjectManager()->persist($customerGroup);

        $customer = new Customer();
        $customer
            ->setEmail('test@gmail.com')
            ->setPhone('+380951234567')
            ->setCustomerGroup($customerGroup)
            ->setShop($hmShop);
        static::getObjectManager()->persist($customer);

        static::getObjectManager()->flush();
        self::$entities['customer'] = $customer;
        self::$entities['customerGroup'] = $customerGroup;
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->payload = [
            'translations' => [
                'en' => [
                    'name' => 'Registered'
                ],
                'ru' => [
                    'name' => 'Зарегистрированные'
                ],
                'ua' => [
                    'name' => 'Зареєстровані'
                ],
                'es' => [
                    'name' => 'Registrados'
                ]
            ],
        ];

        parent::setUp();
        $this->loginEmployee();
    }

    public function testGetCustomerGroupList()
    {
        $uri = $this->router->generate('umberfirm__customer__get_customer-groups');

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

    public function testGetCustomerGroup()
    {
        $uri = $this->router->generate(
            'umberfirm__customer__get_customer-group',
            [
                'customerGroup' => static::$entities['customerGroup']->getId()->toString(),
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
    public function testGetCustomerGroupNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__customer__get_customer-group',
            [
                'customerGroup' => $uuid,
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

    public function testGetCustomerGroupTranslations()
    {
        $uri = $this->router->generate(
            'umberfirm__customer__get_customer-group_translations',
            [
                'customerGroup' => static::$entities['customerGroup']->getId()->toString(),
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
    public function testGetCustomerGroupTranslationsNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__customer__get_customer-group_translations',
            [
                'customerGroup' => $uuid,
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

    public function testPostCustomerGroup()
    {
        $uri = $this->router->generate('umberfirm__customer__post_customer-group');

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

    public function testPostCustomerGroupBadRequest()
    {
        $uri = $this->router->generate('umberfirm__customer__post_customer-group');

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPutCustomerGroup()
    {
        $uri = $this->router->generate(
            'umberfirm__customer__put_customer-group',
            [
                'customerGroup' => static::$entities['customerGroup']->getId()->toString(),
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
        $this->assertEquals('Registrados', $putResponse->name);
    }

    public function testPutCustomerGroupBadRequest()
    {
        $uri = $this->router->generate(
            'umberfirm__customer__put_customer-group',
            [
                'customerGroup' => static::$entities['customerGroup']->getId()->toString(),
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
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPutCustomerGroupNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__customer__put_customer-group',
            [
                'customerGroup' => $uuid,
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

    public function testDeleteCustomerGroup()
    {
        $uri = $this->router->generate(
            'umberfirm__customer__delete_customer-group',
            [
                'customerGroup' => static::$entities['customerGroup']->getId()->toString(),
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
            'umberfirm__customer__get_customer-groups',
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
    public function testDeleteCustomerGroupNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__customer__delete_customer-group',
            [
                'customerGroup' => $uuid,
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
