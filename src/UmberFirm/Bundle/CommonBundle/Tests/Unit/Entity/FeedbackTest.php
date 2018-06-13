<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\CommonBundle\Entity\Feedback;
use UmberFirm\Bundle\CommonBundle\Entity\Subject;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class FeedbackTest
 *
 * @package UmberFirm\Bundle\CommonBundle\Tests\Unit\Entity
 */
class FeedbackTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Feedback
     */
    private $feedback;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->feedback = new Feedback();
    }

    public function testSource()
    {
        $this->assertInstanceOf(Feedback::class, $this->feedback->setSource(null));
        $this->assertInternalType('string', $this->feedback->getSource());
        $this->assertInstanceOf(Feedback::class, $this->feedback->setSource('some string'));
        $this->assertEquals('some string', $this->feedback->getSource());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSourceTypeError()
    {
        $this->feedback->setSource(123);
    }

    public function testName()
    {
        $this->assertInstanceOf(Feedback::class, $this->feedback->setName(null));
        $this->assertInternalType('string', $this->feedback->getName());
        $this->assertInstanceOf(Feedback::class, $this->feedback->setName('some string'));
        $this->assertEquals('some string', $this->feedback->getName());
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameTypeError()
    {
        $this->feedback->setName(123);
    }

    public function testEmail()
    {
        $this->assertInstanceOf(Feedback::class, $this->feedback->setEmail(null));
        $this->assertInternalType('string', $this->feedback->getEmail());
        $this->assertInstanceOf(Feedback::class, $this->feedback->setEmail('some string'));
        $this->assertEquals('some string', $this->feedback->getEmail());
    }

    /**
     * @expectedException \TypeError
     */
    public function testEmailTypeError()
    {
        $this->feedback->setEmail(123);
    }

    public function testPhone()
    {
        $this->assertInstanceOf(Feedback::class, $this->feedback->setPhone(null));
        $this->assertInternalType('string', $this->feedback->getPhone());
        $this->assertInstanceOf(Feedback::class, $this->feedback->setPhone('some string'));
        $this->assertEquals('some string', $this->feedback->getPhone());
    }

    /**
     * @expectedException \TypeError
     */
    public function testPhoneTypeError()
    {
        $this->feedback->setPhone(123);
    }

    public function testMessage()
    {
        $this->assertInstanceOf(Feedback::class, $this->feedback->setMessage(null));
        $this->assertInternalType('string', $this->feedback->getMessage());
        $this->assertInstanceOf(Feedback::class, $this->feedback->setMessage('some string'));
        $this->assertEquals('some string', $this->feedback->getMessage());
    }

    /**
     * @expectedException \TypeError
     */
    public function testMessageTypeError()
    {
        $this->feedback->setMessage(123);
    }

    public function testSubject()
    {
        $this->assertInstanceOf(Feedback::class, $this->feedback->setSubject(null));
        $this->assertNull($this->feedback->getSubject());
        $this->assertInstanceOf(Feedback::class, $this->feedback->setSubject(new Subject()));
        $this->assertInstanceOf(Subject::class, $this->feedback->getSubject());
    }

    /**
     * @expectedException \TypeError
     */
    public function testSubjectTypeError()
    {
        $this->feedback->setSubject(new \stdClass());
    }

    public function testShop()
    {
        $this->assertInstanceOf(Feedback::class, $this->feedback->setShop(null));
        $this->assertNull($this->feedback->getShop());
        $this->assertInstanceOf(Feedback::class, $this->feedback->setShop(new Shop()));
        $this->assertInstanceOf(Shop::class, $this->feedback->getShop());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopTypeError()
    {
        $this->feedback->setShop(new \stdClass());
    }

    public function testCustomer()
    {
        $this->assertInstanceOf(Feedback::class, $this->feedback->setCustomer(null));
        $this->assertNull($this->feedback->getCustomer());
        $this->assertInstanceOf(Feedback::class, $this->feedback->setCustomer(new Customer()));
        $this->assertInstanceOf(Customer::class, $this->feedback->getCustomer());
    }

    /**
     * @expectedException \TypeError
     */
    public function testCustomerTypeError()
    {
        $this->feedback->setCustomer(new \stdClass());
    }
}
