<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Customer;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CommonBundle\Entity\Gender;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerGroup;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerProfile;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class CustomerProfileControllerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Customer
 */
class CustomerProfileControllerTest extends BaseTestCase
{
    /**
     * @var array|UuidEntityInterface[]|CustomerProfile[]|Shop[]
     */
    private static $entities = [];

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $manager = static::getObjectManager();

        $hmGroup = new ShopGroup();
        $hmGroup->setName('HM Group');
        $manager->persist($hmGroup);

        $shop = new Shop();
        $shop->setShopGroup($hmGroup)
            ->setName('Helen Marlen')
            ->setApiKey('00000000000000000000000000000000');
        $manager->persist($shop);

        $shop1 = new Shop();
        $shop1->setShopGroup($hmGroup)
            ->setName('Helen Marlen1')
            ->setApiKey('00000000000000000000000000000001');
        $manager->persist($shop1);

        $customerGroup = new CustomerGroup();
        $customerGroup->setName('Visitors', 'en');
        $customerGroup->mergeNewTranslations();
        $manager->persist($customerGroup);

        $gender = new Gender();
        $gender->setName('women', 'en');
        $gender->mergeNewTranslations();
        $manager->persist($gender);

        $customerProfile = new CustomerProfile();
        $customerProfile->setFirstname('John')
            ->setLastname('Doe')
            ->setBirthday(new \DateTime())
            ->setGender($gender);

        $customer = new Customer();
        $customer
            ->setEmail('test@gmail.com')
            ->setPhone('+380951234567')
            ->setProfile($customerProfile)
            ->setShop($shop)
            ->setCustomerGroup($customerGroup);
        $manager->persist($customer);

        $customerProfile1 = new CustomerProfile();
        $customerProfile1->setFirstname('John1')
            ->setLastname('Doe1')
            ->setBirthday(new \DateTime());

        $customer1 = new Customer();
        $customer1
            ->setEmail('test1@gmail.com')
            ->setPhone('+380501234567')
            ->setProfile($customerProfile1)
            ->setShop($shop1)
            ->setCustomerGroup($customerGroup);
        $manager->persist($customer1);

        $manager->flush();

        self::$entities = [
            'shop' => $shop,
            'customer' => $customer,
            'gender' => $gender,
            'customer1' => $customer1,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->payload = [
            'firstname' => self::$entities['customer']->getProfile()->getFirstname(),
            'lastname' => self::$entities['customer']->getProfile()->getLastname(),
            'birthday' => '1969-01-01',
            'gender' => self::$entities['gender']->getId()->toString(),
        ];
    }

    public function testGetAction()
    {
        $uri = $this->router->generate('umberfirm__public__get_profile');

        $token = ['shop' => static::$entities['shop']->getApiKey(), 'customer' => self::$entities['customer']->getToken()];

        $this->client->request(
            'GET',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);
    }

    public function testGetActionNotFoundFromAnotherShop()
    {
        $this->markTestSkipped('TODO: check customer to shop relation authorization');
        $uri = $this->router->generate('umberfirm__public__get_profile');

        $token = ['shop' => static::$entities['shop']->getApiKey(),  'customer' => self::$entities['customer1']->getToken()];

        $this->client->request(
            'GET',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    public function testPutAction()
    {
        $uri = $this->router->generate('umberfirm__public__put_profile');

        $this->payload['firstname'] = 'Jonny';
        $token = ['shop' => static::$entities['shop']->getApiKey(), 'customer' => self::$entities['customer']->getToken()];

        $this->client->request(
            'PUT',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ],
            json_encode($this->payload)
        );
        $response = $this->client->getResponse();
        $body = json_decode($response->getContent());
        $this->assertJsonResponse($response, Response::HTTP_OK);
        $this->assertEquals('Jonny', $body->firstname);
    }
}
