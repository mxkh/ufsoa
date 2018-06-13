<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Tests\Unit\Event\Subscriber;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use UmberFirm\Bundle\CommonBundle\Component\Producer\FeedbackProducerInterface;
use UmberFirm\Bundle\CommonBundle\Entity\Feedback;
use UmberFirm\Bundle\CommonBundle\Event\Subscriber\FeedbackListener;

/**
 * Class FeedbackListenerTest
 *
 * @package UmberFirm\Bundle\CommonBundle\Tests\Unit\Event\Subscriber
 */
class FeedbackListenerTest extends \PHPUnit_Framework_TestCase
{
    /** @var  FeedbackProducerInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $feedbackProducer;

    /** @var  LifecycleEventArgs|\PHPUnit_Framework_MockObject_MockObject */
    private $lifecycleEventArgs;

    public function setUp()
    {
        $this->feedbackProducer = $this->createMock(FeedbackProducerInterface::class);
        $this->lifecycleEventArgs = $this->createMock(LifecycleEventArgs::class);
    }

    public function testPostPersist()
    {
        $this->feedbackProducer->expects($this->once())->method('syncCreateZendesk');
        $this->lifecycleEventArgs->expects($this->once())->method('getEntity')->willReturn(new Feedback());
        $listener = new FeedbackListener($this->feedbackProducer);
        $listener->postPersist($this->lifecycleEventArgs);
    }

    public function testPostPersistWrongEntity()
    {
        $this->feedbackProducer->expects($this->never())->method('syncCreateZendesk');
        $this->lifecycleEventArgs->expects($this->once())->method('getEntity')->willReturn(new \stdClass());
        $listener = new FeedbackListener($this->feedbackProducer);
        $listener->postPersist($this->lifecycleEventArgs);
    }

    public function testPostRemove()
    {
        $this->feedbackProducer->expects($this->once())->method('syncRemoveZendesk');
        $this->lifecycleEventArgs->expects($this->once())->method('getEntity')->willReturn(new Feedback());
        $listener = new FeedbackListener($this->feedbackProducer);
        $listener->postRemove($this->lifecycleEventArgs);
    }

    public function testPostRemoveWrongEntity()
    {
        $this->feedbackProducer->expects($this->never())->method('syncRemoveZendesk');
        $this->lifecycleEventArgs->expects($this->once())->method('getEntity')->willReturn(new \stdClass());
        $listener = new FeedbackListener($this->feedbackProducer);
        $listener->postRemove($this->lifecycleEventArgs);
    }

    public function testGetSubscribedEvents()
    {
        $listener = new FeedbackListener($this->feedbackProducer);
        $this->assertArraySubset([Events::postPersist, Events::postRemove,], $listener->getSubscribedEvents());
    }
}
