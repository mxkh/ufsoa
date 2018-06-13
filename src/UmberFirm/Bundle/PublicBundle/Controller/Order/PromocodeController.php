<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Order;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\Promocode;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class PromocodeController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Order
 *
 * @FOS\RouteResource("promocode")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class PromocodeController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicPromocode"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postVerificationAction(Request $request): View
    {
        /** @var Shop $shop */
        $shop = $this->getUser();
        /** @var Customer $customer */
        $customer = $shop->getCustomer();

        $promocodeRepository = $this->getDoctrine()->getRepository(Promocode::class);
        $promocode = $promocodeRepository->findOneByCode((string) $request->get('promocode'));

        if (null === $promocode) {
            return $this->view(null, Response::HTTP_BAD_REQUEST);
        }

        $verificationManager = $this->get('umberfirm.public.component.order.manager.promocode_manager');
        if (false === $verificationManager->verify($promocode, $customer)) {
            return $this->view(null, Response::HTTP_BAD_REQUEST);
        }

        return $this->view($promocode);
    }
}
