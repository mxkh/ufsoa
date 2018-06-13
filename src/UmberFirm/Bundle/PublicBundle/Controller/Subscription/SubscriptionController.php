<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Subscription;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\SubscriptionBundle\Entity\Subscriber;
use UmberFirm\Bundle\SubscriptionBundle\Form\SubscriberType;

/**
 * Class SubscriptionController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Subscription
 *
 * @FOS\RouteResource("subscribe")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class SubscriptionController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Creates a new Subscriber from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\SubscriptionBundle\Form\SubscriberType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Subscriber"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $subscriber = new Subscriber();
        $form = $this->createForm(SubscriberType::class, $subscriber);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($subscriber);
            $em->flush();

            return $this->view($subscriber, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }
}
