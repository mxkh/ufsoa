<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Tests\Functional\Controller;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CommonBundle\Entity\Gender;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerGroup;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerProfile;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;

/**
 * Class CustomerProfileControllerTest
 *
 * @package UmberFirm\Bundle\CustomerBundle\Tests\Functional\Controller
 */
class CustomerProfileControllerTest extends BaseTestCase
{
    /**
     * @var Customer[]|Gender[]|CustomerProfile[]
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
            'firstname' => self::$entities['johnDoe']->getFirstname(),
            'lastname' => self::$entities['johnDoe']->getLastname(),
            'birthday' => '1969-01-01',
            'gender' => self::$entities['gender']->getId()->toString(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $customerGroup = new CustomerGroup;
        $customerGroup->setName('Members', 'en');
        $customerGroup->setName('Пользователи', 'ru');
        $customerGroup->mergeNewTranslations();
        static::getObjectManager()->persist($customerGroup);

        $gender = new Gender;
        $gender->setName('women', 'en');
        static::getObjectManager()->persist($gender);

        $hmGroup = new ShopGroup();
        $hmGroup->setName('HM Group');
        static::getObjectManager()->persist($hmGroup);

        $hmShop = new Shop();
        $hmShop->setShopGroup($hmGroup);
        $hmShop->setName('Helen Marlen');
        $hmShop->setApiKey('00000000000000000000000000000000');
        static::getObjectManager()->persist($hmShop);

        $customer = new Customer();
        $customer
            ->setEmail('john@doe.com')
            ->setPhone('+380951234677')
            ->setCustomerGroup($customerGroup)
            ->setShop($hmShop);
        static::getObjectManager()->persist($customer);

        $customer2 = new Customer();
        $customer2
            ->setEmail('john@doe.com')
            ->setPhone('+380951234677')
            ->setCustomerGroup($customerGroup)
            ->setShop($hmShop);
        static::getObjectManager()->persist($customer2);

        $johnDoe = new CustomerProfile();
        $johnDoe->setCustomer($customer);
        $johnDoe->setFirstname('John');
        $johnDoe->setLastname('Doe');
        $johnDoe->setBirthday(new \DateTime());
        $johnDoe->setGender($gender);
        static::getObjectManager()->persist($johnDoe);

        static::getObjectManager()->flush();

        self::$entities = [
            'customer' => $customer,
            'customer2' => $customer2,
            'johnDoe' => $johnDoe,
            'gender' => $gender,
        ];
    }

    public function testGetCustomerProfile()
    {
        $uri = $this->router->generate(
            'umberfirm__customer__get_customer_profile',
            [
                'customer' => self::$entities['customer']->getId()->toString(),
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
    public function testGetCustomerProfileNotFoundCustomer($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__customer__get_customer_profile',
            [
                'customer' => $uuid,
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

    public function testGetCustomerProfileNotFound()
    {
        $uri = $this->router->generate(
            'umberfirm__customer__get_customer_profile',
            [
                'customer' => self::$entities['customer2']->getId()->toString(),
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

    public function testPutCustomerProfile()
    {
        $uri = $this->router->generate(
            'umberfirm__customer__put_customer_profile',
            [
                'customer' => self::$entities['customer']->getId()->toString(),
            ]
        );

        $this->payload['firstname'] = 'John Doe';

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
        $response = $this->client->getResponse();
        $body = json_decode($response->getContent());
        $this->assertJsonResponse($response, Response::HTTP_OK);
        $this->assertEquals('John Doe', $body->firstname);
    }

    public function testPutCustomerProfileValidation()
    {
        $uri = $this->router->generate(
            'umberfirm__customer__put_customer_profile',
            [
                'customer' => self::$entities['customer']->getId()->toString(),
            ]
        );

        $this->payload['firstname'] = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.';

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
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPutCustomerProfileNotFoundCustomer($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__customer__put_customer_profile',
            [
                'customer' => $uuid,
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
}
