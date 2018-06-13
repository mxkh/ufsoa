<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;
use UmberFirm\Bundle\CommonBundle\Entity\City;
use UmberFirm\Bundle\CommonBundle\Entity\Gender;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerAddress;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerGroup;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class CustomerAddressControllerTest
 *
 * @package UmberFirm\Bundle\CustomerBundle\Tests\Functional\Controller
 */
class CustomerAddressControllerTest extends BaseTestCase
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

        $customerGroup = new CustomerGroup();
        $customerGroup->setName('Visitors', 'en');
        $customerGroup->setName('Посетители', 'ru');
        $customerGroup->mergeNewTranslations();
        static::getObjectManager()->persist($customerGroup);

        $gender = new Gender();
        $gender->setName('women', 'en');
        static::getObjectManager()->persist($gender);

        $hmGroup = new ShopGroup();
        $hmGroup->setName('HM Group');
        static::getObjectManager()->persist($hmGroup);

        $hmShop = new Shop();
        $hmShop
            ->setShopGroup($hmGroup)
            ->setName('Helen Marlen');
        $hmShop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($hmShop);

        $customer = new Customer();
        $customer
            ->setEmail('test@gmail.com')
            ->setPhone('+380951234677')
            ->setCustomerGroup($customerGroup)
            ->setShop($hmShop);
        static::getObjectManager()->persist($customer);

        $customer2 = new Customer();
        $customer2
            ->setEmail('test2@gmail.com')
            ->setPhone('+3809533311122')
            ->setCustomerGroup($customerGroup)
            ->setShop($hmShop);
        static::getObjectManager()->persist($customer2);

        $kyiv = new City();
        $kyiv->setName('Kyiv');
        $kyiv->setRef('8d5a980d-391c-11dd-90d9-001a92567626');
        static::getObjectManager()->persist($kyiv);

        $address = new CustomerAddress();
        $address->setFirstname('Joe');
        $address->setLastname('Doe');
        $address->setCustomer($customer);
        $address->setShop($hmShop);
        $address->setCity($kyiv);
        $address->setCountry('Ukraine');
        $address->setZip('01234');
        static::getObjectManager()->persist($address);

        static::getObjectManager()->flush();

        self::$entities = [
            'customerAddress' => $address,
            'customer' => $customer,
            'customer2' => $customer2,
            'kyiv' => $kyiv,
            'shop' => $hmShop
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->payload = [
            'firstname' => 'Joe',
            'lastname' => 'Doe',
            'zip' => '123',
            'country' => array_rand(Intl::getRegionBundle()->getCountryNames()),
            'city' => static::$entities['kyiv']->getId()->toString(),
            'shop' => static::$entities['shop']->getId()->toString(),
        ];

        parent::setUp();
        $this->loginEmployee();
    }

    /**
     * Try to get list of addresses
     */
    public function testGetListAction()
    {
        $uri = $this->router->generate(
            'umberfirm__customer__get_customer_addresses',
            [
                'customer' => static::$entities['customer']->getId()->toString()
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
    public function testGetListBadUuidAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__customer__get_customer_addresses',
            [
                'customer' => $uuid
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
     * Try to get specified address
     */
    public function testGetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__customer__get_customer_address',
            [
                'customer' => static::$entities['customer']->getId()->toString(),
                'customerAddress' => static::$entities['customerAddress']->getId()->toString(),
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
     * Try to get not found address
     */
    public function testGetNotFoundCustomerAction()
    {
        $uri = $this->router->generate(
            'umberfirm__customer__get_customer_address',
            [
                'customer' => static::$entities['customer2']->getId()->toString(),
                'customerAddress' => static::$entities['customerAddress']->getId()->toString(),
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
    public function testNotFoundCustomerAddressAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__customer__get_customer_address',
            [
                'customer' => static::$entities['customer']->getId()->toString(),
                'customerAddress' => $uuid,
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
    public function testBadUuidCustomerAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__customer__get_customer_address',
            [
                'customer' => $uuid,
                'customerAddress' => static::$entities['customerAddress']->getId()->toString(),
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
     * Try to create address
     */
    public function testPostAction()
    {
        $uri = $this->router->generate(
            'umberfirm__customer__post_customer_address',
            [
                'customer' => static::$entities['customer']->getId()->toString()
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

    /**
     * Try to create address with invalid params
     */
    public function testInvalidParamsPostAction()
    {
        $uri = $this->router->generate(
            'umberfirm__customer__post_customer_address',
            [
                'customer' => static::$entities['customer']->getId()->toString()
            ]
        );

        //with empty params
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

    /**
     * Try to update address
     */
    public function testPutAction()
    {
        $uri = $this->router->generate(
            'umberfirm__customer__put_customer_address',
            [
                'customer' => static::$entities['customer']->getId()->toString(),
                'customerAddress' => self::$entities['customerAddress']->getId()->toString(),
            ]
        );

        unset($this->payload['customer']);
        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    /**
     * Try to update customerAddress
     */
    public function testNotFoundCustomerPutAction()
    {
        $uri = $this->router->generate(
            'umberfirm__customer__put_customer_address',
            [
                'customer' => static::$entities['customer2']->getId()->toString(),
                'customerAddress' => self::$entities['customerAddress']->getId()->toString(),
            ]
        );

        unset($this->payload['customer']);
        $this->client->request(
            'PUT',
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

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testNotFoundPutAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__customer__put_customer_address',
            [
                'customer' => static::$entities['customer']->getId()->toString(),
                'customerAddress' => $uuid,
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
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testInvalidParamsPutAction()
    {
        $uri = $this->router->generate(
            'umberfirm__customer__put_customer_address',
            [
                'customer' => static::$entities['customer']->getId()->toString(),
                'customerAddress' => self::$entities['customerAddress']->getId()->toString(),
            ]
        );

        //with empty params
        $this->client->request(
            'PUT',
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

    public function testCustomerNotFoundDeleteAction()
    {
        $uri = $this->router->generate(
            'umberfirm__customer__delete_customer_address',
            [
                'customer' => static::$entities['customer2']->getId()->toString(),
                'customerAddress' => static::$entities['customerAddress']->getId()->toString(),
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

    public function testDeleteAction()
    {
        $uri = $this->router->generate(
            'umberfirm__customer__delete_customer_address',
            [
                'customer' => static::$entities['customer']->getId()->toString(),
                'customerAddress' => static::$entities['customerAddress']->getId()->toString(),
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
            'umberfirm__customer__get_customer_addresses',
            [
                'customer' => static::$entities['customer']->getId()->toString(),
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
    public function testOnBadUuidFormatDeleteAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__customer__delete_customer_address',
            [
                'customer' => static::$entities['customer']->getId()->toString(),
                'customerAddress' => $uuid,
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
    public function testOnBadCustomerAddressFormatDeleteAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__customer__delete_customer_address',
            [
                'customer' => $uuid,
                'customerAddress' => static::$entities['customerAddress']->getId()->toString(),
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
