<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\CommonBundle\Entity\Language;
use UmberFirm\Bundle\CommonBundle\Form\LanguageType;

/**
 * Class LanguageController
 *
 * @package UmberFirm\Bundle\CommonBundle\Controller
 *
 * @FOS\RouteResource("language")
 * @FOS\NamePrefix("umberfirm__common__")
 */
class LanguageController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of languages
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
     * @FOS\View(serializerGroups={"Default", "Language"})
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
            ->searchByQuery(Language::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified language
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Language"})
     *
     * @param Language $language
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Language $language): View
    {
        return $this->view($language);
    }

    /**
     * Creates a new Language from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\CommonBundle\Form\LanguageType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Language"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request)
    {
        $language = new Language();
        $form = $this->createForm(LanguageType::class, $language);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($language);
            $em->flush();

            return $this->view($language, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing language from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\CommonBundle\Form\LanguageType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Language"})
     *
     * @param Request $request the request object
     * @param Language $language
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Language $language): View
    {
        $form = $this->createForm(LanguageType::class, $language);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($language);
            $em->flush();

            return $this->view($language);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes a Language.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param Language $language
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Language $language): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($language);
        $em->flush();

        return $this->routeRedirectView('umberfirm__common__get_languages', [], Response::HTTP_NO_CONTENT);
    }
}
