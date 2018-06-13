<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Bundle\OrderBundle\Form\ShoppingCartType;

/**
 * Class ShoppingCartController
 *
 * @package UmberFirm\Bundle\OrderBundle\Controller
 *
 * @FOS\RouteResource("shopping-cart")
 * @FOS\NamePrefix("umberfirm__order__")
 */
class ShoppingCartController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of shopping carts
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
     * @FOS\View(serializerGroups={"Default", "ShoppingCart"})
     *
     * @param ParamFetcherInterface $paramFetcher
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
            ->searchByQuery(ShoppingCart::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified ShoppingCart
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShoppingCart"})
     *
     * @param ShoppingCart $shoppingCart
     *
     * @return View
     */
    public function getAction(ShoppingCart $shoppingCart): View
    {
        return $this->view($shoppingCart);
    }

    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\OrderBundle\Form\ShoppingCartType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShoppingCart"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $shoppingCart = new ShoppingCart();
        $form = $this->createForm(ShoppingCartType::class, $shoppingCart);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shoppingCart);
            $em->flush();

            return $this->view($shoppingCart, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing item from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\OrderBundle\Form\ShoppingCartType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShoppingCart"})
     *
     * @param Request $request the request object
     * @param ShoppingCart $shoppingCart
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, ShoppingCart $shoppingCart): View
    {
        $form = $this->createForm(ShoppingCartType::class, $shoppingCart);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shoppingCart);
            $em->flush();

            return $this->view($shoppingCart);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes an item.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param ShoppingCart $shoppingCart
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(ShoppingCart $shoppingCart): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($shoppingCart);
        $em->flush();

        return $this->routeRedirectView('umberfirm__order__get_shopping-carts', [], Response::HTTP_NO_CONTENT);
    }
}
