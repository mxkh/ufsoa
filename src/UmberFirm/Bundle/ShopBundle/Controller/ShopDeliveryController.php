<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopDelivery;
use UmberFirm\Bundle\ShopBundle\Form\ShopDeliveryType;

/**
 * Class ShopDeliveryController
 *
 * @package UmberFirm\Bundle\DeliveryBundle\Controller
 *
 * @FOS\RouteResource("delivery")
 * @FOS\NamePrefix("umberfirm__shop__")
 */
class ShopDeliveryController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of delivery by shop
     *
     * @ApiDoc(
     *      resource = true,
     *      statusCodes = {
     *          200 = "Returned when successful",
     *          404 = "Returned when object not found"
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
     * @FOS\QueryParam(
     *     name="q",
     *     nullable=true,
     *     description="search query string"
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopDelivery"})
     *
     * @param Shop $shop
     * @param ParamFetcherInterface $paramFetcher
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher, Shop $shop): View
    {
        $limit = (int) $paramFetcher->get('limit');
        $searchQuery = $paramFetcher->get('q');
        $currentPage = (int) ($paramFetcher->get('page') ?? 1);

        $pagenator = $this->get('umberfirm.component.pagenator_factory');
        $pagenator->searchByQuery(ShopDelivery::class, $searchQuery)
            ->getQueryBuilder()
            ->andWhere('shop_delivery.shop = :shop')
            ->setParameter('shop', $shop);

        $representation = $pagenator->getRepresentation(
            $limit,
            $currentPage,
            [
                'shop' => $shop->getId()->toString(),
            ]
        );

        return $this->view($representation);
    }

    /**
     * Get specified ShopDelivery of shop
     *
     * @ApiDoc(
     *      resource = true,
     *      statusCodes = {
     *          200 = "Returned when successful",
     *          404 = "Returned when the resource not found"
     *      }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopDelivery"})
     *
     * @param Shop $shop
     * @param ShopDelivery $delivery
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Shop $shop, ShopDelivery $delivery): View
    {
        if ($delivery->getShop()->getId()->toString() !== $shop->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        return $this->view($delivery);
    }

    /**
     * Creates a new ShopDelivery from the submitted data.
     *
     * @param Request $request
     * @param Shop $shop
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ShopBundle\Form\ShopDeliveryType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopDelivery"})
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request, Shop $shop): View
    {
        $shopDelivery = new ShopDelivery();
        $shopDelivery->setShop($shop);

        $form = $this->createForm(ShopDeliveryType::class, $shopDelivery);
        $data = json_decode($request->getContent(), true);
        $form->remove('shop');
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shopDelivery);
            $em->flush();

            return $this->view($shopDelivery, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Remove a Shop Delivery
     *
     * @param Shop $shop
     * @param ShopDelivery $delivery
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Shop $shop, ShopDelivery $delivery): View
    {
        if ($delivery->getShop()->getId()->toString() !== $shop->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($delivery);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__shop__get_shop_deliveries',
            [
                'shop' => $shop->getId()->toString(),
            ],
            Response::HTTP_NO_CONTENT
        );
    }
}
