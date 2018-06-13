<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use Symfony\Component\HttpFoundation\Request;
use UmberFirm\Bundle\ShopBundle\Form\ShopType;

/**
 * Class ShopController
 *
 * @package UmberFirm\Bundle\ShopBundle\Controller
 *
 * @FOS\RouteResource("shop")
 * @FOS\NamePrefix("umberfirm__shop__")
 */
class ShopController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of Shop
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
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
     * @FOS\View(serializerGroups={"Default", "Shop"})
     *
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return View
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher): View
    {
        $limit = (int) $paramFetcher->get('limit');
        $searchQuery = $paramFetcher->get('q');
        $currentPage = (int) ($paramFetcher->get('page') ?? 1);

        $pagenator = $this->get('umberfirm.component.pagenator_factory');
        $representation= $pagenator
            ->searchByQuery(Shop::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified shop
     *
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *       200 = "Returned when successful",
     *       404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Shop"})
     *
     * @param Shop $shop
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Shop $shop): View
    {
        return $this->view($shop);
    }

    /**
     * Creates a new Shop from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirmShopBundle\Form\ShopType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Shop"})
     *
     * @param Request $request
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $shop = new Shop();

        $form = $this->createForm(ShopType::class, $shop);
        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shop);
            $em->flush();

            return $this->view($shop, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing shop from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirmShopBundle\Form\ShopType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Shop"})
     *
     * @param Request $request
     * @param Shop $shop
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Shop $shop): View
    {
        $form = $this->createForm(ShopType::class, $shop);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shop);
            $em->flush();

            return $this->view($shop);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Remove a Shop
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param Shop $shop
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Shop $shop): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($shop);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__shop__get_shops',
            [],
            Response::HTTP_NO_CONTENT
        );
    }
}
