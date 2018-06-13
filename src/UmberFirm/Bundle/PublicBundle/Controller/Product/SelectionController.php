<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Product;

use FOS\RestBundle\Controller\Annotations as  FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use UmberFirm\Bundle\ProductBundle\Entity\Selection;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class SelectionController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Product
 *
 * @FOS\RouteResource("selection")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class SelectionController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get selections by shop.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
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
     * @FOS\View(serializerGroups={"PublicService", "PublicSelection"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher): View
    {
        /** @var Shop $shop */
        $shop = $this->getUser();

        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');

        $repository = $this->getDoctrine()->getRepository(Selection::class);
        $collection = $repository->findBy(['shop' => $shop], null, $limit, $offset);

        return $this->view($collection);
    }
}
