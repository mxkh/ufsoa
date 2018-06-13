<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Customer\Security;

use Doctrine\ORM\EntityManager;
use UmberFirm\Bundle\CustomerBundle\DataObject\CustomerSocialDataObject;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerProfile;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerSocialIdentity;
use UmberFirm\Bundle\PublicBundle\Component\Customer\Manager\Login\CustomerLoginManagerInterface;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class CustomerSocialSignup
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Customer
 */
class CustomerSocialSignup implements CustomerSocialSignupInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var CustomerLoginManagerInterface
     */
    private $loginManager;

    /**
     * CustomerSocialSignup constructor.
     *
     * @param EntityManager $em
     * @param CustomerLoginManagerInterface $loginManager
     */
    public function __construct(EntityManager $em, CustomerLoginManagerInterface $loginManager)
    {
        $this->em = $em;
        $this->loginManager = $loginManager;
    }

    /**
     * {@inheritdoc}
     */
    public function loadSocialIdentity(CustomerSocialDataObject $socialDataObject): ?CustomerSocialIdentity
    {
        $repository = $this->em->getRepository(CustomerSocialIdentity::class);

        return $repository->findSocialIdentity($socialDataObject->getSocialNetwork(), $socialDataObject->getSocialId());
    }

    /**
     * {@inheritdoc}
     */
    public function loadCustomer(CustomerSocialDataObject $socialObject, Shop $shop)
    {
        $socialIdentity = $this->loadSocialIdentity($socialObject);
        $customer = $this->loginManager->loadCustomer($socialObject->getPhone(), $socialObject->getEmail(), $shop);

        if (null === $socialIdentity || null === $customer) {
            return null;
        }

        if ($customer === $socialIdentity->getCustomer()) {
            return $customer;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function login(Customer $customer): void
    {
        $this->loginManager->login($customer);
    }

    /**
     * {@inheritdoc}
     */
    public function createCustomerSocialIdentity(
        CustomerSocialDataObject $socialObject,
        Shop $shop
    ): CustomerSocialIdentity {
        $customer = $this->loginManager->loadCustomer($socialObject->getPhone(), $socialObject->getEmail(), $shop);

        if (null === $customer) {
            $customer = new Customer();
            $customer->setShop($shop);
            $customer->setIsConfirmed(true);
            $customer->setProfile(new CustomerProfile());
        }
        $profile = $customer->getProfile();

        //Fill customer data from social object data if some are empty
        $customer->setEmail($this->chooseFilled($customer->getEmail(), $socialObject->getEmail()));
        $customer->setPhone($this->chooseFilled($customer->getPhone(), $socialObject->getPhone()));
        $profile->setFirstname($this->chooseFilled($profile->getFirstname(), $socialObject->getFirstname()));
        $profile->setLastname($this->chooseFilled($profile->getLastname(), $socialObject->getLastname()));
        $profile->setBirthday($this->chooseFilled($profile->getBirthday(), $socialObject->getBirthday()));
        $profile->setGender($this->chooseFilled($profile->getGender(), $socialObject->getGender()));
        $profile->setGender($socialObject->getGender());

        // create CustomerSocialIdentity
        $customerSocialIdentity = new CustomerSocialIdentity();
        $customerSocialIdentity->setSocialNetwork($socialObject->getSocialNetwork());
        $customerSocialIdentity->setSocialId($socialObject->getSocialId());
        $customerSocialIdentity->setCustomer($customer);

        $this->em->persist($profile);
        $this->em->persist($customerSocialIdentity);
        $this->em->persist($customer);
        $this->em->flush();

        return $customerSocialIdentity;
    }

    /**
     * Chooses filled value, and $first is most priority
     *
     * @param mixed $first
     * @param mixed $second
     *
     * @return mixed
     */
    private function chooseFilled($first, $second)
    {
        return (true === empty($first)) ? $second : $first;
    }
}
