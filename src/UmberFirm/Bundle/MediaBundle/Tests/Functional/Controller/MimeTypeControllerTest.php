<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ManufacturerBundle\Tests\BaseTestCase;
use UmberFirm\Bundle\MediaBundle\Entity\MediaEnum;
use UmberFirm\Bundle\MediaBundle\Entity\MimeType;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class MimeTypeControllerTest
 *
 * @package UmberFirm\Bundle\MediaBundle\Tests\Functional\Controller
 */
class MimeTypeControllerTest extends BaseTestCase
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

        $mediaEnumImage = (new MediaEnum())
            ->setName('image');
        $manager->persist($mediaEnumImage);

        $mediaEnumVideo = (new MediaEnum())
            ->setName('video');
        $manager->persist($mediaEnumVideo);

        $MimeTypeImagePng = (new MimeType())
            ->setMediaEnum($mediaEnumImage)
            ->setName('png')
            ->setTemplate('image/png');
        $manager->persist($mediaEnumImage);

        $MimeTypeImageJpeg = (new MimeType())
            ->setMediaEnum($mediaEnumImage)
            ->setName('jpeg')
            ->setTemplate('image/jpeg');
        $manager->persist($MimeTypeImageJpeg);

        $MimeTypeVideoMp4 = (new MimeType())
            ->setMediaEnum($mediaEnumVideo)
            ->setName('mp4')
            ->setTemplate('video/mp4');
        $manager->persist($MimeTypeVideoMp4);

        $manager->flush();

        self::$entities = [
            'MediaEnum:Image' => $mediaEnumImage,
            'MediaEnum:Video' => $mediaEnumVideo,
            'MimeType:Image:Png' => $MimeTypeImagePng,
            'MimeType:Image:Jpeg' => $MimeTypeImageJpeg,
            'MimeType:Video:Mp4' => $MimeTypeVideoMp4,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->loginEmployee();

        $this->payload = [
            'mediaEnum' => self::$entities['MediaEnum:Image']->getId()->toString(),
            'name' => 'gif',
            'template' => 'image/gif',
        ];
    }

    public function testGetMimeTypeList()
    {
        $uri = $this->router->generate('umberfirm__media__get_mime-types');

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

    public function testGetMimeType()
    {
        $uri = $this->router->generate(
            'umberfirm__media__get_mime-type',
            [
                'mimeType' => static::$entities['MimeType:Image:Jpeg']->getId()->toString(),
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
    public function testGetMimeTypeNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__media__get_mime-type',
            [
                'mimeType' => $uuid,
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

    public function testPostMimeType()
    {
        $uri = $this->router->generate('umberfirm__media__post_mime-type');

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

    public function testPostMimeTypeBadRequest()
    {
        $uri = $this->router->generate('umberfirm__media__post_mime-type');

        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode(['hello' => 'world'])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    public function testPutMimeType()
    {
        $uri = $this->router->generate(
            'umberfirm__media__put_mime-type',
            [
                'mimeType' => static::$entities['MimeType:Image:Jpeg']->getId()->toString(),
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
        $this->assertEquals('gif', $putResponse->name);
    }

    public function testPutMimeTypeBadRequest()
    {
        $uri = $this->router->generate(
            'umberfirm__media__put_mime-type',
            [
                'mimeType' => static::$entities['MimeType:Image:Jpeg']->getId()->toString(),
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
            json_encode(['hello' => 'world'])
        );

        $this->assertJsonResponse($this->client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @dataProvider invalidUuidDataProvider
     *
     * @param string $uuid
     */
    public function testPutMimeTypeNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__media__put_mime-type',
            [
                'mimeType' => $uuid,
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

    public function testDeleteMimeType()
    {
        $uri = $this->router->generate(
            'umberfirm__media__delete_mime-type',
            [
                'mimeType' => static::$entities['MimeType:Image:Jpeg']->getId()->toString(),
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
            'umberfirm__media__get_mime-types',
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
    public function testDeleteMimeTypeNotFound($uuid)
    {
        $uri = $this->router->generate(
            'umberfirm__media__delete_mime-type',
            [
                'mimeType' => $uuid,
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
