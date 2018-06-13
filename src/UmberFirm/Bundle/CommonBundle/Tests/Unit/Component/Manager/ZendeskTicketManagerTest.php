<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Tests\Unit\Component\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use UmberFirm\Bundle\CommonBundle\Component\Manager\ZendeskTicketManager;
use UmberFirm\Bundle\CommonBundle\Entity\Feedback;
use UmberFirm\Bundle\CommonBundle\Entity\Subject;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Repository\ShopSettingsRepository;
use Zendesk\API\Exceptions\AuthException;
use Zendesk\API\HttpClient;
use Zendesk\API\Resources\Core\Tickets;

/**
 * Class ZendeskTicketManagerTest
 *
 * @package UmberFirm\Bundle\CommonBundle\Tests\Unit\Component\Manager
 */
class ZendeskTicketManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HttpClient|\PHPUnit_Framework_MockObject_MockObject
     */
    private $zendesk;

    /**
     * @var EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $entityManager;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->zendesk = $this->createMock(HttpClient::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
    }

    public function testAuth()
    {
        $settings = [
            ZendeskTicketManager::SETTING_USERNAME => 'username',
            ZendeskTicketManager::SETTING_TOKEN => 'token',
            ZendeskTicketManager::SETTING_BRAND => 'brand_id',
        ];

        //getSettings
        $repository = $this->createMock(ShopSettingsRepository::class);
        $repository->expects($this->once())->method('findSetting')->willReturn($settings);
        $this->entityManager->expects($this->once())->method('getRepository')->willReturn($repository);

        $manager = new ZendeskTicketManager($this->zendesk, $this->entityManager);
        $manager->auth(new Shop());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAuthWrongSettings()
    {
        $settings = [
            ZendeskTicketManager::SETTING_USERNAME => 'username',
            ZendeskTicketManager::SETTING_BRAND => 'brand_id',
        ];

        //getSettings
        $repository = $this->createMock(ShopSettingsRepository::class);
        $repository->expects($this->once())->method('findSetting')->willReturn($settings);
        $this->entityManager->expects($this->once())->method('getRepository')->willReturn($repository);

        $manager = new ZendeskTicketManager($this->zendesk, $this->entityManager);
        $manager->auth(new Shop());
    }

    public function testExecute()
    {
        $settings = [
            ZendeskTicketManager::SETTING_USERNAME => 'username',
            ZendeskTicketManager::SETTING_TOKEN => 'token',
            ZendeskTicketManager::SETTING_BRAND => 'brand_id',
        ];

        //getSettings
        $repository = $this->createMock(ShopSettingsRepository::class);
        $repository->expects($this->once())->method('findSetting')->willReturn($settings);
        $this->entityManager->expects($this->once())->method('getRepository')->willReturn($repository);

        //feedback
        $uuid = $this->createMock(UuidInterface::class);
        $uuid->method('toString')->willReturn(Uuid::uuid4());
        $feedback = $this->createMock(Feedback::class);
        $feedback->method('getId')->willReturn($uuid);
        $feedback->method('getSubject')->willReturn(new Subject());
        $feedback->method('getShop')->willReturn(new Shop());
        $this->entityManager->expects($this->any())->method('find')->willReturn($feedback);

        $ticketResult = new \stdClass();
        $ticketResult->ticket = new \stdClass();
        $ticketResult->ticket->id = 123;
        //create
        $tickets = $this->createMock(Tickets::class);
        $tickets->expects($this->once())->method('create')->willReturn($ticketResult);
        $this->zendesk->expects($this->once())->method('__call')->willReturn($tickets);

        $manager = new ZendeskTicketManager($this->zendesk, $this->entityManager);
        $this->assertTrue($manager->execute(ZendeskTicketManager::CREATE_ACTION, $feedback));
    }

    public function testExecuteWrongAction()
    {
        $settings = [
            ZendeskTicketManager::SETTING_USERNAME => 'username',
            ZendeskTicketManager::SETTING_TOKEN => 'token',
            ZendeskTicketManager::SETTING_BRAND => 'brand_id',
        ];

        //getSettings
        $repository = $this->createMock(ShopSettingsRepository::class);
        $repository->expects($this->once())->method('findSetting')->willReturn($settings);
        $this->entityManager->expects($this->once())->method('getRepository')->willReturn($repository);

        //feedback
        $uuid = $this->createMock(UuidInterface::class);
        $uuid->method('toString')->willReturn(Uuid::uuid4());
        $feedback = $this->createMock(Feedback::class);
        $feedback->method('getId')->willReturn($uuid);
        $feedback->method('getSubject')->willReturn(new Subject());
        $feedback->method('getShop')->willReturn(new Shop());
        $this->entityManager->expects($this->any())->method('find')->willReturn($feedback);

        //create
        $tickets = $this->createMock(Tickets::class);
        $tickets->expects($this->once())->method('create')->willReturn(null);
        $this->zendesk->expects($this->once())->method('__call')->willReturn($tickets);

        $manager = new ZendeskTicketManager($this->zendesk, $this->entityManager);
        $this->assertFalse($manager->execute(ZendeskTicketManager::CREATE_ACTION, $feedback));
    }

    public function testExecuteCreateException()
    {
        $settings = [
            ZendeskTicketManager::SETTING_USERNAME => 'username',
            ZendeskTicketManager::SETTING_TOKEN => 'token',
            ZendeskTicketManager::SETTING_BRAND => 'brand_id',
        ];

        //getSettings
        $repository = $this->createMock(ShopSettingsRepository::class);
        $repository->expects($this->once())->method('findSetting')->willReturn($settings);
        $this->entityManager->expects($this->once())->method('getRepository')->willReturn($repository);

        //feedback
        $uuid = $this->createMock(UuidInterface::class);
        $uuid->method('toString')->willReturn(Uuid::uuid4());
        $feedback = $this->createMock(Feedback::class);
        $feedback->method('getId')->willReturn($uuid);
        $feedback->method('getSubject')->willReturn(new Subject());
        $feedback->method('getShop')->willReturn(new Shop());
        $this->entityManager->expects($this->any())->method('find')->willReturn($feedback);

        //create
        $tickets = $this->createMock(Tickets::class);
        $tickets->expects($this->once())->method('create')->willThrowException(new AuthException());
        $this->zendesk->expects($this->once())->method('__call')->willReturn($tickets);

        $manager = new ZendeskTicketManager($this->zendesk, $this->entityManager);
        $this->assertFalse($manager->execute(ZendeskTicketManager::CREATE_ACTION, $feedback));
    }

    public function testRemove()
    {
        //remove
        $tickets = $this->createMock(Tickets::class);
        $tickets->expects($this->once())->method('delete')->willReturn(null);
        $this->zendesk->expects($this->once())->method('__call')->willReturn($tickets);

        $feedback = $this->createMock(Feedback::class);

        $manager = new ZendeskTicketManager($this->zendesk, $this->entityManager);
        $this->assertTrue($manager->remove($feedback));
    }

    public function testRemoveFailed()
    {
        //remove
        $tickets = $this->createMock(Tickets::class);
        $tickets->expects($this->once())->method('delete')->willReturn(1);
        $this->zendesk->expects($this->once())->method('__call')->willReturn($tickets);

        $feedback = $this->createMock(Feedback::class);

        $manager = new ZendeskTicketManager($this->zendesk, $this->entityManager);
        $this->assertFalse($manager->remove($feedback));
    }
}
