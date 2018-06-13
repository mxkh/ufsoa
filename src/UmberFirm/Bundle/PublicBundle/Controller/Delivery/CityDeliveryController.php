<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Delivery;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\CommonBundle\Entity\City;
use UmberFirm\Bundle\PublicBundle\Controller\BasePublicController;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDeliveryCity;

/**
 * Class CityDeliveryController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Delivery
 *
 * @FOS\RouteResource("delivery")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class CityDeliveryController extends BasePublicController implements ClassResourceInterface
{
    /**
     * Get list of delivery methods filtered by city and shop if any
     *
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when city not found"
     *     }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicShopDeliveryCity"})
     *
     * @param City $city
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(City $city): View
    {
        $repository = $this->getDoctrine()->getRepository(ShopDeliveryCity::class);
        $collection = $repository->findBy(
            [
                'shop' => $this->authenticatedShop(),
                'city' => $city,
            ]
        );

        return $this->view($collection);
    }
}
