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
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;
use UmberFirm\Bundle\ShopBundle\Form\ShopPaymentSettingsType;

/**
 * Class ShopPaymentController
 *
 * @package UmberFirm\Bundle\ShopBundle\Controller
 *
 * @FOS\RouteResource("shop-payment_settings")
 * @FOS\NamePrefix("umberfirm__shop__")
 */
class ShopPaymentSettingsController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get specified ShopPaymentSettings of ShopPayment
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopPaymentSettings"})
     *
     * @param ShopPayment $shopPayment
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(ShopPayment $shopPayment): View
    {
        return $this->view($shopPayment->getSettings());
    }

    /**
     * Update existing ShopPayment from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ShopBundle\Form\ShopPaymentSettingsType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopPaymentSettings"})
     *
     * @param Request $request
     * @param ShopPayment $shopPayment
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, ShopPayment $shopPayment): View
    {
        $settings = $shopPayment->getSettings();

        $form = $this->createForm(ShopPaymentSettingsType::class, $settings);
        $form->remove('shopPayment');
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($settings);
            $em->flush();

            return $this->view($settings);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }
}
