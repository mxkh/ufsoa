<?php

declare(strict_types = 1);

namespace UmberFirm\Bundle\ShopBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDeliveryCity;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDeliveryCityPayment;
use UmberFirm\Bundle\ShopBundle\Form\ShopDeliveryCityPaymentType;

/**
 * Class ShopDeliveryCityPaymentController
 *
 * @package UmberFirm\Bundle\ShopBundle\Controller
 *
 * @FOS\NamePrefix("umberfirm__shop__")
 */
class ShopDeliveryCityPaymentController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of payments in shop delivery cities
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="10",
     *     description="How many items to return."
     * )
     * @FOS\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     nullable=true
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopDeliveryCityPayment"})
     *
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     * @param ShopDeliveryCity $shopDeliveryCity
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher, ShopDeliveryCity $shopDeliveryCity): View
    {
        $limit = (int) $paramFetcher->get('limit');
        $currentPage = (int) ($paramFetcher->get('page') ?? 1);

        $pagenator = $this->get('umberfirm.component.pagenator_factory');
        $pagenator->searchByQuery(ShopDeliveryCityPayment::class, null);
        $pagenator->getQueryBuilder()
            ->andWhere('shop_delivery_city_payment.shopDeliveryCity = :shopDeliveryCity')
            ->setParameter('shopDeliveryCity', $shopDeliveryCity);
        $representation = $pagenator->getRepresentation(
            $limit,
            $currentPage,
            [
                'shopDeliveryCity' => $shopDeliveryCity->getId()->toString(),
            ]
        );

        return $this->view($representation);
    }

    /**
     * Creates a new shop delivery city payment from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ShopBundle\Form\ShopDeliveryCityPaymentType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopDeliveryCityPayment"})
     *
     * @param Request $request
     * @param ShopDeliveryCity $shopDeliveryCity
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request, ShopDeliveryCity $shopDeliveryCity): View
    {
        $shopDeliveryCityPayment = new ShopDeliveryCityPayment();
        $shopDeliveryCityPayment->setShopDeliveryCity($shopDeliveryCity);
        $shopDeliveryCityPayment->setShop($shopDeliveryCity->getShop());

        $form = $this->createForm(ShopDeliveryCityPaymentType::class, $shopDeliveryCityPayment);
        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shopDeliveryCityPayment);
            $em->flush();

            return $this->view($shopDeliveryCityPayment, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes a payment in delivery city shop.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param ShopDeliveryCity $shopDeliveryCity
     * @param ShopDeliveryCityPayment $shopDeliveryCityPayment
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(
        ShopDeliveryCity $shopDeliveryCity,
        ShopDeliveryCityPayment $shopDeliveryCityPayment
    ): View {
        $em = $this->getDoctrine()->getManager();
        $em->remove($shopDeliveryCityPayment);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__shop__get_shopdeliverycity_payments',
            [
                'shopDeliveryCity' => $shopDeliveryCity->getId()->toString(),
            ],
            Response::HTTP_NO_CONTENT
        );
    }
}
