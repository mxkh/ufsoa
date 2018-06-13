<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Customer\Consumer;

use UmberFirm\Bundle\CommonBundle\Entity\Gender;
use UmberFirm\Bundle\CustomerBundle\DataObject\CustomerSocialDataObject;
use UmberFirm\Bundle\CustomerBundle\Entity\SocialNetwork;

/**
 * Class CustomerSocialDataObjectTest
 *
 * @package UmberFirm\Bundle\PublicBundle\Tests\Unit\Component\Customer\Consumer
 */
class CustomerSocialDataObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CustomerSocialDataObject
     */
    protected $customerSocialDataObject;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->customerSocialDataObject = new CustomerSocialDataObject();
    }

    public function testName()
    {
        $this->assertInstanceOf(CustomerSocialDataObject::class, $this->customerSocialDataObject->setFirstname('facebook'));
        $this->assertEquals('facebook', $this->customerSocialDataObject->getFirstname());
    }

    public function testLastName()
    {
        $this->assertInstanceOf(CustomerSocialDataObject::class, $this->customerSocialDataObject->setLastName('facebook'));
        $this->assertEquals('facebook', $this->customerSocialDataObject->getLastName());
    }

    public function testBirthday()
    {
        $this->assertInstanceOf(CustomerSocialDataObject::class, $this->customerSocialDataObject->setBirthday(new \DateTime));
        $this->assertInstanceOf(\DateTime::class, $this->customerSocialDataObject->getBirthday());
    }

    public function testGender()
    {
        $this->assertInstanceOf(CustomerSocialDataObject::class, $this->customerSocialDataObject->setGender(new Gender()));
        $this->assertInstanceOf(Gender::class, $this->customerSocialDataObject->getGender());
    }

    public function testPhone()
    {
        $this->assertInstanceOf(CustomerSocialDataObject::class, $this->customerSocialDataObject->setPhone('05012345678'));
        $this->assertEquals('05012345678', $this->customerSocialDataObject->getPhone());
    }

    public function testSocialId()
    {
        $this->assertInstanceOf(CustomerSocialDataObject::class, $this->customerSocialDataObject->setSocialId('05012345678'));
        $this->assertEquals('05012345678', $this->customerSocialDataObject->getSocialId());
    }

    public function testSocialNetwork()
    {
        $this->assertInstanceOf(CustomerSocialDataObject::class, $this->customerSocialDataObject->setSocialNetwork(new SocialNetwork()));
        $this->assertInstanceOf(SocialNetwork::class, $this->customerSocialDataObject->getSocialNetwork());
    }
}
