<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CustomerBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerAddress;
use UmberFirm\Bundle\CustomerBundle\Form\CustomerAddressType;

/**
 * Class CustomerAddressController
 *
 * @package UmberFirm\Bundle\CustomerBundle\Controller
 *
 * @FOS\RouteResource("address")
 * @FOS\NamePrefix("umberfirm__customer__")
 */
class CustomerAddressController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of customer addresses
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the customerAddress or customer not found"
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
     * @FOS\View(serializerGroups={"Default", "CustomerAddress"})
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param Customer $customer
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher, Customer $customer): View
    {
        $limit = (int) $paramFetcher->get('limit');
        $searchQuery = $paramFetcher->get('q');
        $currentPage = (int) ($paramFetcher->get('page') ?? 1);

        $pagenator = $this->get('umberfirm.component.pagenator_factory')->searchByQuery(
            CustomerAddress::class,
            $searchQuery
        );
        $pagenator->getQueryBuilder()
            ->andWhere('customer_address.customer = :customer')
            ->setParameter('customer', $customer);
        $representation = $pagenator->getRepresentation(
            $limit,
            $currentPage,
            [
                'customer' => $customer->getId()->toString(),
            ]
        );

        return $this->view($representation);
    }

    /**
     * Get specified customer address
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the customerAddress or customer not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "CustomerAddress"})
     *
     * @param CustomerAddress $customerAddress
     * @param Customer $customer
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Customer $customer, CustomerAddress $customerAddress): View
    {
        if ($customer->getId()->toString() !== $customerAddress->getCustomer()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        return $this->view($customerAddress);
    }

    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\CustomerBundle\Form\CustomerAddressType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "CustomerAddress"})
     *
     * @param Request $request the request object
     * @param Customer $customer
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request, Customer $customer): View
    {
        $address = new CustomerAddress();
        $address->setCustomer($customer);
        $form = $this->createForm(CustomerAddressType::class, $address);
        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->flush();

            return $this->view($address, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing address from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\CustomerBundle\Form\CustomerAddressType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "CustomerAddress"})
     *
     * @param Request $request the request object
     * @param Customer $customer
     * @param CustomerAddress $customerAddress
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Customer $customer, CustomerAddress $customerAddress): View
    {
        if ($customer->getId()->toString() !== $customerAddress->getCustomer()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(CustomerAddressType::class, $customerAddress);
        $form->remove('customer');
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customerAddress);
            $em->flush();

            return $this->view($customerAddress);
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
     *     404 = "Returned when the customerAddress or customer not found"
     *   }
     * )
     *
     * @param CustomerAddress $customerAddress
     * @param Customer $customer
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Customer $customer, CustomerAddress $customerAddress): View
    {
        if ($customer->getId()->toString() !== $customerAddress->getCustomer()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($customerAddress);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__customer__get_customer_addresses',
            [
                'customer' => $customer->getId()->toString(),
            ],
            Response::HTTP_NO_CONTENT
        );
    }
}
