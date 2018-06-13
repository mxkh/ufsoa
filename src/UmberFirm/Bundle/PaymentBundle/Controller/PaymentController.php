<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PaymentBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as FOS;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\PaymentBundle\Component\Adapter\WayForPayAdapter;
use UmberFirm\Bundle\PaymentBundle\Entity\Payment;
use UmberFirm\Bundle\PaymentBundle\Form\PaymentType;
use UmberFirm\Bundle\ShopBundle\Entity\ShopPayment;

/**
 * Class PaymentController
 *
 * @package UmberFirm\Bundle\PaymentBundle\Controller
 *
 * @FOS\RouteResource("payment")
 * @FOS\NamePrefix("umberfirm__payment__")
 */
class PaymentController extends FOSRestController implements ClassResourceInterface
{
    /**
     * This action is an example
     * TODO: remove this action
     *
     * orderReference need to be unique
     *
     *  {
     *      "orderReference": "HD3656-8938498843931",
     *      "orderDate": 1430373125,
     *      "amount": 0.16,
     *      "currency": "UAH",
     *      "productName": "Saturn BLUE",
     *      "productCount": 1,
     *      "productPrice": 0.16,
     *      "language": "UA"
     *  }
     *
     * @param Request $request
     *
     * @return View
     */
    public function postPurchaseAction(Request $request): View
    {
        $payment = $this->getDoctrine()->getRepository(Payment::class)->findOneBy(
            ['code' => WayForPayAdapter::NAME]
        );

        $shopPayment = $this->getDoctrine()->getRepository(ShopPayment::class)->findOneBy(
            ['payment' => $payment->getId()->toString()]
        );

        $paymentManager = $this->get('umberfirm.payment.component.manager.payment_manager');
        $payment = $paymentManager->getPayment($shopPayment);

        return $this->view($payment->generatePaymentUrl($request->request->all()));
    }

    /**
     * Get list of Payments
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
     * @FOS\View(serializerGroups={"Default", "Payment"})
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
            ->searchByQuery(Payment::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified Payment
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Payment"})
     *
     * @param Payment $payment
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Payment $payment): View
    {
        return $this->view($payment);
    }

    /**
     * Get specified Translation of Payment
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Payment"})
     *
     * @param Payment $payment
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getTranslationsAction(Payment $payment): View
    {
        return $this->view($payment->getTranslations());
    }

    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\PaymentBundle\Form\PaymentType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Payment"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($payment);
            $em->flush();

            return $this->view($payment, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing item from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\PaymentBundle\Form\PaymentType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Payment"})
     *
     * @param Request $request the request object
     * @param Payment $payment
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Payment $payment): View
    {
        $form = $this->createForm(PaymentType::class, $payment);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($payment);
            $em->flush();

            return $this->view($payment);
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
     * @param Payment $payment
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Payment $payment): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($payment);
        $em->flush();

        return $this->routeRedirectView('umberfirm__payment__get_payments', [], Response::HTTP_NO_CONTENT);
    }
}
