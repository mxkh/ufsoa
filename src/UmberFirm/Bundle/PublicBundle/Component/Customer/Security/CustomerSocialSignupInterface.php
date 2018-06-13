<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Component\Customer\Security;

use UmberFirm\Bundle\CustomerBundle\DataObject\CustomerSocialDataObject;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerSocialIdentity;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Interface CustomerSocialSignupInterface
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Customer
 */
interface CustomerSocialSignupInterface
{
    /**
     * @param CustomerSocialDataObject $socialDataObject
     *
     * @return null|CustomerSocialIdentity
     */
    public function loadSocialIdentity(CustomerSocialDataObject $socialDataObject): ?CustomerSocialIdentity;

    /**
     * @param CustomerSocialDataObject $socialObject
     * @param Shop $shop
     *
     * @return null|Customer
     */
    public function loadCustomer(CustomerSocialDataObject $socialObject, Shop $shop);

    /**
     * @param Customer $customer
     */
    public function login(Customer $customer): void;

    /**
     * @param CustomerSocialDataObject $socialObject
     * @param Shop $shop
     *
     * @return CustomerSocialIdentity
     */
    public function createCustomerSocialIdentity(
        CustomerSocialDataObject $socialObject,
        Shop $shop
    ): CustomerSocialIdentity;
}
