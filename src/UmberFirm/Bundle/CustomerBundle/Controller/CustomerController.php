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
use UmberFirm\Bundle\CommonBundle\Entity\Gender;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerProfile;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerSocialIdentity;
use UmberFirm\Bundle\CustomerBundle\Entity\SocialNetwork;
use UmberFirm\Bundle\CustomerBundle\Form\CustomerType;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class CustomerController
 *
 * @package UmberFirm\Bundle\CustomerBundle\Controller
 *
 * @FOS\RouteResource("customer")
 * @FOS\NamePrefix("umberfirm__customer__")
 */
class CustomerController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get paginated list of customers filtered by search query string if any
     *
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         200 = "Returned when successful"
     *     }
     * )
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
     * @FOS\View(serializerGroups={"Default", "Customer"})
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
            ->searchByQuery(Customer::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified customer
     *
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when the resource not found"
     *     }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Customer"})
     *
     * @param Customer $customer
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Customer $customer): View
    {
        return $this->view($customer);
    }

    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *     resource = true,
     *     input = "UmberFirm\Bundle\CustomerBundle\Form\CustomerType",
     *     statusCodes = {
     *         201 = "Returned when successful",
     *         400 = "Returned when the form has errors"
     *     }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Customer"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if (true === $form->isValid()) {
            //hack to create default empty customer profile
            $customer->setProfile(new CustomerProfile());

            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();

            return $this->view($customer, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing item from the submitted data
     *
     * @ApiDoc(
     *     resource = true,
     *     input = "UmberFirm\Bundle\CustomerBundle\Form\CustomerType",
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         400 = "Returned when the form has errors",
     *         404 = "Returned when the resource not found"
     *     }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Customer"})
     *
     * @param Request $request the request object
     * @param Customer $customer
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Customer $customer): View
    {
        $form = $this->createForm(CustomerType::class, $customer);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();

            return $this->view($customer, Response::HTTP_OK);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes an item.
     *
     * @ApiDoc(
     *     resource = true,
     *     statusCodes={
     *         204="Returned when successful",
     *         404 = "Returned when the resource not found"
     *     }
     * )
     *
     * @param Customer $customer
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Customer $customer): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($customer);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__customer__get_customers',
            [],
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @FOS\View(serializerGroups={"Default", "Customer"})
     *
     * @param Request $request
     * @param Shop $shop
     *
     * @return View
     */
    public function postImportAction(Request $request, Shop $shop): View
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);
        $customer = $em->getRepository(Customer::class)->findOneBy(['email' => $data['email']]);
        if (null !== $customer) {
            return $this->view($customer);
        }

        $customer = new Customer();
        $customer->setShop($shop);
        $customer->setEmail($data['email']);
        $customer->setPhone($data['phone']);

        $customerProfile = new CustomerProfile();
        if (null !== $data['customerProfile']['birthday']) {
            $customerProfile->setBirthday(new \DateTime($data['customerProfile']['birthday']));
        }

        $gender = null;
        if (null !== $data['customerProfile']['gender']) {
            $gender = $em->getRepository(Gender::class)->find($data['customerProfile']['gender']);
        }

        $customerProfile->setFirstname($data['customerProfile']['firstname']);
        $customerProfile->setLastname($data['customerProfile']['lastname']);
        $customerProfile->setGender($gender);
        $customerProfile->setCustomer($customer);
        $em->persist($customerProfile);

        foreach ($data['customerSocial'] as $social) {
            $socialNetwork = $em->getRepository(SocialNetwork::class)->findOneBy(['name' => $social['socialNetwork']]);
            if (null === $socialNetwork) {
                continue;
            }
            $customerSocial = new CustomerSocialIdentity();
            $customerSocial->setCustomer($customer);
            $customerSocial->setSocialId($social['socialId']);
            $customerSocial->setSocialNetwork($socialNetwork);
            $em->persist($customerSocial);
        }

        $em->persist($customer);
        $em->flush();

        return $this->view($customer);
    }
}
