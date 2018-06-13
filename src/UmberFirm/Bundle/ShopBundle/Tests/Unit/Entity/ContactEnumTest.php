<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\ShopBundle\Entity\Contact;
use UmberFirm\Bundle\ShopBundle\Entity\ContactEnum;

/**
 * Class ContactEnumTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity
 */
class ContactEnumTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContactEnum
     */
    private $contactEnum;

    /** @var string */
    private $locale;

    /**
     *{@inheritdoc}
     */
    protected function setUp()
    {
        $this->contactEnum = new ContactEnum();
        $this->locale = $this->contactEnum->getCurrentLocale();
    }

    /**
     * @expectedException \TypeError
     */
    public function testContacts()
    {
        $contact = new Contact();
        $this->assertInstanceOf(ContactEnum::class, $this->contactEnum->addContact($contact));
        $this->assertInstanceOf(Collection::class, $this->contactEnum->getContacts());
        $this->contactEnum->addContact(new \stdClass());
    }

    public function testValue()
    {
        $this->assertInstanceOf(contactEnum::class, $this->contactEnum->setValue("100", $this->locale));
        $this->assertInternalType('string', $this->contactEnum->getValue());
        $this->assertEquals("100", $this->contactEnum->getValue());
        $this->contactEnum->setValue("100", $this->locale);
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongValue()
    {
        $this->contactEnum->setValue(123, $this->locale);
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongValueLocale()
    {
        $this->contactEnum->setValue("123", 123);
    }
}
