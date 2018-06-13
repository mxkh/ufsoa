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
use UmberFirm\Bundle\ShopBundle\Entity\ShopLanguage;
use UmberFirm\Bundle\ShopBundle\Form\ShopLanguageType;

/**
 * Class ShopLanguageController
 *
 * @package UmberFirm\Bundle\ShopBundle\Controller
 *
 * @FOS\RouteResource("language")
 * @FOS\NamePrefix("umberfirm__shop__")
 */
class ShopLanguageController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of languages by shop
     *
     * @ApiDoc(
     *      resource = true,
     *      statusCodes = {
     *          200 = "Returned when successful",
     *          404 = "Returned when object not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopLanguage"})
     *
     * @param Shop $shop
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(Shop $shop): View
    {
        $shopLanguageRepository = $this->getDoctrine()->getRepository(ShopLanguage::class);
        $languages = $shopLanguageRepository->findLanguages($shop);

        return $this->view($languages);
    }

    /**
     * Get specified ShopLanguage of shop
     *
     * @ApiDoc(
     *      resource = true,
     *      statusCodes = {
     *          200 = "Returned when successful",
     *          404 = "Returned when the resource not found"
     *      }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopLanguage"})
     *
     * @param Shop $shop
     * @param ShopLanguage $language
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Shop $shop, ShopLanguage $language): View
    {
        if ($language->getShop()->getId()->toString() !== $shop->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        return $this->view($language);
    }

    /**
     * Creates a new ShopLanguage from the submitted data.
     *
     * @param Request $request
     * @param Shop $shop
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ShopBundle\Form\ShopLanguageType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopLanguage"})
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request, Shop $shop): View
    {
        $language = new ShopLanguage();
        $language->setShop($shop);

        $form = $this->createForm(ShopLanguageType::class, $language);
        $form->remove('shop');
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $event = $this->get('umber_firm_shop.event.defaultable_event_dispatcher')->create($language);
            $this->get('event_dispatcher')->dispatch('umber_firm_shop.event.defaultable.on_create', $event);

            $em = $this->getDoctrine()->getManager();
            $em->persist($language);
            $em->flush();

            return $this->view($language, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing shop language from the submitted data
     *
     * @param Request $request
     * @param Shop $shop
     * @param ShopLanguage $language
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ShopBundle\Form\ShopLanguageType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ShopLanguage"})
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Shop $shop, ShopLanguage $language): View
    {
        if ($language->getShop()->getId()->toString() !== $shop->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(ShopLanguageType::class, $language);
        $form->remove('shop');
        $form->remove('language');
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $event = $this->get('umber_firm_shop.event.defaultable_event_dispatcher')->create($language);
            $this->get('event_dispatcher')->dispatch('umber_firm_shop.event.defaultable.on_default', $event);

            $em = $this->getDoctrine()->getManager();
            $em->persist($language);
            $em->flush();

            return $this->view($language);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Remove a Shop Language
     *
     * @param Shop $shop
     * @param ShopLanguage $language
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
    public function deleteAction(Shop $shop, ShopLanguage $language): View
    {
        if ($language->getShop()->getId()->toString() !== $shop->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($language);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__shop__get_shop_languages',
            ['shop' => $shop->getId()->toString()],
            Response::HTTP_NO_CONTENT
        );
    }
}
