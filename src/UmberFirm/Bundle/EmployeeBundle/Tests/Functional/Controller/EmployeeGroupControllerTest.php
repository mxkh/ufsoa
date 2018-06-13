<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\EmployeeBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CommonBundle\Entity\Language;
use UmberFirm\Bundle\EmployeeBundle\Entity\Employee;
use UmberFirm\Bundle\EmployeeBundle\Entity\EmployeeGroup;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;

/**
 * Class EmployeeGroupControllerTest
 *
 * @package UmberFirm\Bundle\EmployeeBundle\Tests\Functional\Controller
 */
class EmployeeGroupControllerTest extends BaseTestCase
{
    /**
     * @var Language[]
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
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $employee = new Employee();
        $employee->setName('123');
        $employee->setEmail('test@gmail.com');
        $employee->setPhone('123');
        $employee->setBirthday(new \DateTime());
        $employee->setPassword('1234567890');
        static::getObjectManager()->persist($employee);

        $employeeGroup = new EmployeeGroup();
        $employeeGroup->setName('Englesh', 'en'); // This will be fixed on update test
        $employeeGroup->addEmployee($employee);
        static::getObjectManager()->persist($employeeGroup);

        static::getObjectManager()->flush();

        self::$entities['employee'] = $employee;
        self::$entities['employeeGroup'] = $employeeGroup;
    }

    public function testEmployeeGroupList()
    {
        $uri = $this->router->generate('umberfirm__employee__get_employee-groups');

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

    public function testGetEmployee()
    {
        $id = self::$entities['employeeGroup']->getId()->toString();

        $uri = $this->router->generate(
            'umberfirm__employee__get_employee-group',
            [
                'employeeGroup' => self::$entities['employeeGroup']->getId()->toString(),
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
        $response = $this->client->getResponse();
        $language = json_decode($response->getContent());
        $this->assertJsonResponse($response, Response::HTTP_OK);
        $this->assertEquals($id, $language->id);
    }

    /**
     * Try to get specified employee-group on not existed uid.
     *
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetEmployeeGroupNotFoundBadUuid($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__employee__get_employee-group',
            [
                'employeeGroup' => $uuid,
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
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND);
    }

    public function testGetEmployeeGroupTranslations()
    {
        $uri = $this->router->generate(
            'umberfirm__employee__get_employee-group_translations',
            [
                'employeeGroup' => self::$entities['employeeGroup']->getId()->toString(),
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
    public function testGetEmployeeGroupTranslationsNotFoundBadUuid($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__employee__get_employee-group_translations',
            [
                'employeeGroup' => $uuid,
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

    public function testPostEmployeeGroupValidation()
    {
        $uri = $this->router->generate('umberfirm__employee__post_employee-group');
        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(['code' => 'ENG'])
        );
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPostEmployeeGroup()
    {
        $uri = $this->router->generate('umberfirm__employee__post_employee-group');
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

    public function testPutEmployeeGroup()
    {
        $uri = $this->router->generate(
            'umberfirm__employee__put_employee-group',
            [
                'employeeGroup' => self::$entities['employeeGroup']->getId()->toString(),
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
        $response = $this->client->getResponse();
        $employeeGroup = json_decode($response->getContent());

        $this->assertEquals(self::$entities['employeeGroup']->getId()->toString(), $employeeGroup->id);
        $this->assertJsonResponse($response, Response::HTTP_OK);
    }

    /**
     * Try to get specified employee-group on not existed uid.
     *
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPutEmployeeGroupNotFoundBadUuid($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__employee__put_employee-group',
            [
                'employeeGroup' => $uuid,
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
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND);
    }

    public function testPutEmployeeGroupValidation()
    {
        unset($this->payload['translations']);
        $uri = $this->router->generate(
            'umberfirm__employee__put_employee-group',
            [
                'employeeGroup' => self::$entities['employeeGroup']->getId()->toString(),
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
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_BAD_REQUEST);
    }

    public function testDeleteEmployeeGroup()
    {
        $uri = $this->router->generate(
            'umberfirm__employee__delete_employee-group',
            [
                'employeeGroup' => self::$entities['employeeGroup']->getId()->toString(),
            ]
        );
        $listUri = $this->router->generate(
            'umberfirm__employee__get_employee-groups',
            [],
            Router::ABSOLUTE_URL
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
        $this->assertTrue(
            $this->client->getResponse()->headers->contains('Location', $listUri),
            $this->client->getResponse()->headers
        );
    }

    /**
     * Try to get specified employee-group on not existed uid.
     *
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testDeleteEmployeeGroupNotFoundBadUuid($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__employee__delete_employee-group',
            [
                'employeeGroup' => $uuid,
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
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Response::HTTP_NOT_FOUND);
    }
}
