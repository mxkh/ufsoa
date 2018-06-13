<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Tests\Functional\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\MediaBundle\Component\Manager\MediaManagerInterface;
use UmberFirm\Bundle\MediaBundle\Entity\Media;
use UmberFirm\Bundle\MediaBundle\Entity\MediaEnum;

/**
 * Class MediaControllerTest
 *
 * @package UmberFirm\Bundle\MediaBundle\Tests\Functional\Controller
 */
class MediaControllerTest extends BaseTestCase
{
    /**
     * @var Media[]
     */
    private static $entities = [];

    /**
     * @var MediaManagerInterface
     */
    private $mediaManager;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->loginEmployee();

        $file = new UploadedFile(
            __DIR__.'/../Fixtures/text.txt',
            'text.txt'
        );

        $this->payload = ['file' => $file];

        $this->mediaManager = $this->getMockBuilder(MediaManagerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['create', 'delete'])
            ->getMock();
        $this->client->getContainer()->set('umberfirm.media.component.manager.media_manager', $this->mediaManager);
    }

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $mediaEnum = new MediaEnum();
        $mediaEnum->setName('image');
        static::getObjectManager()->persist($mediaEnum);

        $media = new Media();
        $media->setMediaEnum($mediaEnum);
        $media->setFilename('1.jpg');
        $media->setExtension('jpg');
        $media->setMimeType('image/jpeg');
        static::getObjectManager()->persist($media);

        static::getObjectManager()->flush();
        self::$entities['mediaEnum'] = $mediaEnum;
        self::$entities['media'] = $media;
    }

    public function testCGetMedias()
    {
        $uri = $this->router->generate('umberfirm__media__cget_media');
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

    public function testGetMedia()
    {
        $uri = $this->router->generate(
            'umberfirm__media__get_media',
            [
                'media' => self::$entities['media']->getId()->toString(),
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
        $media = json_decode($response->getContent());
        $this->assertJsonResponse($response, Response::HTTP_OK);
        $this->assertEquals(self::$entities['media']->getId()->toString(), $media->id);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testGetMediaNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__media__get_media',
            [
                'media' => $uuid,
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

    public function testPostMediaValidation()
    {
        $uri = $this->router->generate('umberfirm__media__post_media');
        $this->client->request(
            'POST',
            $uri,
            [],
            $this->payload,
            [
                'HTTP_ACCEPT' => 'application/json',
            ]
        );
        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPostMedia()
    {
        $this->mediaManager
            ->expects($this->once())
            ->method('create')
            ->willReturn(sprintf('%s.%s', md5(Uuid::uuid4()->toString()), 'jpg'));

        $uri = $this->router->generate('umberfirm__media__post_media');
        $this->payload['file'] = new UploadedFile(__DIR__.'/../Fixtures/1.jpg', '1.jpg');

        $this->client->request(
            'POST',
            $uri,
            [],
            $this->payload,
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED);
    }

    public function testPostMediaInternalServerError()
    {
        $this->mediaManager
            ->expects($this->once())
            ->method('create')
            ->willReturn(null);

        $uri = $this->router->generate('umberfirm__media__post_media');
        $this->payload['file'] = new UploadedFile(__DIR__.'/../Fixtures/1.jpg', '1.jpg');

        $this->client->request(
            'POST',
            $uri,
            [],
            $this->payload,
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function testDeleteMedia()
    {
        $this->mediaManager
            ->expects($this->once())
            ->method('delete');

        $uri = $this->router->generate(
            'umberfirm__media__delete_media',
            [
                'media' => self::$entities['media']->getId()->toString(),
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
            'umberfirm__media__cget_media',
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
    public function testDeleteMediaNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__media__delete_media',
            [
                'media' => $uuid,
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
