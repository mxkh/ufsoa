<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Cart;

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
 * @package UmberFirm\Bundle\PublicBundle\Controller\Cart
 *
 * @FOS\RouteResource("item")
 * @FOS\NamePrefix("umberfirm__public__")
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
     *     name="offset",
     *     requirements="\d+",
     *     nullable=true,
     *     description="Offset from which to start listing items."
     * )
     * @FOS\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="10",
     *     description="How many items to return."
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicShoppingCartItem"})
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param ShoppingCart $shoppingCart
     *
     * @return View
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher, ShoppingCart $shoppingCart): View
    {
        if (true === $shoppingCart->isArchived()) {
            throw $this->createNotFoundException();
        }

        //TODO: validate $shoppingCart on customer and shop belonging
        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');

        $shoppingCartItemRepository = $this->getDoctrine()->getRepository(ShoppingCartItem::class);
        $shoppingCartItems = $shoppingCartItemRepository->findBy(
            [
                'shoppingCart' => $shoppingCart->getId()->toString(),
            ],
            null,
            $limit,
            $offset
        );

        return $this->view($shoppingCartItems);
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
     * @FOS\View(serializerGroups={"PublicService", "PublicShoppingCartItem"})
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
        //TODO: validate $shoppingCart on customer and shop belonging
        if ($shoppingCart->getId()->toString() !== $shoppingCartItem->getShoppingCart()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        if (true === $shoppingCart->isArchived()) {
            throw $this->createNotFoundException();
        }

        return $this->view($shoppingCartItem);
    }

    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Add item to cart, if item exist update it",
     *   input = "UmberFirm\Bundle\OrderBundle\Form\ShoppingCartItemType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicShoppingCartItem"})
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
        //TODO: validate $shoppingCart on customer and shop belonging
        if ($shoppingCart->getId()->toString() !== $shoppingCartItem->getShoppingCart()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        if (true === $shoppingCart->isArchived()) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($shoppingCartItem);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__public__get_cart_items',
            [
                'shoppingCart' => $shoppingCart->getId()->toString(),
            ],
            Response::HTTP_NO_CONTENT
        );
    }
}
