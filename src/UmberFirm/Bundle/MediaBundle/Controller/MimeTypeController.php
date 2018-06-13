<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\MediaBundle\Entity\MimeType;
use UmberFirm\Bundle\MediaBundle\Form\MimeTypeType;

/**
 * Class MimeTypeController
 *
 * @package UmberFirm\Bundle\MediaBundle\Controller
 *
 * @FOS\RouteResource("mime-type")
 * @FOS\NamePrefix("umberfirm__media__")
 */
class MimeTypeController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of items
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "MimeType"})
     *
     * @return View
     */
    public function cgetAction(): View
    {
        $repository = $this->getDoctrine()->getRepository(MimeType::class);
        $items = $repository->findBy([]);

        return $this->view($items);
    }

    /**
     * Get specified item
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "MimeType"})
     *
     * @param MimeType $mimeType
     *
     * @return View
     */
    public function getAction(MimeType $mimeType): View
    {
        return $this->view($mimeType);
    }

    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\MediaBundle\Form\MimeTypeType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "MimeType"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $item = new MimeType();
        $form = $this->createForm(MimeTypeType::class, $item);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();

            return $this->view($item, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing item from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\MediaBundle\Form\MimeTypeType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "MimeType"})
     *
     * @param Request $request the request object
     * @param MimeType $mimeType
     *
     * @return View
     */
    public function putAction(Request $request, MimeType $mimeType): View
    {
        $form = $this->createForm(MimeTypeType::class, $mimeType);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($mimeType);
            $em->flush();

            return $this->view($mimeType);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes an item.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param MimeType $mimeType
     *
     * @return View
     */
    public function deleteAction(MimeType $mimeType): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($mimeType);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__media__get_mime-types',
            [],
            Response::HTTP_NO_CONTENT
        );
    }
}
