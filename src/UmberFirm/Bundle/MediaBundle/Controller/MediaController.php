<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraints\NotBlank;
use UmberFirm\Bundle\MediaBundle\Entity\Media;
use UmberFirm\Bundle\MediaBundle\Entity\MimeType;
use UmberFirm\Bundle\MediaBundle\Repository\MimeTypeRepository;

/**
 * Class MediaController
 *
 * @package UmberFirm\Bundle\MediaBundle\Controller
 *
 * @FOS\RouteResource("media")
 * @FOS\NamePrefix("umberfirm__media__")
 */
class MediaController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of media files
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
     * @FOS\View(serializerGroups={"Default", "Media"})
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
            ->searchByQuery(Media::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified media file
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Media"})
     *
     * @param Media $media
     *
     * @return View
     */
    public function getAction(Media $media): View
    {
        return $this->view($media);
    }

    /**
     * Creates a new Media file from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\MediaBundle\Form\MediaType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Media"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $media = new Media();

        $form = $this->createFormBuilder($media)->add('file', FileType::class)->getForm();
        $form->submit($request->files->all());

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($media);
            $em->flush();

            return $this->view($media, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes a Media file.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @param Media $media
     *
     * @return View
     */
    public function deleteAction(Media $media): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($media);
        $em->flush();

        return $this->routeRedirectView('umberfirm__media__cget_media', [], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param string $filename
     *
     * @return View
     */
    public function getImportAction(string $filename)
    {
        $mediaRepository = $this->getDoctrine()->getRepository(Media::class);
        $media = $mediaRepository->findOneBy(['filename' => $filename]);
        if (null === $media) {
            return $this->view(null, Response::HTTP_NOT_FOUND);
        }

        return $this->view($media);
    }

    /**
     * Export a new Media file from the submitted data.
     * This is action used for import images from old project to new
     *
     * @FOS\View(serializerGroups={"Default", "Media"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postImportAction(Request $request): View
    {
        $media = new Media();

        $form = $this->createFormBuilder($media)
            ->add('filename', null, ['constraints' => [new NotBlank()]])
            ->add('extension', null, ['constraints' => [new NotBlank()]])
            ->add('mimeType', null, ['constraints' => [new NotBlank()]])
            ->add('file', null, ['disabled' => true])
            ->getForm();

        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (false === $form->isValid()) {
            return $this->view($form, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $mediaRepository = $this->getDoctrine()->getRepository(Media::class);
        $mediaExist = $mediaRepository->findOneBy(['filename' => $media->getFilename()]);

        if (null !== $mediaExist) {
            throw new BadRequestHttpException('Media already exist.');
        }

        /** @var MimeTypeRepository $repository */
        $mimeTypeRepository = $this->getDoctrine()->getRepository(MimeType::class);
        $mimeType = $mimeTypeRepository->findOneByTemplate($media->getMimeType());

        if (null === $mimeType) {
            throw $this->createNotFoundException('Not supported mime-type.');
        }

        $media->setMediaEnum($mimeType->getMediaEnum());

        $em = $this->getDoctrine()->getManager();
        $em->persist($media);
        $em->flush();

        return $this->view($media, Response::HTTP_CREATED);
    }
}
