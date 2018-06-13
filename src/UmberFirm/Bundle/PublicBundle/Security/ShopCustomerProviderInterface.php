<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Security;

use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class PreAuthenticationTokenFactory
 *
 * @package UmberFirm\Bundle\PublicBundle\Security
 */
interface ShopCustomerProviderInterface extends UserProviderInterface
{
    /**
     * @param string $token
     *
     * @return null|Customer
     */
    public function loadCustomerByToken(string $token): ?Customer;

    /**
     * @param string $token
     *
     * @return null|Shop
     */
    public function loadShopByToken(string $token): ?Shop;
}
