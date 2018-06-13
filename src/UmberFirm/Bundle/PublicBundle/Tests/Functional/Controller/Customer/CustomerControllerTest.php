<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Customer;

use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CommonBundle\Entity\Gender;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerGroup;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerProfile;
use UmberFirm\Bundle\CustomerBundle\Entity\SocialNetwork;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\PublicBundle\Event\Customer\Subscriber\CustomerEventSubscriberInterface;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;

/**
 * Class CustomerControllerTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Functional\Controller\Customer
 */
class CustomerControllerTest extends BaseTestCase
{
    /**
     * @var array|Shop[]|Customer[]
     */
    private static $entities = [];

    /**
     * @var CustomerEventSubscriberInterface||\PHPUnit_Framework_MockObject_MockObject
     */
    protected $customerEventSubscriber;

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

        $hmShop = new Shop();
        $hmShop
            ->setShopGroup($hmGroup)
            ->setName('Helen Marlen');
        $hmShop->setApiKey('00000000000000000000000000000000');
        $manager->persist($hmShop);

        $shop1 = new Shop();
        $shop1->setShopGroup($hmGroup)
            ->setName('Helen Marlen1')
            ->setApiKey('00000000000000000000000000000001');
        $manager->persist($shop1);

        $customer2 = new Customer();
        $customer2->setPhone('380503214567');
        $customer2->setShop($hmShop);
        $customer2->setPassword(password_hash('password', PASSWORD_BCRYPT));
        $customer2->setIsConfirmed(true);
        $manager->persist($customer2);

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
            ->setConfirmationCode('123456')
            ->setIsConfirmed(false)
            ->setShop($hmShop)
            ->setPassword(password_hash('password', PASSWORD_BCRYPT))
            ->setCustomerGroup($customerGroup);
        $manager->persist($customer);

        $socialNetwork = new SocialNetwork();
        $socialNetwork->setName('google');
        $manager->persist($socialNetwork);

        $customerProfile1 = new CustomerProfile();
        $customerProfile1->setFirstname('John1')
            ->setLastname('Doe1')
            ->setBirthday(new \DateTime());

        $customer1 = new Customer();
        $customer1
            ->setEmail('test1@gmail.com')
            ->setPhone('+380501234567')
            ->setProfile($customerProfile1)
            ->setIsConfirmed(true)
            ->setShop($hmShop)
            ->setPassword(password_hash('password', PASSWORD_BCRYPT))
            ->setCustomerGroup($customerGroup);
        $manager->persist($customer1);

        $manager->flush();

        self::$entities = [
            'shop' => $hmShop,
            'shop2' => $shop1,
            'customer' => $customer,
            'customer1' => $customer1,
            'customer2' => $customer2,
            'socialNetwork' => $socialNetwork,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->customerEventSubscriber = $this->createMock(CustomerEventSubscriberInterface::class);
        $this->client->getContainer()->set('umberfirm.customer.event.subscriber', $this->customerEventSubscriber);
    }

