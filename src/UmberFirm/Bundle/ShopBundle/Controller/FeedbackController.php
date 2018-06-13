<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\CommonBundle\Entity\Feedback;
use UmberFirm\Bundle\CommonBundle\Form\FeedbackType;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class FeedbackController
 *
 * @package UmberFirm\Bundle\ShopBundle\Controller
 *
 * @FOS\RouteResource("feedback")
 * @FOS\NamePrefix("umberfirm__shop__")
 */
class FeedbackController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of feedback
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
     * @FOS\View(serializerGroups={"Default", "Feedback"})
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param Shop $shop
     *
     * @return View
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher, Shop $shop): View
    {
        $limit = (int) $paramFetcher->get('limit');
        $searchQuery = $paramFetcher->get('q');
        $currentPage = (int) ($paramFetcher->get('page') ?? 1);

        $pagenator = $this->get('umberfirm.component.pagenator_factory');
        $pagenator->searchByQuery(Feedback::class, $searchQuery)
            ->getQueryBuilder()
            ->andWhere('feedback.shop = :shop')
            ->setParameter('shop', $shop);

        $representation = $pagenator->getRepresentation(
            $limit,
            $currentPage,
            [
                'shop' => $shop->getId()->toString(),
            ]
        );

        return $this->view($representation);
    }

    /**
     * Get specified feedback
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Feedback"})
     *
     * @param Feedback $feedback
     * @param Shop $shop
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Shop $shop, Feedback $feedback): View
    {
        if ($feedback->getShop()->getId()->toString() !== $shop->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        return $this->view($feedback);
    }

    /**
     * Creates a new Feedback from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\CommonBundle\Form\FeedbackType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Feedback"})
     *
     * @param Request $request the request object
     * @param Shop $shop
     *
     * @return View
     */
    public function postAction(Request $request, Shop $shop): View
    {
        $feedback = new Feedback();
        $feedback->setLocale($request->getLocale());
        $feedback->setShop($shop);

        $form = $this->createForm(FeedbackType::class, $feedback);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($feedback);
            $em->flush();

            return $this->view($feedback, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing Feedback from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\CommonBundle\Form\FeedbackType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Feedback"})
     *
     * @param Request $request the request object
     * @param Feedback $feedback
     * @param Shop $shop
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Shop $shop, Feedback $feedback): View
    {
        if ($feedback->getShop()->getId()->toString() !== $shop->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(FeedbackType::class, $feedback);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($feedback);
            $em->flush();

            return $this->view($feedback);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes a Feedback.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param Feedback $feedback
     * @param Shop $shop
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Shop $shop, Feedback $feedback): View
    {
        if ($feedback->getShop()->getId()->toString() !== $shop->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($feedback);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__shop__get_shop_feedbacks',
            [
                'shop' => $shop->getId()->toString(),
            ],
            Response::HTTP_NO_CONTENT
        );
    }
}
