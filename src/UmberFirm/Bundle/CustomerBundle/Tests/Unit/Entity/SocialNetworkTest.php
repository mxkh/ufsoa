<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\Collection;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerSocialIdentity;
use UmberFirm\Bundle\CustomerBundle\Entity\SocialNetwork;

/**
 * Class SocialNetworkTest
 *
 * @package UmberFirm\Bundle\CustomerBundle\Tests\Unit
 */
class SocialNetworkTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SocialNetwork
     */
    private $socialNetwork;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->socialNetwork = new SocialNetwork();
    }

    public function testName()
    {
        $this->assertInstanceOf(SocialNetwork::class, $this->socialNetwork->setName('facebook'));
        $this->assertEquals('facebook', $this->socialNetwork->getName());
    }

    public function testCustomerSocialIdentity()
    {
        $customerSocialIdentity = new CustomerSocialIdentity();
        $this->assertInstanceOf(SocialNetwork::class, $this->socialNetwork->addCustomerSocialIdentity($customerSocialIdentity));
        $this->assertInstanceOf(Collection::class, $this->socialNetwork->getCustomerSocialIdentities());
        $this->assertInstanceOf(CustomerSocialIdentity::class, $this->socialNetwork->getCustomerSocialIdentities()->first());
        $this->assertInstanceOf(SocialNetwork::class, $this->socialNetwork->removeCustomerSocialIdentity($customerSocialIdentity));
        $this->assertEquals(0, $this->socialNetwork->getCustomerSocialIdentities()->count());
    }
}
