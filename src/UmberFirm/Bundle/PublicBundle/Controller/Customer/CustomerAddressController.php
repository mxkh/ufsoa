<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Customer;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerAddress;
use UmberFirm\Bundle\CustomerBundle\Form\CustomerAddressType;
use UmberFirm\Bundle\PublicBundle\Controller\BaseAuthenticatedController;

/**
 * Class CustomerAddressController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Customer
 *
 * @FOS\RouteResource("customer-address")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class CustomerAddressController extends BaseAuthenticatedController implements ClassResourceInterface
{
    /**
     * Get list of customer addresses
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the customerAddress or customer not found",
     *     403 = "When customer is unauthorized",
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
     * @FOS\View(serializerGroups={"PublicService", "PublicCustomerAddress"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher): View
    {
        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');

        $addressRepository = $this->getDoctrine()->getRepository(CustomerAddress::class);
        $addressCollection = $addressRepository->findBy(
            [
                'customer' => $this->customer->getId()->toString(),
                'deletedAt' => null
            ],
            null,
            $limit,
            $offset
        );

        return $this->view($addressCollection);
    }

    /**
     * Get specified customer address
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the customerAddress or customer not found",
     *     403 = "When customer is unauthorized",
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicCustomerAddress"})
     *
     * @param CustomerAddress $customerAddress
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(CustomerAddress $customerAddress): View
    {
        if ($this->customer->getId()->toString() !== $customerAddress->getCustomer()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        if (true === $customerAddress->isDeleted()) {
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
     *     404 = "Returned when the resource not found",
     *     403 = "When customer is unauthorized",
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicCustomerAddress"})
     *
     * @param Request $request the request object
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $address = new CustomerAddress();
        $address->setShop($this->shop);
        $address->setCustomer($this->customer);
        $form = $this->createForm(CustomerAddressType::class, $address);
        $form->remove('shop');
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
     *     404 = "Returned when the resource not found",
     *     403 = "When customer is unauthorized",
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicCustomerAddress"})
     *
     * @param Request $request the request object
     * @param CustomerAddress $customerAddress
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, CustomerAddress $customerAddress): View
    {
        if ($this->customer->getId()->toString() !== $customerAddress->getCustomer()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        if (true === $customerAddress->isDeleted()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(CustomerAddressType::class, $customerAddress);
        $form->remove('shop');
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
     *     404 = "Returned when the customerAddress or customer not found",
     *     403 = "When customer is unauthorized",
     *   }
     * )
     *
     * @param CustomerAddress $customerAddress
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(CustomerAddress $customerAddress): View
    {
        if ($this->customer->getId()->toString() !== $customerAddress->getCustomer()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        if (true === $customerAddress->isDeleted()) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($customerAddress);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__public__get_customer-addresses',
            [],
            Response::HTTP_NO_CONTENT
        );
    }
}
