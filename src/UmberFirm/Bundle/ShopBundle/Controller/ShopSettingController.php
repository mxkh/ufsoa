<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopSettings;
use UmberFirm\Bundle\ShopBundle\Form\ShopSettingsType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @FOS\RouteResource("setting")
 * @FOS\NamePrefix("umberfirm__shop__")
 */
class ShopSettingController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of shop settings
     *
     * @ApiDoc(
     *      resource = true,
     *      statusCodes = {
     *          200 = "Returned when successful",
     *          404 = "Returned when object not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopSettings"})
     *
     * @param Shop $shop
     *
     * @throws NotFoundHttpException
     *
     * @return \FOS\RestBundle\View\View
     */
    public function cgetAction(Shop $shop): View
    {
        return $this->view($shop->getShopSettings());
    }

    /**
     * Get specified ShopSettings of shop
     *
     * @ApiDoc(
     *      resource = true,
     *      statusCodes = {
     *          200 = "Returned when successful",
     *          404 = "Returned when the resource not found"
     *      }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopSettings"})
     *
     * @param Shop $shop
     * @param ShopSettings $settings
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Shop $shop, ShopSettings $settings): View
    {
        if ($settings->getShop()->getId()->toString() !== $shop->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        return $this->view($settings);
    }

    /**
     * Creates a new Settings Shop from the submitted data.
     *
     * @param Request $request
     * @param Shop $shop
     *
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirmShopBundle\Form\ShopSettingsType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopSettings"})
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request, Shop $shop): View
    {
        $shopSettings = new ShopSettings();
        $shopSettings->setShop($shop);

        $form = $this->createForm(ShopSettingsType::class, $shopSettings);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shopSettings);
            $em->flush();

            return $this->view($shopSettings, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing shop settings from the submitted data
     *
     * @param Request $request
     * @param Shop $shop
     * @param ShopSettings $settings
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirmShopBundle\Form\ShopSettingsType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopSettings"})
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Shop $shop, ShopSettings $settings): View
    {
        if ($settings->getShop()->getId()->toString() !== $shop->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(ShopSettingsType::class, $settings);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($settings);
            $em->flush();

            return $this->view($settings);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Remove a Shop Settings by settings_id
     *
     * @param Shop $shop
     * @param ShopSettings $settings
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
    public function deleteAction(Shop $shop, ShopSettings $settings): View
    {
        if ($settings->getShop()->getId()->toString() !== $shop->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($settings);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__shop__get_shop_settings',
            ['shop' => $shop->getId()->toString()],
            Response::HTTP_NO_CONTENT
        );
    }
}
