<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopCurrency;
use UmberFirm\Bundle\ShopBundle\Form\ShopCurrencyType;

/**
 * Class ShopCurrencyController
 *
 * @package UmberFirm\Bundle\ShopBundle\Controller
 *
 * @FOS\RouteResource("currency")
 * @FOS\NamePrefix("umberfirm__shop__")
 */
class ShopCurrencyController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of currencies by shop
     *
     * @ApiDoc(
     *      resource = true,
     *      statusCodes = {
     *          200 = "Returned when successful",
     *          404 = "Returned when the resource not found"
     *      }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopCurrency"})
     *
     * @param Shop $shop
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(Shop $shop): View
    {
        $shopCurrencyRepository = $this->getDoctrine()->getRepository(ShopCurrency::class);
        $currencies = $shopCurrencyRepository->findCurrenciesByShop($shop);

        return $this->view($currencies);
    }

    /**
     * Get specified ShopCurrency of shop
     *
     * @ApiDoc(
     *      resource = true,
     *      statusCodes = {
     *          200 = "Returned when successful",
     *          404 = "Returned when the resource not found"
     *      }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopCurrency"})
     *
     * @param Shop $shop
     * @param ShopCurrency $currency
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Shop $shop, ShopCurrency $currency): View
    {
        if ($currency->getShop()->getId()->toString() !== $shop->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        return $this->view($currency);
    }

    /**
     * Creates a new ShopCurrency from the submitted data.
     *
     * @param Request $request
     * @param Shop $shop
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ShopBundle\Form\ShopCurrencyType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopCurrency"})
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request, Shop $shop): View
    {
        $currency = new ShopCurrency();
        $currency->setShop($shop);

        $form = $this->createForm(ShopCurrencyType::class, $currency);
        $form->remove('shop');
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $event = $this->get('umber_firm_shop.event.defaultable_event_dispatcher')->create($currency);
            $this->get('event_dispatcher')->dispatch('umber_firm_shop.event.defaultable.on_create', $event);

            $em = $this->getDoctrine()->getManager();
            $em->persist($currency);
            $em->flush();

            return $this->view($currency, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing ShopCurrency from the submitted data
     *
     * @param Request $request
     * @param Shop $shop
     * @param ShopCurrency $currency
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ShopBundle\Form\ShopCurrencyType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopCurrency"})
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Shop $shop, ShopCurrency $currency): View
    {
        if ($currency->getShop()->getId()->toString() !== $shop->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(ShopCurrencyType::class, $currency);
        $form->remove('shop');
        $form->remove('currency');
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $event = $this->get('umber_firm_shop.event.defaultable_event_dispatcher')->create($currency);
            $this->get('event_dispatcher')->dispatch('umber_firm_shop.event.defaultable.on_default', $event);

            $em = $this->getDoctrine()->getManager();
            $em->persist($currency);
            $em->flush();

            return $this->view($currency);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Remove a ShopCurrency
     *
     * @param Shop $shop
     * @param ShopCurrency $currency
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
    public function deleteAction(Shop $shop, ShopCurrency $currency): View
    {
        if ($currency->getShop()->getId()->toString() !== $shop->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($currency);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__shop__get_shop_currencies',
            ['shop' => $shop->getId()->toString()],
            Response::HTTP_NO_CONTENT
        );
    }
}
