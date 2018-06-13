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
use UmberFirm\Bundle\CommonBundle\Entity\Subject;
use UmberFirm\Bundle\CommonBundle\Form\SubjectType;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class SubjectController
 *
 * @package UmberFirm\Bundle\ShopBundle\Controller
 *
 * @FOS\RouteResource("subject")
 * @FOS\NamePrefix("umberfirm__shop__")
 */
class SubjectController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of subjects
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
     * @FOS\View(serializerGroups={"Default", "Subject"})
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
        $pagenator->searchByQuery(Subject::class, $searchQuery)
            ->getQueryBuilder()
            ->andWhere('subject.shop = :shop')
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
     * Get specified subjects
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Subject"})
     *
     * @param Subject $subject
     * @param Shop $shop
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Shop $shop, Subject $subject): View
    {
        if ($shop->getId()->toString() === $subject->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        return $this->view($subject);
    }

    /**
     * Get Translations of Product
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "SubjectTranslation"})
     *
     * @param Subject $subject
     * @param Shop $shop
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getTranslationsAction(Shop $shop, Subject $subject): View
    {
        if ($shop->getId()->toString() === $subject->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        return $this->view($subject->getTranslations());
    }

    /**
     * Creates a new Subject from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\CommonBundle\Form\SubjectType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Subject"})
     *
     * @param Request $request the request object
     * @param Shop $shop
     *
     * @return View
     */
    public function postAction(Shop $shop, Request $request): View
    {
        $subject = new Subject();
        $subject->setShop($shop);
        $form = $this->createForm(SubjectType::class, $subject);
        $form->remove('shop');
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($subject);
            $em->flush();

            return $this->view($subject, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing Subject from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\CommonBundle\Form\SubjectType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Subject"})
     *
     * @param Request $request the request object
     * @param Shop $shop
     * @param Subject $subject
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Shop $shop, Subject $subject): View
    {
        if ($shop->getId()->toString() === $subject->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(SubjectType::class, $subject);
        $form->remove('shop');
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($subject);
            $em->flush();

            return $this->view($subject);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes a Subject.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param Subject $subject
     * @param Shop $shop
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Shop $shop, Subject $subject): View
    {
        if ($shop->getId()->toString() === $subject->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($subject);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__shop__get_shop_subjects',
            [
                'shop' => $shop->getId()->toString(),
            ],
            Response::HTTP_NO_CONTENT
        );
    }
}
