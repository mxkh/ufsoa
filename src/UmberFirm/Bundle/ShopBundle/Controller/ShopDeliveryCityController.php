<?php

declare(strict_types=1);

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
use UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDeliveryCity;
use UmberFirm\Bundle\ShopBundle\Form\ShopDeliveryCityType;

/**
 * Class ShopDeliveryCityController
 *
 * @package UmberFirm\Bundle\ShopBundle\Controller
 *
 * @FOS\NamePrefix("umberfirm__shop__")
 */
class ShopDeliveryCityController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of delivery cities in shop
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
     * @FOS\View(serializerGroups={"Default", "ShopDeliveryCity"})
     *
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     * @param ShopDelivery $shopDelivery
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher, ShopDelivery $shopDelivery): View
    {
        $limit = (int) $paramFetcher->get('limit');
        $currentPage = (int) ($paramFetcher->get('page') ?? 1);

        $pagenator = $this->get('umberfirm.component.pagenator_factory');
        $pagenator->searchByQuery(ShopDeliveryCity::class, null);
        $pagenator->getQueryBuilder()
            ->andWhere('shop_delivery_city.shopDelivery = :shopDelivery')
            ->setParameter('shopDelivery', $shopDelivery);
        $representation = $pagenator->getRepresentation(
            $limit,
            $currentPage,
            [
                'shopDelivery' => $shopDelivery->getId()->toString(),
            ]
        );

        return $this->view($representation);
    }

    /**
     * Creates a new shop delivery city from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ShopBundle\Form\ShopDeliveryCityType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopDeliveryCity"})
     *
     * @param Request $request
     * @param ShopDelivery $shopDelivery
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request, ShopDelivery $shopDelivery): View
    {
        $shopDeliveryCity = new ShopDeliveryCity();
        $shopDeliveryCity->setShopDelivery($shopDelivery);
        $shopDeliveryCity->setShop($shopDelivery->getShop());

        $form = $this->createForm(ShopDeliveryCityType::class, $shopDeliveryCity);
        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shopDeliveryCity);
            $em->flush();

            return $this->view($shopDeliveryCity, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes a delivery city in shop.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param ShopDelivery $shopDelivery
     * @param ShopDeliveryCity $shopDeliveryCity
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(ShopDelivery $shopDelivery, ShopDeliveryCity $shopDeliveryCity): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($shopDeliveryCity);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__shop__get_shopdelivery_cities',
            [
                'shopDelivery' => $shopDelivery->getId()->toString(),
            ],
            Response::HTTP_NO_CONTENT
        );
    }
}
