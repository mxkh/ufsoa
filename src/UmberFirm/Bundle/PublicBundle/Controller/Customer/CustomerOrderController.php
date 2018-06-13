<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Customer;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Hateoas\Configuration\Route;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Bundle\OrderBundle\Entity\OrderItem;
use UmberFirm\Bundle\OrderBundle\Repository\OrderItemRepository;
use UmberFirm\Bundle\OrderBundle\Repository\OrderRepository;
use UmberFirm\Bundle\PublicBundle\Controller\BaseAuthenticatedController;

/**
 * Class CustomerOrderController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Customer
 *
 * @FOS\RouteResource("customer-order")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class CustomerOrderController extends BaseAuthenticatedController implements ClassResourceInterface
{
    /**
     * Get Customer Orders.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *      200 = "Returned when successful",
     *      404 = "Returns when not found",
     *      403 = "When customer is unauthorized",
     *   }
     * )
     *
     * @FOS\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="10",
     *     nullable=true,
     *     description="How many items to return. If zero|null given, all results returned."
     * )
     * @FOS\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     default="1",
     *     nullable=true
     * )
     *
     * @FOS\View(serializerGroups={"Default", "PublicService", "PublicOrders"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     *
     * @throws NotFoundHttpException
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher): View
    {
        $limit = $paramFetcher->get('limit');
        $currentPage = $paramFetcher->get('page');

        /** @var OrderRepository $repository */
        $repository = $this->getDoctrine()->getRepository(Order::class);
        $queryBuilder = $repository->createCustomerOrderQuery($this->customer);

        $adapter = new DoctrineORMAdapter($queryBuilder, false);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($limit);
        $pagerfanta->setCurrentPage($currentPage ?? 1);

        $factory = new PagerfantaFactory();
        $representation = $factory->createRepresentation(
            $pagerfanta,
            new Route(
                $this->container->get('request_stack')->getCurrentRequest()->get('_route'),
                ['customer' => $this->customer->getId()->toString()],
                true
            )
        );

        return $this->view($representation);
    }

    /**
     * Get Customer Order.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *      200 = "Returned when successful",
     *      404 = "Returns when not found",
     *      403 = "When customer is unauthorized",
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicOrder"})
     *
     * @param Order $order
     *
     * @return View
     *
     * @throws NotFoundHttpException
     */
    public function getAction(Order $order): View
    {
        if ($this->customer->getId()->toString() !== $order->getCustomer()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        return $this->view($order);
    }


    /**
     * Get Customer Order Items.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *      200 = "Returned when successful",
     *      404 = "Returns when not found",
     *      403 = "When customer is unauthorized",
     *   }
     * )
     *
     * @FOS\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="10",
     *     nullable=true,
     *     description="How many items to return. If zero|null given, all results returned."
     * )
     * @FOS\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     default="1",
     *     nullable=true
     * )
     *
     * @FOS\View(serializerGroups={"Default", "PublicService", "PublicOrderItem"})
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param Order $order
     *
     * @return View
     *
     * @throws NotFoundHttpException
     */
    public function cgetItemsAction(ParamFetcherInterface $paramFetcher, Order $order): View
    {
        if ($this->customer->getId()->toString() !== $order->getCustomer()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $limit = $paramFetcher->get('limit');
        $currentPage = $paramFetcher->get('page');

        /** @var OrderItemRepository $repository */
        $repository = $this->getDoctrine()->getRepository(OrderItem::class);
        $queryBuilder = $repository->createOrderItemQuery($order);

        $adapter = new DoctrineORMAdapter($queryBuilder, false);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($limit);
        $pagerfanta->setCurrentPage($currentPage ?? 1);

        $factory = new PagerfantaFactory();
        $representation = $factory->createRepresentation(
            $pagerfanta,
            new Route(
                $this->container->get('request_stack')->getCurrentRequest()->get('_route'),
                ['order' => $order->getId()->toString()],
                true
            )
        );

        return $this->view($representation);
    }

    /**
     * Get Customer Order Item.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *      200 = "Returned when successful",
     *      404 = "Returns when not found",
     *      403 = "When customer is unauthorized",
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicOrderItem"})
     *
     * @param Order $order
     * @param OrderItem $orderItem
     *
     * @return View
     *
     * @throws NotFoundHttpException
     */
    public function getItemAction(Order $order, OrderItem $orderItem): View
    {
        if ($this->customer->getId()->toString() !== $order->getCustomer()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        if ($order->getId()->toString() !== $orderItem->getOrder()->getId()->toString()) {
            throw  $this->createNotFoundException();
        }

        return $this->view($orderItem);
    }
}
