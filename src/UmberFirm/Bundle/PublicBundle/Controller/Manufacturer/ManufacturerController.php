<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Manufacturer;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\View\View;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use UmberFirm\Bundle\ManufacturerBundle\Repository\ManufacturerRepository;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\ProductBundle\Repository\ProductRepository;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * @FOS\RouteResource("manufacturer")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class ManufacturerController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of manufacturers.
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
     *     description="Offset from which to start listing manufacturers."
     * )
     * @FOS\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     nullable=true,
     *     description="How many manufacturers to return."
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicManufacturers"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher): View
    {
        /** @var Shop $shop */
        $shop = $this->getUser();

        $offset = $paramFetcher->get('offset');
        $limit = null === $paramFetcher->get('limit') ? null : (int) $paramFetcher->get('limit');

        /** @var ManufacturerRepository $manufacturerRepository */
        $manufacturerRepository = $this->getDoctrine()->getRepository(Manufacturer::class);
        $manufacturerCollection = $manufacturerRepository->findByShop($shop, (int) $offset, $limit);

        return $this->view($manufacturerCollection);
    }

    /**
     * Get specified manufacturer.
     *
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *       200 = "Returned when successful",
     *       404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicManufacturer"})
     *
     * @param Manufacturer $manufacturer
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Manufacturer $manufacturer): View
    {
        return $this->view($manufacturer);
    }

    /**
     * Get list of products
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
     * @FOS\View(serializerGroups={"PublicService",  "PublicManufacturerProducts"})
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param Manufacturer $manufacturer
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getProductsAction(ParamFetcherInterface $paramFetcher, Manufacturer $manufacturer): View
    {
        /** @var Shop $shop */
        $shop = $this->getUser();

        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');

        /** @var ProductRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $collection = $repository->findBy(
            [
                'shop' => $shop,
                'manufacturer' => $manufacturer,
            ],
            null,
            (int) $offset,
            (int) $limit
        );

        return $this->view($collection);
    }
}