    /**
     * @dataProvider signupDataProvider
     *
     * @param array $payload
     * @param int $httpCode
     */
    public function testSignupAction(array $payload, int $httpCode)
    {
        $uri = $this->router->generate('umberfirm__public__post_customer_signup');

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ],
            json_encode($payload)
        );

        $this->assertJsonResponse($this->client->getResponse(), $httpCode);
    }

    /**
     * @return array
     */
    public function signupDataProvider(): array
    {
        return [
            [
                [
                    'phone' => '+380669898123',
                    'password' => 'kolokol',
                ],
                Response::HTTP_CREATED,
            ],
            [
                [
                    'phone' => '+380501234567',
                    'password' => 'pa$$w0rd',
                ],
                Response::HTTP_BAD_REQUEST,
            ],
            [
                [
                    'email' => 'test@mail.me',
                    'phone' => '+380501234567',
                    'password' => 'pa$$w0rd',
                ],
                Response::HTTP_BAD_REQUEST,
            ],
            [
                [
                    'email' => 'test@mail',
                    'phone' => '+380501234567',
                    'password' => 'pa$$w0rd',
                ],
                Response::HTTP_BAD_REQUEST,
            ],
        ];
    }

    public function testLoginAction()
    {
        $uri = $this->router->generate('umberfirm__public__post_customer_login');

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ],
            json_encode(
                [
                    'phone' => static::$entities['customer1']->getPhone(),
                    'password' => 'password',
                ]
            )
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_OK,
            $this->client->getResponse()->getContent()
        );
    }

    public function testLoginActionWithNotConfirmed()
    {
        $uri = $this->router->generate('umberfirm__public__post_customer_login');

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ],
            json_encode(
                [
                    'phone' => static::$entities['customer']->getPhone(),
                    'password' => 'password',
                ]
            )
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_BAD_REQUEST,
            $this->client->getResponse()->getContent()
        );
    }

    public function testBadLoginAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__post_customer_login',
            []
        );

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ],
            json_encode(
                [
                    'phone' => static::$entities['customer1']->getPhone(),
                    'password' => 'password1111',
                ]
            )
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_BAD_REQUEST,
            $this->client->getResponse()->getContent()
        );
    }

    public function testSocialLoginAction()
    {
        $uri = $this->router->generate('umberfirm__public__post_customer_signup_social');

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ],
            json_encode(
                [
                    'email' => 'test22222222223@gmail.com',
                    'phone' => '+380505672385',
                    'socialId' => '123',
                    'socialNetwork' => 'google',
                ]
            )
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_OK,
            $this->client->getResponse()->getContent()
        );
    }

    public function testSocialLoginActionOnExistedUser()
    {
        $uri = $this->router->generate('umberfirm__public__post_customer_signup_social');

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ],
            json_encode(
                [
                    'email' => static::$entities['customer1']->getEmail(),
                    'phone' => '+380505672385',
                    'socialId' => '123456789',
                    'socialNetwork' => 'google',
                ]
            )
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_OK,
            $this->client->getResponse()->getContent()
        );
    }

    public function testSocialBadLoginAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__post_customer_signup_social',
            []
        );

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ],
            json_encode(
                [
                    'socialId' => '',
                    'socialNetwork' => '',
                ]
            )
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_BAD_REQUEST,
            $this->client->getResponse()->getContent()
        );
    }

    public function testChangePasswordAction()
    {
        $uri = $this->router->generate('umberfirm__public__post_customer_login');

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ],
            json_encode(
                [
                    'phone' => '380503214567',
                    'password' => 'password',
                ]
            )
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_OK,
            $this->client->getResponse()->getContent()
        );
        $token = json_decode($this->client->getResponse()->getContent(), true);

        $uri = $this->router->generate(
            'umberfirm__public__post_customer_change_password',
            []
        );

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$token['token'],
            ],
            json_encode(
                [
                    'oldPassword' => 'password',
                    'password' => [
                        'first' => 'password2',
                        'second' => 'password2',
                    ],
                ]
            )
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_OK,
            $this->client->getResponse()->getContent()
        );
    }

    public function testChangePasswordActionWithWrong()
    {
        $uri = $this->router->generate('umberfirm__public__post_customer_login');

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ],
            json_encode(
                [
                    'phone' => '380503214567',
                    'password' => 'password2',
                ]
            )
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_OK,
            $this->client->getResponse()->getContent()
        );
        $token = json_decode($this->client->getResponse()->getContent(), true);

        $uri = $this->router->generate(
            'umberfirm__public__post_customer_change_password',
            []
        );

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$token['token'],
            ],
            json_encode(
                [
                    'oldPassword' => 'password2',
                    'password' => [
                        'first' => '123',
                        'second' => '123',
                    ],
                ]
            )
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_BAD_REQUEST,
            $this->client->getResponse()->getContent()
        );
    }

    public function testPostConfirmAction()
    {
        $uri = $this->router->generate('umberfirm__public__post_customer_confirm');

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ],
            json_encode(
                [
                    'confirmationCode' => static::$entities['customer']->getConfirmationCode(),
                    'customerId' => static::$entities['customer']->getId()->toString(),
                ]
            )
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_OK,
            $this->client->getResponse()->getContent()
        );
    }

    public function testPostConfirmActionWithConfirmedCustomer()
    {
        $uri = $this->router->generate('umberfirm__public__post_customer_confirm');

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ],
            json_encode(
                [
                    'confirmationCode' => static::$entities['customer']->getConfirmationCode(),
                    'customerId' => static::$entities['customer']->getId()->toString(),
                ]
            )
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_BAD_REQUEST,
            $this->client->getResponse()->getContent()
        );
    }

    public function testResetWrongNewPasswordAction()
    {
        $uri = $this->router->generate('umberfirm__public__post_customer_login');

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ],
            json_encode(
                [
                    'phone' => '380503214567',
                    'password' => 'password2',
                ]
            )
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_OK,
            $this->client->getResponse()->getContent()
        );
        $token = json_decode($this->client->getResponse()->getContent(), true);

        $uri = $this->router->generate(
            'umberfirm__public__post_customer_change_password',
            []
        );

        $this->client->request(
            'POST',
            $uri,
            [
                'oldPassword' => 'password2',
                'password' => [
                    'first' => 'password22',
                    'second' => 'password2',
                ],
            ],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$token['token'],
            ]
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_BAD_REQUEST,
            $this->client->getResponse()->getContent()
        );
    }

    public function testResetWrongOldPasswordAction()
    {
        $uri = $this->router->generate('umberfirm__public__post_customer_login');

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ],
            json_encode(
                [
                    'phone' => '380503214567',
                    'password' => 'password2',
                ]
            )
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_OK,
            $this->client->getResponse()->getContent()
        );
        $token = json_decode($this->client->getResponse()->getContent(), true);

        $uri = $this->router->generate(
            'umberfirm__public__post_customer_change_password',
            []
        );

        $this->client->request(
            'POST',
            $uri,
            [
                'oldPassword' => 'password',
                'password' => [
                    'first' => 'password22',
                    'second' => 'password22',
                ],
            ],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$token['token'],
            ]
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_BAD_REQUEST,
            $this->client->getResponse()->getContent()
        );
    }

    public function testResetPasswordCustomerWithoutPassAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__post_customer_signup_social',
            []
        );

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ],
            json_encode(
                [
                    'email' => 'test222223@gmail.com',
                    'phone' => '+380501112233',
                    'socialId' => '321',
                    'socialNetwork' => 'google',
                ]
            )
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_OK,
            $this->client->getResponse()->getContent()
        );
        $token = json_decode($this->client->getResponse()->getContent(), true);

        $uri = $this->router->generate(
            'umberfirm__public__post_customer_change_password',
            []
        );

        $this->client->request(
            'POST',
            $uri,
            [
                'oldPassword' => null,
                'password' => [
                    'first' => 'password2',
                    'second' => 'password2',
                ],
            ],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$token['token'],
            ]
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_BAD_REQUEST,
            $this->client->getResponse()->getContent()
        );
    }

    public function testResetPasswordCustomerWithoutPassWrongNewPassAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__post_customer_signup_social',
            []
        );

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ],
            json_encode(
                [
                    'email' => 'test222223@gmail.com',
                    'phone' => '+380501112233',
                    'socialId' => '321',
                    'socialNetwork' => 'google',
                ]
            )
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_OK,
            $this->client->getResponse()->getContent()
        );
        $token = json_decode($this->client->getResponse()->getContent(), true);

        $uri = $this->router->generate(
            'umberfirm__public__post_customer_change_password',
            []
        );

        $this->client->request(
            'POST',
            $uri,
            [
                'oldPassword' => null,
                'password' => [
                    'first' => 'password2',
                    'second' => 'password21',
                ],
            ],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$token['token'],
            ]
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_BAD_REQUEST,
            $this->client->getResponse()->getContent()
        );
    }

    public function testPostResetPasswordByPhoneAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__post_customer_reset_password',
            []
        );

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [
                'phone' => '380503214567',
            ],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ]
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_OK,
            $this->client->getResponse()->getContent()
        );
        $data = json_decode($this->client->getResponse()->getContent(), true);

        $uri = $this->router->generate(
            'umberfirm__public__post_customer_confirm_reset_password',
            []
        );

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ],
            json_encode(
                [
                    'resetPasswordCode' => $data['resetPasswordCode'],
                    'password' => [
                        'first' => '1111111234',
                        'second' => '1111111234',
                    ],
                ]
            )
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_OK,
            $this->client->getResponse()->getContent()
        );
    }

    public function testPostResetPasswordByEmailAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__post_customer_reset_password',
            []
        );

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [
                'email' => 'test@gmail.com',
            ],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ]
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_OK,
            $this->client->getResponse()->getContent()
        );
        $data = json_decode($this->client->getResponse()->getContent(), true);

        $uri = $this->router->generate(
            'umberfirm__public__post_customer_confirm_reset_password',
            []
        );

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ],
            json_encode(
                [
                    'resetPasswordCode' => $data['resetPasswordCode'],
                    'password' => [
                        'first' => '1111111234',
                        'second' => '1111111234',
                    ],
                ]
            )
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_OK,
            $this->client->getResponse()->getContent()
        );
    }

    public function testPostWrongResetPasswordByEmailAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__post_customer_reset_password',
            []
        );

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [
                'email' => 'test@gmail.com',
            ],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ]
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_OK,
            $this->client->getResponse()->getContent()
        );

        $uri = $this->router->generate(
            'umberfirm__public__post_customer_confirm_reset_password',
            []
        );

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ],
            json_encode(
                [
                    'resetPasswordCode' => uniqid(),
                    'password' => [
                        'first' => '1111111234',
                        'second' => '1111111234',
                    ],
                ]
            )
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_BAD_REQUEST,
            $this->client->getResponse()->getContent()
        );
    }

    public function testPostWrongResetPasswordByPhoneAction()
    {
        $uri = $this->router->generate(
            'umberfirm__public__post_customer_reset_password',
            []
        );

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [
                'phone' => '380503214567',
            ],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ]
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_OK,
            $this->client->getResponse()->getContent()
        );

        $uri = $this->router->generate(
            'umberfirm__public__post_customer_confirm_reset_password',
            []
        );

        $token = ['shop' => static::$entities['shop']->getApiKey()];

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$this->encoder->encode($token),
            ],
            json_encode(
                [
                    'resetPasswordCode' => uniqid(),
                    'password' => [
                        'first' => '1111111234',
                        'second' => '1111111234',
                    ],
                ]
            )
        );

        $this->assertEquals(
            $this->client->getResponse()->getStatusCode(),
            Response::HTTP_BAD_REQUEST,
            $this->client->getResponse()->getContent()
        );
    }
}
