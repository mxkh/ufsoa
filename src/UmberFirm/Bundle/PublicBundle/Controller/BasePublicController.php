<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class BasePublicController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller
 */
abstract class BasePublicController extends FOSRestController
{
    /**
     * @var Shop
     */
    protected $shop;

    /**
     * @var Customer
     */
    protected $customer;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        /** @var Shop $shop */
        $this->shop = $this->getUser();
        $this->customer = $this->shop->getCustomer();
    }

    /**
     * Returns currently authenticated shop
     *
     * @return Shop
     */
    protected function authenticatedShop(): Shop
    {
        return $this->shop;
    }

    /**
     * Returns currently authenticated customer
     *
     * @return null|Customer
     */
    protected function authenticatedCustomer(): ?Customer
    {
        return $this->customer;
    }
}
