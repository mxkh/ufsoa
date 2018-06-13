<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\ShopBundle\Entity\ContactEnumTranslation;

/**
 * Class ContactEnumTranslationTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity
 */
class ContactEnumTranslationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContactEnumTranslation
     */
    private $contactEnumTranslation;

    /**
     *{@inheritdoc}
     */
    protected function setUp()
    {
        $this->contactEnumTranslation = new ContactEnumTranslation();
    }

    public function testValue()
    {
        $this->assertInstanceOf(ContactEnumTranslation::class, $this->contactEnumTranslation->setValue("100"));
        $this->assertInternalType('string', $this->contactEnumTranslation->getValue());
        $this->assertEquals("100", $this->contactEnumTranslation->getValue());
        $this->contactEnumTranslation->setValue("100");
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongValue()
    {
        $this->contactEnumTranslation->setValue(123);
    }
}
