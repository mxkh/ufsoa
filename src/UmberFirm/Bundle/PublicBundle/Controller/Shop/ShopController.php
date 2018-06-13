<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Shop;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class DefaultController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Shop
 *
 * @FOS\RouteResource("shop")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class ShopController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @param Shop $shop
     *
     * @return View
     */
    public function getAction(Shop $shop): View
    {
        return $this->view(['status' => true]);
    }
}
