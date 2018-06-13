<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\SubscriptionBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\SubscriptionBundle\Entity\Subscriber;

/**
 * Class SubscriberTest
 *
 * @package UmberFirm\Bundle\SubscriptionBundle\Tests\Unit\Entity
 */
class SubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Subscriber
     */
    private $subscriber;

    protected function setUp()
    {
        parent::setUp();

        $this->subscriber = new Subscriber();
    }

    public function testEmail()
    {
        $this->assertInstanceOf(Subscriber::class, $this->subscriber->setEmail('john@doe.com'));
        $this->assertEquals('john@doe.com', $this->subscriber->getEmail());
        $this->assertInternalType('string', $this->subscriber->getEmail());
    }
}
