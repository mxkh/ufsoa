<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Common;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\CommonBundle\Entity\Feedback;
use UmberFirm\Bundle\CommonBundle\Form\FeedbackType;
use UmberFirm\Bundle\PublicBundle\Controller\BasePublicController;
use Hateoas\Configuration\Route;

/**
 * Class FeedbackController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Common
 *
 * @FOS\RouteResource("feedback")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class FeedbackController extends BasePublicController implements ClassResourceInterface
{
    /**
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Returned when unauthorized"
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
     * @FOS\View(serializerGroups={"Default", "Feedback"})
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return View
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher): View
    {
        if (null === $this->customer) {
            throw $this->createAccessDeniedException();
        }

        $currentPage = $paramFetcher->get('page');
        $limit = $paramFetcher->get('limit');

        $repository = $this->getDoctrine()->getRepository(Feedback::class);
        $queryBuilder = $repository->createCustomerFeedbackQuery($this->customer);

        $adapter = new DoctrineORMAdapter($queryBuilder, false);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($limit);
        $pagerfanta->setCurrentPage($currentPage ?? 1);

        $factory = new PagerfantaFactory();
        $representation = $factory->createRepresentation(
            $pagerfanta,
            new Route(
                $this->container->get('request_stack')->getCurrentRequest()->get('_route'),
                [],
                true
            )
        );

        return $this->view($representation);
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Returned when customer unauthorized",
     *     404 = "Returned when feedback wasn't found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicFeedback"})
     *
     * @param Feedback $feedback
     *
     * @return View
     */
    public function getAction(Feedback $feedback): View
    {
        if (null === $this->customer) {
            throw $this->createAccessDeniedException();
        }

        if ($feedback->getCustomer()->getId()->toString() !== $this->customer->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        return $this->view($feedback);
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\CommonBundle\Form\FeedbackType",
     *   statusCodes = {
     *     201 = "Returned when successful created",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicFeedback"})
     *
     * @param Request $request
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $feedback = new Feedback();
        $feedback->setShop($this->shop);
        $feedback->setCustomer($this->customer);
        $feedback->setLocale($request->getLocale());

        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(FeedbackType::class, $feedback);
        $form->remove('customer');
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($feedback);
            $em->flush();

            return $this->view($feedback, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }
}
