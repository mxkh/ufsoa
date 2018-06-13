<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\ShopBundle\Entity\SocialProfileEnum;
use UmberFirm\Bundle\ShopBundle\Entity\StoreSocialProfile;

/**
 * Class SocialProfileEnumTest
 *
 * @package UmberFirm\Bundle\ShopBundle\Tests\Unit\Entity
 */
class SocialProfileEnumTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SocialProfileEnum
     */
    private $socialProfileEnum;

    /** @var string */
    private $locale;

    /**
     *{@inheritdoc}
     */
    protected function setUp()
    {
        $this->socialProfileEnum = new SocialProfileEnum();
        $this->locale = $this->socialProfileEnum->getCurrentLocale();
    }

    public function testStoreSocialProfile()
    {
        $storeSocialProfile = new StoreSocialProfile();
        $this->assertInstanceOf(
            SocialProfileEnum::class,
            $this->socialProfileEnum->addStoreSocialProfile($storeSocialProfile)
        );
        $this->assertInstanceOf(Collection::class, $this->socialProfileEnum->getStoreSocialProfiles());
        $this->socialProfileEnum->addStoreSocialProfile($storeSocialProfile);
    }

    public function testAddStoreSocialProfile()
    {
        /** @var StoreSocialProfile|\PHPUnit_Framework_MockObject_MockObject $order */
        $storeSocialProfile = $this->createMock(StoreSocialProfile::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $storeSocialProfiles = $this->createMock(Collection::class);
        $storeSocialProfiles
            ->expects($this->once())
            ->method('contains')
            ->with($storeSocialProfile)
            ->willReturn(false);
        $storeSocialProfiles
            ->expects($this->once())
            ->method('add')
            ->with($storeSocialProfile);
        $storeSocialProfile
            ->expects($this->once())
            ->method('setSocialProfileEnum');

        $ordersReflect = new \ReflectionProperty(SocialProfileEnum::class, 'storeSocialProfiles');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->socialProfileEnum, $storeSocialProfiles);

        $this->socialProfileEnum->addStoreSocialProfile($storeSocialProfile);
    }

    public function testAddStoreSocialProfileExists()
    {
        /** @var StoreSocialProfile|\PHPUnit_Framework_MockObject_MockObject $order */
        $storeSocialProfile = $this->createMock(StoreSocialProfile::class);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $orders */
        $storeSocialProfiles = $this->createMock(Collection::class);
        $storeSocialProfiles
            ->expects($this->once())
            ->method('contains')
            ->with($storeSocialProfile)
            ->willReturn(true);
        $storeSocialProfiles
            ->expects($this->never())
            ->method('add');
        $storeSocialProfile
            ->expects($this->never())
            ->method('setSocialProfileEnum');

        $ordersReflect = new \ReflectionProperty(SocialProfileEnum::class, 'storeSocialProfiles');
        $ordersReflect->setAccessible(true);
        $ordersReflect->setValue($this->socialProfileEnum, $storeSocialProfiles);

        $this->socialProfileEnum->addStoreSocialProfile($storeSocialProfile);
    }

    public function testName()
    {
        $this->assertInstanceOf(SocialProfileEnum::class, $this->socialProfileEnum->setName("100", $this->locale));
        $this->assertInternalType('string', $this->socialProfileEnum->getName());
        $this->assertEquals("100", $this->socialProfileEnum->getName());
        $this->socialProfileEnum->setName("100", $this->locale);
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongTypeName()
    {
        $this->socialProfileEnum->setName(123, $this->locale);
    }

    /**
     * @expectedException \TypeError
     */
    public function testWrongValueLocale()
    {
        $this->socialProfileEnum->setName("123", 123);
    }

    public function testGetStoreSocialProfiles()
    {
        $this->assertInstanceOf(Collection::class, $this->socialProfileEnum->GetStoreSocialProfiles());
    }

    public function testAlias()
    {
        $this->assertInstanceOf(SocialProfileEnum::class, $this->socialProfileEnum->setAlias('123123', 'en'));
        $this->assertEquals('123123', $this->socialProfileEnum->getAlias());
    }
}
