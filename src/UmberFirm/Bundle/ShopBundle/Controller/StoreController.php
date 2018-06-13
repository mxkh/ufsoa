<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\Store;
use UmberFirm\Bundle\ShopBundle\Form\StoreType;

/**
 * Class ProductController
 *
 * @package UmberFirm\Bundle\ShopBundle\Controller
 * @FOS\RouteResource("store")
 * @FOS\NamePrefix("umberfirm__shop__")
 */
class StoreController extends FOSRestController
{
    /**
     * Get list of Stores
     *
     * @ApiDoc(
     *      resource = true,
     *      statusCodes = {
     *          200 = "Returned when successful"
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
     * @FOS\View(serializerGroups={"Default", "Store"})
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
            ->searchByQuery(Store::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified shopGroup
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Store"})
     *
     * @param Store $store
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Store $store)
    {
        return $this->view($store);
    }

    /**
     * Get Translations of Store
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "StoreTranslation"})
     *
     * @param Store $store
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getTranslationsAction(Store $store): View
    {
        return $this->view($store->getTranslations());
    }

    /**
     * Creates a new store from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ShopBundle\Form\StoreType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Store"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $store = new Store();

        $form = $this->createForm(StoreType::class, $store);
        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($store);
            $em->flush();

            return $this->view($store, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing store from the submitted data or create a new one
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ShopBundle\Form\StoreType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Store"})
     *
     * @param Request $request
     * @param Store $store
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Store $store): View
    {
        $form = $this->createForm(StoreType::class, $store);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($store);
            $em->flush();

            return $this->view($store);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes a Store.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param Store $store
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Store $store): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($store);
        $em->flush();

        return $this->routeRedirectView('umberfirm__shop__get_stores', [], Response::HTTP_NO_CONTENT);
    }

    /**
     * @FOS\View(serializerGroups={"Default", "Store"})
     *
     * @param string $reference
     *
     * @return View
     */
    public function getImportAction(string $reference)
    {
        $storeRepository = $this->getDoctrine()->getRepository(Store::class);
        $store = $storeRepository->findOneBy(['reference' => $reference]);
        if (null === $store) {
            return $this->view(null, Response::HTTP_NOT_FOUND);
        }

        return $this->view($store);
    }

    /**
     * @FOS\View(serializerGroups={"Default", "Store"})
     *
     * @param Request $request the request object
     * @param Shop $shop
     *
     * @return View
     */
    public function postImportAction(Request $request, Shop $shop): View
    {
        $reference = $request->get('reference');
        $storeRepository = $this->getDoctrine()->getRepository(Store::class);
        $store = $storeRepository->findOneBy(['reference' => $reference]);
        if (null !== $store) {
            $store->addShop($shop);

            return $this->view($store);
        }

        $store = new Store();
        $store->addShop($shop);
        $form = $this->createForm(StoreType::class, $store);
        $form->remove('shops');
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($store);
            $em->flush();

            return $this->view($store, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }
}
