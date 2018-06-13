<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Customer;

use Elastica\Query\Terms;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\ProductBundle\Entity\Product;
use UmberFirm\Bundle\PublicBundle\Component\Customer\Manager\Favorite\FavoriteManagerInterface;
use Hateoas\Configuration\Route;
use UmberFirm\Bundle\PublicBundle\Controller\BaseAuthenticatedController;

/**
 * Class CustomerFavoriteController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Customer
 *
 * @FOS\RouteResource("favorite")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class CustomerFavoriteController extends BaseAuthenticatedController implements ClassResourceInterface
{
    /**
     * @var FavoriteManagerInterface
     */
    private $favoriteManager;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->favoriteManager = $container->get('umberfirm.public.component.customer.manager.favorite_manager');
        $this->favoriteManager->setCustomer($this->customer);
    }

    /**
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when the resource not found",
     *         403 = "When customer is unauthorized",
     *     }
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
     *     nullable=true,
     *     description="Current page."
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Catalog"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher): View
    {
        $perPage = (int) $paramFetcher->get('limit');
        $currentPage = $paramFetcher->get('page');

        $productRepository = $this->get('fos_elastica.finder.umberfirm.product');
        $pager = $productRepository->findPaginated(new Terms('_id', $this->favoriteManager->getAll()));
        $pager->setMaxPerPage($perPage)->setCurrentPage($currentPage ?? 1);

        $factory = new PagerfantaFactory();
        $representation = $factory->createRepresentation(
            $pager,
            new Route(
                $this->container->get('request_stack')->getCurrentRequest()->get('_route'),
                [],
                true
            )
        );

        return $this->view($representation);
    }

    /**
     * @ApiDoc(
     *     resource = true,
     *     requirements = {
     *         {
     *             "name"="products",
     *             "dataType"="array",
     *             "description"="Array or products ids"
     *         }
     *     },
     *     statusCodes = {
     *         200 = "Returned when successful"
     *     }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicFavorite"})
     *
     * @param Request $request
     *
     * @return View
     */
    public function postFindAction(Request $request): View
    {
        $products = (array) $request->get('products');

        return $this->view($this->favoriteManager->get($products), Response::HTTP_OK);
    }

    /**
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         201 = "Returned when successful created",
     *         404 = "Returned when the resource not found",
     *         403 = "When customer is unauthorized",
     *     }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicFavorite"})
     *
     * @return View
     *
     * @param Request $request
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postBulkAction(Request $request): View
    {
        $products = (array) $request->get('products');
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $result = 0;
        foreach ($products as $uuid) {
            $product = $repository->findOneByUuid($uuid);
            if (null === $product) {
                continue;
            }
            $result += $this->favoriteManager->add($product);
        }

        return $this->view(['result' => $result], Response::HTTP_CREATED);
    }

    /**
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         201 = "Returned when successful created",
     *         404 = "Returned when the resource not found",
     *         403 = "When customer is unauthorized",
     *     }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicFavorite"})
     *
     * @return View
     *
     * @param Product $product
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Product $product): View
    {
        $result = $this->favoriteManager->add($product);

        return $this->view(['result' => $result], Response::HTTP_CREATED);
    }

    /**
     * @ApiDoc(
     *     resource = true,
     *     requirements = {
     *         {
     *             "name"="products",
     *             "dataType"="array",
     *             "description"="Array or products ids"
     *         }
     *     },
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         403 = "When customer is unauthorized",
     *     }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicFavorite"})
     *
     * @return View
     *
     * @param Request $request
     *
     * @return View
     */
    public function deleteAction(Request $request): View
    {
        $products = (array) $request->get('products');
        $result = $this->favoriteManager->remove($products);

        return $this->view(['result' => $result], Response::HTTP_OK);
    }
}
