<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Payment;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\PublicBundle\Controller\BasePublicController;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDeliveryCity;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDeliveryCityPayment;

/**
 * Class ShopDeliveryCityPaymentController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Payment
 *
 * @FOS\NamePrefix("umberfirm__public__")
 */
class ShopDeliveryCityPaymentController extends BasePublicController implements ClassResourceInterface
{
    /**
     * Get list of payment methods filtered by shopDeliveryCity if any
     *
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when city not found"
     *     }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicShopDeliveryCityPayment"})
     *
     * @param ShopDeliveryCity $shopDeliveryCity
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(ShopDeliveryCity $shopDeliveryCity): View
    {
        $repository = $this->getDoctrine()->getRepository(ShopDeliveryCityPayment::class);
        $collection = $repository->findBy(
            [
                'shopDeliveryCity' => $shopDeliveryCity,
                'shop' => $this->authenticatedShop(),
            ]
        );

        return $this->view($collection);
    }
}
