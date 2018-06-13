<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ManufacturerBundle\Tests;

use Doctrine\Common\Persistence\ObjectManager;
use Elastica\Index;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\DefaultEncoder;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\EmployeeBundle\Entity\Employee;
use UmberFirm\Component\Catalog\Client\Elastic\Client as ElasticaClient;

/**
 * Class BaseTestCase
 *
 * @package UmberFirm\Bundle\ManufacturerBundle\Tests
 */
abstract class BaseTestCase extends WebTestCase
{
    /**
     * @var Client
     */
    private static $webClient;

    /**
     * @var Application
     */
    private static $application = null;

    /**
     * @var ObjectManager
     */
    private static $objectManager;

    /**
     * @var array
     */
    protected $payload = [];

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var DefaultEncoder
     */
    protected $encoder;

    /**
     * @var Router
     */
    protected $router;

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        self::setWebClient(static::createClient())
            ->setApplication(new Application(self::getWebClient()->getKernel()))
            ->setObjectManager(static::$kernel->getContainer()->get('doctrine')->getManager())
            ->setUpMysql();
    }

    /**
     * {@inheritdoc}
     */
    public static function tearDownAfterClass()
    {
        self::tearDownMysql();
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->router = $this->client->getContainer()->get('router');
        $this->encoder = $this->client->getContainer()->get('lexik_jwt_authentication.encoder');
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $refl = new \ReflectionObject($this);
        foreach ($refl->getProperties() as $prop) {
            if (false === $prop->isStatic() && 0 !== strpos($prop->getDeclaringClass()->getName(), 'PHPUnit_')) {
                $prop->setAccessible(true);
                $prop->setValue($this, null);
            }
        }
    }

    /**
     * @return Application
     */
    public static function getApplication()
    {
        if (null === self::$application) {
            self::$application = new Application(static::createClient()->getKernel());
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }

    /**
     * @param $command
     *
     * @return int
     */
    public static function runAppConsoleCommand($command)
    {
        $command = sprintf('%s --env=test', $command);

        return self::getApplication()->run(new StringInput($command));
    }

    /**
     * @return void
     */
    public static function setUpMysql(): void
    {
        self::runAppConsoleCommand('doctrine:database:drop --force -q');
        self::runAppConsoleCommand('doctrine:database:create -q');
        self::runAppConsoleCommand('doctrine:schema:update --force -q');
    }

    /**
     * @return void
     */
    public static function setUpMysqlFixtures(): void
    {
        self::runAppConsoleCommand('doctrine:fixtures:load --append -q');
    }

    /**
     * @return void
     */
    public static function tearDownMysql(): void
    {
        self::runAppConsoleCommand('doctrine:database:drop --force -q');
    }

    /**
     * @param Application $application
     *
     * @return static
     */
    public static function setApplication(Application $application)
    {
        self::$application = $application;
        self::$application->setAutoExit(false);

        return new static();
    }

    /**
     * @return ObjectManager
     */
    public static function getObjectManager()
    {
        return self::$objectManager;
    }

    /**
     * @param ObjectManager $objectManager
     *
     * @return static
     */
    public static function setObjectManager(ObjectManager $objectManager)
    {
        self::$objectManager = $objectManager;

        return new static();
    }

    /**
     * @return Client
     */
    public static function getWebClient()
    {
        return self::$webClient;
    }

    /**
     * @param Client $webClient
     *
     * @return static
     */
    public static function setWebClient(Client $webClient)
    {
        self::$webClient = $webClient;

        return new static();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ElasticaClient
     */
    public function getMockElastica()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|ElasticaClient $elastica */
        $elastica = $this->createMock(ElasticaClient::class);
        $elastica->method('getIndex')->willReturn(new Index($elastica, 'umberfirm'));

        $this->client->getContainer()->set('fos_elastica.client.default', $elastica);

        return $elastica;
    }

    /**
     * code  was taken from bazinga web test case
     *
     * @param Response $response
     * @param int $statusCode
     */
    protected function assertJsonResponse(Response $response, int $statusCode)
    {
        $this->assertEquals(
            $statusCode,
            $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
        $this->assertJson($response->getContent());
    }

    /**
     * @return array
     */
    public function invalidUuidDataProvider()
    {
        return [
            [Uuid::NIL], //not found
            ['bad-uuid-format'], //bad format
        ];
    }

    protected function loginEmployee()
    {
        $employee = new Employee();
        $jwtToken = $this->encoder->encode(['email' => 'email@com.ua']);

        $token = new JWTUserToken($employee->getRoles(), $employee, $jwtToken);
        $this->client->getContainer()->get('security.token_storage')->setToken($token);
    }
}
