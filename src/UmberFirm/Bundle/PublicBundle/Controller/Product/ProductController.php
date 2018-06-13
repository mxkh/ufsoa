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

/**
 * Class ProductController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Product
 *
 * @FOS\RouteResource("product")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class ProductController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get specified product
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
     *     name="is_active",
     *     nullable=true,
     *     description="Filtering product by flag is_active. Example: ?is_active=true"
     * )
     * @FOS\QueryParam(
     *     name="is_out_of_stock",
     *     nullable=true,
     *     description="Filtering product by flag is_out_of_stock. Example: ?is_out_of_stock=false"
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Catalog"})
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param string $slug
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(ParamFetcherInterface $paramFetcher, string $slug): View
    {
        $isActive = null === $paramFetcher->get('is_active') ? null :  (bool) $paramFetcher->get('is_active');
        $isOutOfStock = null === $paramFetcher->get('is_out_of_stock') ? null :  (bool) $paramFetcher->get('is_out_of_stock');
        $catalog = $this->container->get('umberfirm.component.catalog');
        $product = $catalog->findOne($slug, $isActive, $isOutOfStock);

        if (null === $product) {
            throw $this->createNotFoundException();
        }

        return $this->view($product);
    }
}
