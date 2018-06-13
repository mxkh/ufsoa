<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\CommonBundle\Entity\Subject;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class SubjectTest
 *
 * @package UmberFirm\Bundle\CommonBundle\Tests\Unit\Entity
 */
class SubjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Subject
     */
    private $subject;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->subject = new Subject();
    }

    public function testName()
    {
        /** @var string $locale */
        $locale = $this->subject->getCurrentLocale();
        $this->assertInstanceOf(Subject::class, $this->subject->setName('string', $locale));
        $this->assertInternalType('string', $this->subject->getName());
    }

    /**
     * @expectedException \TypeError
     */
    public function testNameTypeError()
    {
        /** @var string $locale */
        $locale = $this->subject->getDefaultLocale();
        $this->subject->setName(null, $locale);
    }

    public function testIsActive()
    {
        $this->assertTrue($this->subject->isActive());
        $this->assertInstanceOf(Subject::class, $this->subject->setIsActive(null));
        $this->assertFalse($this->subject->isActive());
        $this->assertInstanceOf(Subject::class, $this->subject->setIsActive(true));
        $this->assertTrue($this->subject->isActive());
    }

    /**
     * @expectedException \TypeError
     */
    public function testIsActiveTypeError()
    {
        $this->subject->setIsActive(1);
    }

    public function testShop()
    {
        $this->assertInstanceOf(Subject::class, $this->subject->setShop(null));
        $this->assertNull($this->subject->getShop());
        $this->assertInstanceOf(Subject::class, $this->subject->setShop(new Shop()));
        $this->assertInstanceOf(Shop::class, $this->subject->getShop());
    }

    /**
     * @expectedException \TypeError
     */
    public function testShopTypeError()
    {
        $this->subject->setShop(new \stdClass());
    }
}
