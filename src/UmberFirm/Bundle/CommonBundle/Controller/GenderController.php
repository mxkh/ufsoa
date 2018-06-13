<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\CommonBundle\Entity\Gender;
use UmberFirm\Bundle\CommonBundle\Entity\GenderTranslation;
use UmberFirm\Bundle\CommonBundle\Form\GenderType;

/**
 * Class GenderController
 *
 * @package UmberFirm\Bundle\CommonBundle\Controller
 *
 * @FOS\RouteResource("gender")
 * @FOS\NamePrefix("umberfirm__common__")
 */
class GenderController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of genders
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Gender"})
     *
     * @return View
     */
    public function cgetAction(): View
    {
        $genderRepository = $this->getDoctrine()->getRepository(Gender::class);
        $genderCollection = $genderRepository->findBy([]);

        return $this->view($genderCollection);
    }

    /**
     * Get specified gender
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Gender"})
     *
     * @param Gender $gender
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Gender $gender): View
    {
        return $this->view($gender);
    }

    /**
     * Get Translations of Gender
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "GenderTranslation"})
     *
     * @param Gender $gender
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getTranslationsAction(Gender $gender): View
    {
        return $this->view($gender->getTranslations());
    }

    /**
     * Creates a new Gender from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\CommonBundle\Form\GenderType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Gender"})
     *
     * @param Request $request
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $gender = new Gender();

        $form = $this->createForm(GenderType::class, $gender);
        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gender);
            $em->flush();

            return $this->view($gender, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing genders from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\CommonBundle\Form\GenderType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Gender"})
     *
     * @param Request $request
     * @param Gender $gender
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Gender $gender): View
    {
        $form = $this->createForm(GenderType::class, $gender);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gender);
            $em->flush();

            return $this->view($gender);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @FOS\View(serializerGroups={"Default", "Gender"})
     *
     * @param string $name
     *
     * @return View
     */
    public function getImportAction(string $name): View
    {
        $genderTranslationRepository = $this->getDoctrine()->getRepository(GenderTranslation::class);
        $genderTranslation = $genderTranslationRepository->findOneBy(['name' => $name]);
        if (null === $genderTranslation) {
            return $this->view(null, Response::HTTP_NOT_FOUND);
        }

        return $this->view($genderTranslation->getTranslatable());
    }
}
