<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Delivery;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use UmberFirm\Bundle\PublicBundle\Controller\BasePublicController;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery;

/**
 * Class DeliveryController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Delivery
 *
 * @FOS\RouteResource("delivery")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class DeliveryController extends BasePublicController implements ClassResourceInterface
{
    /**
     * Get list of delivery methods filtered by shop if any
     *
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         200 = "Returned when successful"
     *     }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicShopDelivery"})
     *
     * @return View
     */
    public function cgetAction(): View
    {
        $repository = $this->getDoctrine()->getRepository(ShopDelivery::class);
        $collection = $repository->findBy(['shop' => $this->authenticatedShop()]);

        return $this->view($collection);
    }
}
