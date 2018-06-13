<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\OrderBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\OrderBundle\Entity\Order;
use UmberFirm\Bundle\OrderBundle\Entity\OrderItem;
use UmberFirm\Bundle\OrderBundle\Form\OrderItemType;

/**
 * Class OrderItemController
 *
 * @package UmberFirm\Bundle\OrderBundle\Controller
 *
 * @FOS\RouteResource("order-item")
 * @FOS\NamePrefix("umberfirm__order__")
 */
class OrderItemController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of orderItems
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
     * @FOS\View(serializerGroups={"Default", "OrderItem"})
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param Order $order
     *
     * @return View
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher, Order $order): View
    {
        $limit = (int) $paramFetcher->get('limit');
        $searchQuery = $paramFetcher->get('q');
        $currentPage = (int) ($paramFetcher->get('page') ?? 1);

        $pagenator = $this->get('umberfirm.component.pagenator_factory');
        $representation = $pagenator
            ->searchByQuery(OrderItem::class, $searchQuery)
            ->getRepresentation(
                $limit,
                $currentPage,
                [
                    'order' => $order->getId()->toString(),
                ]
            );

        return $this->view($representation);
    }

    /**
     * Get specified order item.
     *
     * @ApiDoc(
     *    resource = true,
     *    statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "OrderItem"})
     *
     * @param Order $order
     * @param OrderItem $orderItem
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Order $order, OrderItem $orderItem): View
    {
        if ($order->getId()->toString() !== $orderItem->getOrder()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        return $this->view($orderItem);
    }

    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\OrderBundle\Form\OrderItemType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "OrderItem"})
     *
     * @param Request $request the request object
     * @param Order $order
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request, Order $order): View
    {
        $orderItem = new OrderItem();
        $orderItem->setOrder($order);
        $form = $this->createForm(OrderItemType::class, $orderItem);
        $form->remove('order');
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($orderItem);
            $em->flush();

            return $this->view($orderItem, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\OrderBundle\Form\OrderItemType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "OrderItem"})
     *
     * @param Request $request the request object
     * @param OrderItem $orderItem
     * @param Order $order
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Order $order, OrderItem $orderItem): View
    {
        if ($order->getId()->toString() !== $orderItem->getOrder()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(OrderItemType::class, $orderItem);
        $form->remove('order');
        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($orderItem);
            $em->flush();

            return $this->view($orderItem);
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
     * @param OrderItem $orderItem
     * @param Order $order
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Order $order, OrderItem $orderItem): View
    {
        if ($order->getId()->toString() !== $orderItem->getOrder()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($orderItem);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__order__get_order_order-items',
            [
                'order' => $order->getId()->toString(),
            ],
            Response::HTTP_NO_CONTENT
        );
    }
}
