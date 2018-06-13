<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPaymentSettings;
use UmberFirm\Bundle\ShopBundle\Form\ShopPaymentType;

/**
 * Class ShopPaymentController
 *
 * @package UmberFirm\Bundle\ShopBundle\Controller
 *
 * @FOS\RouteResource("payment")
 * @FOS\NamePrefix("umberfirm__shop__")
 */
class ShopPaymentController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of currencies by shop
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
     * @FOS\QueryParam(
     *     name="q",
     *     nullable=true,
     *     description="search query string"
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopPayment"})
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param Shop $shop
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
        $pagenator->searchByQuery(ShopPayment::class, $searchQuery)
            ->getQueryBuilder()
            ->andWhere('shop_payment.shop = :shop')
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
     * Get specified ShopPayment of shop
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopPayment"})
     *
     * @param Shop $shop
     * @param ShopPayment $shopPayment
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Shop $shop, ShopPayment $shopPayment): View
    {
        if ($shopPayment->getShop()->getId()->toString() !== $shop->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        return $this->view($shopPayment);
    }

    /**
     * Creates a new ShopPayment from the submitted data.
     *
     * @param Request $request
     * @param Shop $shop
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ShopBundle\Form\ShopPaymentType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopPayment"})
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request, Shop $shop): View
    {
        $shopPayment = new ShopPayment();
        $shopPayment->setShop($shop);
        $shopPayment->setSettings(new ShopPaymentSettings());

        $form = $this->createForm(ShopPaymentType::class, $shopPayment);
        $form->remove('shop');
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shopPayment);
            $em->flush();

            return $this->view($shopPayment, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing ShopPayment from the submitted data
     *
     * @param Request $request
     * @param Shop $shop
     * @param ShopPayment $shopPayment
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ShopBundle\Form\ShopPaymentType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopPayment"})
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Shop $shop, ShopPayment $shopPayment): View
    {
        if ($shopPayment->getShop()->getId()->toString() !== $shop->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(ShopPaymentType::class, $shopPayment);
        $form->remove('shop');
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shopPayment);
            $em->flush();

            return $this->view($shopPayment);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Remove a ShopPayment
     *
     * @param Shop $shop
     * @param ShopPayment $shopPayment
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
    public function deleteAction(Shop $shop, ShopPayment $shopPayment): View
    {
        if ($shopPayment->getShop()->getId()->toString() !== $shop->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($shopPayment);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__shop__get_shop_payments',
            ['shop' => $shop->getId()->toString()],
            Response::HTTP_NO_CONTENT
        );
    }
}
