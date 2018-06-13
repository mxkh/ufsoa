<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Tests\Unit\Entity;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerSocialIdentity;
use UmberFirm\Bundle\CustomerBundle\Entity\SocialNetwork;

/**
 * Class CustomerSocialIdentityTest
 *
 * @package UmberFirm\Bundle\CustomerBundle\Tests\Unit
 */
class CustomerSocialIdentityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CustomerSocialIdentity
     */
    private $customerSocialIdentity;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->customerSocialIdentity = new CustomerSocialIdentity();
    }

    public function testCustomer()
    {
        $this->assertInstanceOf(CustomerSocialIdentity::class, $this->customerSocialIdentity->setCustomer(new Customer));
        $this->assertInstanceOf(Customer::class, $this->customerSocialIdentity->getCustomer());
    }

    public function testSocialNetwork()
    {
        $this->assertInstanceOf(CustomerSocialIdentity::class, $this->customerSocialIdentity->setSocialNetwork(new SocialNetwork()));
        $this->assertInstanceOf(SocialNetwork::class, $this->customerSocialIdentity->getSocialNetwork());
    }
}
