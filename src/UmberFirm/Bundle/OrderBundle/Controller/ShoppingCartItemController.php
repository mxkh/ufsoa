<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCartItem;
use UmberFirm\Bundle\OrderBundle\Form\ShoppingCartItemType;
use FOS\RestBundle\View\View;

/**
 * Class ShoppingCartItemController
 *
 * @package UmberFirm\Bundle\OrderBundle\Controller
 *
 * @FOS\RouteResource("item")
 * @FOS\NamePrefix("umberfirm__order__")
 */
class ShoppingCartItemController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of shopping cart items
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
     * @FOS\View(serializerGroups={"Default", "ShoppingCartItem"})
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param ShoppingCart $shoppingCart
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher, ShoppingCart $shoppingCart): View
    {
        $limit = (int) $paramFetcher->get('limit');
        $searchQuery = $paramFetcher->get('q');
        $currentPage = (int) ($paramFetcher->get('page') ?? 1);

        $pagenator = $this->get('umberfirm.component.pagenator_factory');
        $pagenator->searchByQuery(ShoppingCartItem::class, $searchQuery);

        $pagenator->getQueryBuilder()
            ->andWhere('shopping_cart_item.shoppingCart = :shoppingCart')
            ->setParameter('shoppingCart', $shoppingCart);

        $representation = $pagenator->getRepresentation(
            $limit,
            $currentPage,
            [
                'shoppingCart' => $shoppingCart->getId()->toString(),
            ]
        );

        return $this->view($representation);
    }

    /**
     * Get specified shopping cart item.
     *
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *       200 = "Returned when successful",
     *       404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShoppingCartItem"})
     *
     * @param ShoppingCart $shoppingCart
     * @param ShoppingCartItem $shoppingCartItem
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(ShoppingCart $shoppingCart, ShoppingCartItem $shoppingCartItem): View
    {
        if ($shoppingCart->getId()->toString() !== $shoppingCartItem->getShoppingCart()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        return $this->view($shoppingCartItem);
    }

    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\OrderBundle\Form\ShoppingCartItemType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShoppingCartItem"})
     *
     * @param Request $request the request object
     * @param ShoppingCart $shoppingCart
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request, ShoppingCart $shoppingCart): View
    {
        if (true === $shoppingCart->isArchived()) {
            throw $this->createNotFoundException();
        }

        $shoppingCartItem = new ShoppingCartItem();
        $shoppingCartItem->setShoppingCart($shoppingCart);
        $form = $this->createForm(ShoppingCartItemType::class, $shoppingCartItem);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $shoppingCartItemManager = $this->get('umberfirm.order.component.manager.shopping_cart_item_manager');
            $shoppingCartItem = $shoppingCartItemManager->manage($shoppingCartItem);

            return $this->view($shoppingCartItem, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes an item.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param ShoppingCart $shoppingCart
     * @param ShoppingCartItem $shoppingCartItem
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(ShoppingCart $shoppingCart, ShoppingCartItem $shoppingCartItem): View
    {
        if ($shoppingCart->getId()->toString() !== $shoppingCartItem->getShoppingCart()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($shoppingCartItem);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__order__get_shopping-cart_items',
            [
                'shoppingCart' => $shoppingCart->getId()->toString(),
            ],
            Response::HTTP_NO_CONTENT
        );
    }
}
