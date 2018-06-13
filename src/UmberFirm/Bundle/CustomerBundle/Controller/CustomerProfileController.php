<?php

namespace UmberFirm\Bundle\CustomerBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\CustomerBundle\Entity\Customer;
use UmberFirm\Bundle\CustomerBundle\Entity\CustomerProfile;
use UmberFirm\Bundle\CustomerBundle\Form\CustomerProfileType;

/**
 * Class CustomerProfileController
 *
 * @package UmberFirm\Bundle\CustomerBundle\Controller
 *
 * @FOS\RouteResource("profile")
 * @FOS\NamePrefix("umberfirm__customer__")
 */
class CustomerProfileController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get Customer Profile
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *      200 = "Returned when successful",
     *      404 = "Returns when customer or customer profile not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "CustomerProfile"})
     *
     * @param Customer $customer
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Customer $customer): View
    {
        $repository = $this->getDoctrine()->getRepository(CustomerProfile::class);
        $profile = $repository->findOneBy(['customer' => $customer->getId()->toString()]);

        if (null === $profile) {
            throw $this->createNotFoundException();
        }

        return $this->view($profile);
    }

    /**
     * Update existing customer profile from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\CustomerBundle\Form\CustomerProfileType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the customer or customer profile not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "CustomerProfile"})
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
        $customerProfile = $customer->getProfile();

        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(CustomerProfileType::class, $customerProfile);
        $form->remove('customer');
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($customerProfile);
            $em->flush();

            return $this->view($customerProfile);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }
}
