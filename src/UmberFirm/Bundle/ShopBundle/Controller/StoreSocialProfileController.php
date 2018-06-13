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
use UmberFirm\Bundle\ShopBundle\Entity\StoreSocialProfile;
use UmberFirm\Bundle\ShopBundle\Form\StoreSocialProfileType;

/**
 * @FOS\RouteResource("store-social-profile")
 * @FOS\NamePrefix("umberfirm__shop__")
 */
class StoreSocialProfileController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of storeSocialProfile
     *
     * @ApiDoc(
     *      resource = true,
     *      statusCodes = {
     *          200 = "Returned when successful"
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
     * @FOS\View(serializerGroups={"Default", "StoreSocialProfile"})
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
            ->searchByQuery(StoreSocialProfile::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified storeSocialProfile
     *
     * @ApiDoc(
     *      resource = true,
     *      statusCodes = {
     *          200 = "Returned when successful",
     *          404 = "Returned when object not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "StoreSocialProfile"})
     *
     * @param StoreSocialProfile $storeSocialProfile
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(StoreSocialProfile $storeSocialProfile): View
    {
        return $this->view($storeSocialProfile);
    }

    /**
     * Creates a new storeSocialProfile from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirmShopBundle\Form\StoreSocialProfileType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "StoreSocialProfile"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $storeSocialProfile = new StoreSocialProfile();
        $form = $this->createForm(StoreSocialProfileType::class, $storeSocialProfile);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($storeSocialProfile);
            $em->flush();

            return $this->view($storeSocialProfile, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing shop group from the submitted data or create a new one
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirmShopBundle\Form\StoreSocialProfile",
     *   statusCodes = {
     *     200 = "Returned when a new resource is created",
     *     400 = "Returned when successful",
     *     404 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "StoreSocialProfile"})
     *
     * @param Request $request the request object
     * @param StoreSocialProfile $storeSocialProfile
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, StoreSocialProfile $storeSocialProfile): View
    {
        $form = $this->createForm(StoreSocialProfileType::class, $storeSocialProfile);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($storeSocialProfile);
            $em->flush();

            return $this->view($storeSocialProfile);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes a store social profile.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param StoreSocialProfile $storeSocialProfile
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(StoreSocialProfile $storeSocialProfile): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($storeSocialProfile);
        $em->flush();

        return $this->routeRedirectView('umberfirm__shop__get_store-social-profiles', [], Response::HTTP_NO_CONTENT);
    }
}
