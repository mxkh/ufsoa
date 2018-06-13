<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Cart;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Bundle\OrderBundle\Form\ShoppingCartType;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class ShoppingCartController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Cart
 *
 * @FOS\RouteResource("cart")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class ShoppingCartController extends FOSRestController implements ClassResourceInterface
{
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
     * @FOS\View(serializerGroups={"PublicService", "PublicShoppingCart"})
     *
     * @param ShoppingCart $shoppingCart
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(ShoppingCart $shoppingCart): View
    {
        //TODO: validate $shoppingCart on customer and shop belonging
        if (true === $shoppingCart->isArchived()) {
            throw $this->createNotFoundException();
        }

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
     * @FOS\View(serializerGroups={"PublicService", "PublicShoppingCart"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        /** @var Shop $shop */
        $shop = $this->getUser();
        /** @var Customer $customer */
        $customer = $shop->getCustomer();

        $shoppingCart = new ShoppingCart();
        $shoppingCart->setCustomer($customer);
        $shoppingCart->setShop($shop);

        $form = $this->createForm(ShoppingCartType::class, $shoppingCart);
        $form->remove('shop');
        $form->remove('customer');
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
     * @FOS\View(serializerGroups={"PublicService", "PublicShoppingCart"})
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
        if (true === $shoppingCart->isArchived()) {
            throw $this->createNotFoundException();
        }

        //TODO: validate $shoppingCart on customer and shop belonging
        $form = $this->createForm(ShoppingCartType::class, $shoppingCart);
        $form->remove('shop');
        $form->remove('customer');
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
}
