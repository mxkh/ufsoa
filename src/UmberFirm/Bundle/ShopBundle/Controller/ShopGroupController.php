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
use UmberFirm\Bundle\ShopBundle\Entity\ShopGroup;
use UmberFirm\Bundle\ShopBundle\Form\ShopGroupType;

/**
 * Class ShopGroupController
 *
 * @package UmberFirm\Bundle\ShopBundle\Controller
 *
 * @FOS\RouteResource("shop-group")
 * @FOS\NamePrefix("umberfirm__shop__")
 */
class ShopGroupController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of shopGroup
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
     * @FOS\View(serializerGroups={"Default", "ShopGroup"})
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
            ->searchByQuery(ShopGroup::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified shopGroup
     *
     * @ApiDoc(
     *      resource = true,
     *      statusCodes = {
     *          200 = "Returned when successful",
     *          404 = "Returned when the resource not found"
     *      }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopGroup"})
     *
     * @param ShopGroup $shopGroup
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(ShopGroup $shopGroup): View
    {
        return $this->view($shopGroup);
    }

    /**
     * Creates a new shopGroup from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ShopBundle\Form\ShopGroupType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopGroup"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $shopGroup = new ShopGroup();
        $form = $this->createForm(ShopGroupType::class, $shopGroup);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shopGroup);
            $em->flush();

            return $this->view($shopGroup, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing shop group from the submitted data or create a new one
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ShopBundle\Form\ShopGroupType",
     *   statusCodes = {
     *     200 = "Returned when a new resource is updated",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopGroup"})
     *
     * @param Request $request the request object
     * @param ShopGroup $shopGroup
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, ShopGroup $shopGroup): View
    {
        $form = $this->createForm(ShopGroupType::class, $shopGroup);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shopGroup);
            $em->flush();

            return $this->view($shopGroup);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes a Shop Group.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param ShopGroup $shopGroup
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(ShopGroup $shopGroup): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($shopGroup);
        $em->flush();

        return $this->routeRedirectView('umberfirm__shop__get_shop-groups', [], Response::HTTP_NO_CONTENT);
    }
}
