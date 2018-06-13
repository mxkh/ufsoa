<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Payment;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\PublicBundle\Controller\BasePublicController;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;
use UmberFirm\Bundle\ShopBundle\Repository\ShopPaymentRepository;
use FOS\RestBundle\Controller\Annotations as FOS;

/**
 * Class PaymentController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Payment
 *
 * @FOS\RouteResource("payment")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class PaymentController extends BasePublicController implements ClassResourceInterface
{
    /**
     * Get list of payments.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicShopPayment"})
     *
     * @return View
     */
    public function cgetAction(): View
    {
        $repository = $this->getDoctrine()->getRepository(ShopPayment::class);
        $collection = $repository->findBy(['shop' => $this->authenticatedShop()]);

        return $this->view($collection);
    }
}
