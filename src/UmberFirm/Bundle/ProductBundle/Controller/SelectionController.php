<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Entity\Selection;
use UmberFirm\Bundle\ProductBundle\Entity\SelectionItem;
use UmberFirm\Bundle\ProductBundle\Form\SelectionItemType;
use UmberFirm\Bundle\ProductBundle\Form\SelectionType;

/**
 * Class SelectionController
 *
 * @package UmberFirm\Bundle\ProductBundle\Controller
 *
 * @FOS\RouteResource("selection")
 * @FOS\NamePrefix("umberfirm__product__")
 */
class SelectionController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of Selections
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
     * @FOS\View(serializerGroups={"Default", "Selections"})
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
            ->searchByQuery(Selection::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified Selection
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Selection"})
     *
     * @param Selection $selection
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Selection $selection): View
    {
        return $this->view($selection);
    }

    /**
     * Get Translations of Selection
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "SelectionTranslation"})
     *
     * @param Selection $selection
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getTranslationsAction(Selection $selection): View
    {
        return $this->view($selection->getTranslations());
    }

    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ProductBundle\Form\SelectionType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Selection"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $selection = new Selection();
        $form = $this->createForm(SelectionType::class, $selection);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($selection);
            $em->flush();

            return $this->view($selection, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing item from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ProductBundle\Form\SelectionType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Selection"})
     *
     * @param Request $request the request object
     * @param Selection $selection
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Selection $selection): View
    {
        $form = $this->createForm(SelectionType::class, $selection);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($selection);
            $em->flush();

            return $this->view($selection);
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
     * @param Selection $selection
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Selection $selection): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($selection);
        $em->flush();

        return $this->routeRedirectView('umberfirm__product__get_selections', [], Response::HTTP_NO_CONTENT);
    }

    /**
     * Add item to the selection
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ProductBundle\Form\SelectionItemType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "SelectionItem"})
     *
     * @param Request $request the request object
     * @param Selection $selection
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postItemAction(Request $request, Selection $selection): View
    {
        $item = new SelectionItem();
        $item->setSelection($selection);

        $form = $this->createForm(SelectionItemType::class, $item);
        $form->remove('selection');

        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();

            return $this->view($item, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes an product item from the selection.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param Selection $selection
     * @param Product $product
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteItemAction(Selection $selection, Product $product): View
    {
        $em = $this->getDoctrine()->getManager();
        $itemRepository = $this->getDoctrine()->getRepository(SelectionItem::class);
        $item = $itemRepository->findOneBy([
            'product' => $product,
            'selection' => $selection,
        ]);

        if (null === $item) {
            throw $this->createNotFoundException();
        }

        $em->remove($item);
        $em->flush();

        return $this->routeRedirectView('umberfirm__product__get_selection', [
            'selection' => $selection->getId()->toString()
        ], Response::HTTP_NO_CONTENT);
    }
}
