<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Customer;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\CustomerBundle\Form\CustomerProfileType;
use UmberFirm\Bundle\PublicBundle\Controller\BaseAuthenticatedController;

/**
 * Class CustomerProfileController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Customer
 *
 * @FOS\RouteResource("profile")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class CustomerProfileController extends BaseAuthenticatedController implements ClassResourceInterface
{
    /**
     * Get Customer Profile
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *      200 = "Returned when successful",
     *      404 = "Returns when customer or customer profile not found",
     *      403 = "When customer is unauthorized",
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicCustomerProfile"})
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(): View
    {
        return $this->view($this->customer->getProfile());
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
     *     404 = "Returned when the customer or customer profile not found",
     *     403 = "When customer is unauthorized",
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicCustomerProfile"})
     *
     * @param Request $request the request object
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request): View
    {
        $customerProfile = $this->customer->getProfile();
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
