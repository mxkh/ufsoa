<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\EmployeeBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\EmployeeBundle\Entity\Employee;
use UmberFirm\Bundle\EmployeeBundle\Entity\EmployeeGroup;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class EmployeeControllerTest
 *
 * @package UmberFirm\Bundle\EmployeeBundle\Tests\Functional\Controller
 */
class EmployeeControllerTest extends BaseTestCase
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
        $employeeGroup1 = new EmployeeGroup();
        /** @var string $locale */
        $locale = $employeeGroup1->getDefaultLocale();
        $employeeGroup1->setName('Developers', $locale);
        $employeeGroup1->mergeNewTranslations();
        static::getObjectManager()->persist($employeeGroup1);

        $employee = new Employee();
        $employee
            ->setName('Vasya')
            ->setEmail('Pupkin')
            ->setBirthday(new \DateTime())
            ->setEmail('test@gmail.com')
            ->setPhone('+380951234677')
            ->setEmployeeGroup($employeeGroup1)
            ->setPassword('1234567890');
        static::getObjectManager()->persist($employee);

        static::getObjectManager()->flush();

        self::$entities = [
            'employee' => $employee,
            'employeeGroup1' => $employeeGroup1
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $pass = md5((string) time());

        $this->payload = [
            'name' => 'product003',
            'email' => 'test@gmail.com',
            'phone' => '+380951234677',
            'birthday' => '2015-10-30',
            'password' => $pass,
            'employeeGroup' => self::$entities['employeeGroup1']->getId()->toString()
        ];

        parent::setUp();
        $this->loginEmployee();
    }

    /**
     * Try to get list of employees
     */
    public function testGetListAction()
    {
        $uri = $this->router->generate('umberfirm__employee__get_employees');

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
     * Try to get specified employee
     */
    public function testGetAction()
    {
        $uri = $this->router->generate(
            'umberfirm__employee__get_employee',
            [
                'employee' => self::$entities['employee']->getId()->toString(),
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
    public function testTryNotFoundGetAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__employee__get_employee',
            [
                'employee' => $uuid,
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
     * Try to create employee
     */
    public function testPostAction()
    {
        $uri = $this->router->generate('umberfirm__employee__post_employee');

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
     * Try to create employee with invalid params
     */
    public function testInvalidParamsPostAction()
    {
        $uri = $this->router->generate('umberfirm__employee__post_employee');

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
     * Try to update employee
     */
    public function testPutAction()
    {
        $uri = $this->router->generate(
            'umberfirm__employee__put_employee',
            [
                'employee' => self::$entities['employee']->getId()->toString(),
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

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_OK);

        $putResponse = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals($this->payload['email'], $putResponse->email);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testNotFoundPutAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__employee__put_employee',
            [
                'employee' => $uuid,
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
            'umberfirm__employee__put_employee',
            [
                'employee' => self::$entities['employee']->getId()->toString(),
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

    public function testDeleteAction()
    {
        $uri = $this->router->generate(
            'umberfirm__employee__delete_employee',
            [
                'employee' => static::$entities['employee']->getId()->toString(),
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
            'umberfirm__employee__get_employees',
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
    public function testOnBadUuidFormatDeleteAction($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__employee__delete_employee',
            [
                'employee' => $uuid,
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
