<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Product;

use FOS\RestBundle\Controller\Annotations as  FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\ProductBundle\Entity\Selection;
use UmberFirm\Bundle\ProductBundle\Entity\SelectionItem;

/**
 * Class SelectionItemController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Product
 *
 * @FOS\RouteResource("selection-item")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class SelectionItemController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get selection items by shop.
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
     * @FOS\View(serializerGroups={"PublicService", "PublicSelectionItem"})
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param Selection $selection
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher, Selection $selection): View
    {
        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');

        $repository = $this->getDoctrine()->getRepository(SelectionItem::class);
        $collection = $repository->findBy(['selection' => $selection], null, $limit, $offset);

        return $this->view($collection);
    }
}
