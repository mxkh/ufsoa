<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Customer;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;
use UmberFirm\Bundle\CommonBundle\Entity\City;
use UmberFirm\Bundle\CommonBundle\Entity\Gender;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerAddress;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerGroup;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class CustomerAddressControllerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Customer
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

        $manager = static::getObjectManager();

        $customerGroup = new CustomerGroup();
        $customerGroup
            ->setName('Visitors', 'en')
            ->mergeNewTranslations();
        $manager->persist($customerGroup);

        $gender = new Gender();
        $gender->setName('women', 'en');
        $manager->persist($gender);

        $hmGroup = new ShopGroup();
        $hmGroup->setName('HM Group');
        $manager->persist($hmGroup);

        $hmShop = new Shop();
        $hmShop
            ->setShopGroup($hmGroup)
            ->setName('Helen Marlen')
            ->setApiKey('00000000000000000000000000000000');
        $manager->persist($hmShop);

        $kyiv = new City();
        $kyiv->setName('Kyiv');
        $kyiv->setRef('8d5a980d-391c-11dd-90d9-001a92567626');
        static::getObjectManager()->persist($kyiv);

        $customer = new Customer();
        $customer
            ->setEmail('test@email.me')
            ->setPhone('+380501234567')
            ->setCustomerGroup($customerGroup)
            ->setShop($hmShop);
        $manager->persist($customer);

        $address = new CustomerAddress();
        $address->setFirstname('Joe');
        $address->setLastname('Doe');
        $address->setCustomer($customer);
        $address->setShop($hmShop);
        $address->setCity($kyiv);
        $address->setCountry('Ukraine');
        $address->setZip('01234');
        static::getObjectManager()->persist($address);

        $customer2 = new Customer();
        $customer2
            ->setEmail('test2@email.me')
            ->setPhone('+380501234568')
            ->setCustomerGroup($customerGroup)
            ->setShop($hmShop);
        $manager->persist($customer2);

        $address2 = new CustomerAddress();
        $address2->setFirstname('Joe');
        $address2->setLastname('Doe');
        $address2->setCustomer($customer2);
        $address2->setShop($hmShop);
        $address2->setCity($kyiv);
        $address2->setCountry('Ukraine');
        $address2->setZip('01234');
        static::getObjectManager()->persist($address2);

        $manager->flush();

        self::$entities = [
            'shop' => $hmShop,
            'customerAddress' => $address,
            'customerAddress2' => $address2,
            'customer' => $customer,
            'customer2' => $customer2,
            'kyiv' => $kyiv,
        ];
    }

    /**
     * {@inheritdoc]
     */
    protected function setUp()
    {
        parent::setUp();

        $this->payload = [
            'firstname' => 'Petya',
            'lastname' => 'Petya',
            'zip' => '123',
            'country' => array_rand(Intl::getRegionBundle()->getCountryNames()),
            'city' => static::$entities['kyiv']->getId()->toString(),
        ];
    }

    public function testCgetAction()
    {
        $uri = $this->router->generate('umberfirm__public__get_customer-addresses');

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

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

    public function testGetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_customer-address',
            [
                'customerAddress' => static::$entities['customerAddress']->getId()->toString(),
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

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

    public function testGetActionCustomerNotFound()
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_customer-address',
            [
                'customerAddress' => static::$entities['customerAddress']->getId()->toString(),
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer2']->getToken(),
        ];

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

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetActionCustomerAddressBadUuid(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__public__get_customer-address',
            [
                'customerAddress' => $uuid,
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

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

    public function testPostAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__post_customer-address',
            [
                'customer' => static::$entities['customer']->getId()->toString(),
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostActionInvalidParams()
    {
        $uri = $this->router->generate(
            'umberfirm__public__post_customer-address',
            [
                'customer' => static::$entities['customer']->getId()->toString(),
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ],
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPutAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__put_customer-address',
            [
                'customerAddress' => self::$entities['customerAddress']->getId()->toString(),
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    public function testPutActionCustomerNotFound()
    {
        $uri = $this->router->generate(
            'umberfirm__public__put_customer-address',
            [
                'customerAddress' => self::$entities['customerAddress']->getId()->toString(),
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer2']->getToken(),
        ];

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
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
    public function testPutActionCustomerAddressBadUuid(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__public__put_customer-address',
            [
                'customerAddress' => $uuid,
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ],
            json_encode($this->payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testPutActionInvalidParams()
    {
        $uri = $this->router->generate(
            'umberfirm__public__put_customer-address',
            [
                'customerAddress' => self::$entities['customerAddress']->getId()->toString(),
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ],
            json_encode([])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testDeleteAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__delete_customer-address',
            [
                'customerAddress' => static::$entities['customerAddress']->getId()->toString(),
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

        $this->client->request(
            'DELETE',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->encoder->encode($token)),
            ]
        );

        $this->assertEquals(
            Response::HTTP_NO_CONTENT,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );

        $listUri = $this->router->generate(
            'umberfirm__public__get_customer-addresses',
            [],
            Router::ABSOLUTE_URL
        );

        $this->assertTrue(
            $this->client->getResponse()->headers->contains('Location', $listUri),
            $this->client->getResponse()->headers
        );
    }

    public function testDeleteActionCustomerNotFound()
    {
        $uri = $this->router->generate(
            'umberfirm__public__delete_customer-address',
            [
                'customerAddress' => static::$entities['customerAddress']->getId()->toString(),
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer2']->getToken(),
        ];

        $this->client->request(
            'DELETE',
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

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testDeleteActionCustomerAddressBadUuid(string $uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__public__delete_customer-address',
            [
                'customerAddress' => $uuid,
            ]
        );

        $token = [
            'shop' => static::$entities['shop']->getApiKey(),
            'customer' => self::$entities['customer']->getToken(),
        ];

        $this->client->request(
            'DELETE',
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
