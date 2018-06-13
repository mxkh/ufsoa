<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\ShopBundle\Entity\SocialProfileEnum;
use UmberFirm\Bundle\ShopBundle\Form\SocialProfileEnumType;

/**
 * @FOS\RouteResource("social-profile-enum")
 * @FOS\NamePrefix("umberfirm__shop__")
 */
class SocialProfileEnumController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of SocialProfileEnum
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
     * @FOS\View(serializerGroups={"Default", "SocialProfileEnum"})
     *
     * @param ParamFetcherInterface $paramFetcher param fetcher service
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
            ->searchByQuery(SocialProfileEnum::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified SocialProfileEnum
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "SocialProfileEnum"})
     *
     * @param SocialProfileEnum $socialProfileEnum
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(SocialProfileEnum $socialProfileEnum): View
    {
        return $this->view($socialProfileEnum);
    }

    /**
     * Get Translations of SocialProfileEnum
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "SocialProfileEnumTranslation"})
     *
     * @param SocialProfileEnum $socialProfileEnum
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getTranslationsAction(SocialProfileEnum $socialProfileEnum): View
    {
        return $this->view($socialProfileEnum->getTranslations());
    }

    /**
     * Creates a new SocialProfileEnum from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ShopBundle\Form\SocialProfileEnumType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "SocialProfileEnum"})
     *
     * @param Request $request the request object
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $socialProfileEnum = new SocialProfileEnum();
        $form = $this->createForm(SocialProfileEnumType::class, $socialProfileEnum);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($socialProfileEnum);
            $em->flush();

            return $this->view($socialProfileEnum, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing SocialProfileEnum from the submitted data or create a new one
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirmShopBundle\Form\SocialProfileEnumType",
     *   statusCodes = {
     *     200 = "Returned when a new resource is created",
     *     400 = "Returned when successful",
     *     404 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "SocialProfileEnum"})
     *
     * @param Request $request the request object
     * @param SocialProfileEnum $socialProfileEnum
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, SocialProfileEnum $socialProfileEnum): View
    {
        $form = $this->createForm(SocialProfileEnumType::class, $socialProfileEnum);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($socialProfileEnum);
            $em->flush();

            return $this->view($socialProfileEnum);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }
}
