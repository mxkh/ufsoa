<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ProductBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\ProductBundle\Entity\Attribute;
use UmberFirm\Bundle\ProductBundle\Entity\AttributeTranslation;
use UmberFirm\Bundle\ProductBundle\Form\AttributeType;

/**
 * Class AttributeController
 *
 * @package UmberFirm\Bundle\ProductBundle\Controller
 *
 * @FOS\RouteResource("attribute")
 * @FOS\NamePrefix("umberfirm__product__")
 */
class AttributeController extends FOSRestController implements ClassResourceInterface
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
     * @FOS\View(serializerGroups={"Default", "Attribute"})
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
            ->searchByQuery(Attribute::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
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
     * @FOS\View(serializerGroups={"Default", "Attribute"})
     *
     * @param Attribute $attribute
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Attribute $attribute): View
    {
        return $this->view($attribute);
    }

    /**
     * Get Translations of Attribute
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "AttributeTranslation"})
     *
     * @param Attribute $attribute
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getTranslationsAction(Attribute $attribute): View
    {
        return $this->view($attribute->getTranslations());
    }

    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ProductBundle\Form\AttributeType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Attribute"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $item = new Attribute();
        $form = $this->createForm(AttributeType::class, $item);
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
     *   input = "UmberFirm\Bundle\ProductBundle\Form\AttributeType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Attribute"})
     *
     * @param Request $request the request object
     * @param Attribute $attribute
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Attribute $attribute): View
    {
        $form = $this->createForm(AttributeType::class, $attribute);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($attribute);
            $em->flush();

            return $this->view($attribute);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes an item.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param Attribute $attribute
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Attribute $attribute): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($attribute);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__product__get_attributes',
            [],
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @FOS\View(serializerGroups={"Default", "Attribute"})
     *
     * @param string $name
     *
     * @return View
     */
    public function getImportAction(string $name): View
    {
        $locale = $this->getParameter('locale');
        $attributeTranslationRepository = $this->getDoctrine()->getRepository(AttributeTranslation::class);
        $attributeTranslation = $attributeTranslationRepository->findOneBy(['name' => $name, 'locale' => $locale,]);
        if (null !== $attributeTranslation) {
            $attribute = $attributeTranslation->getTranslatable();

            return $this->view($attribute);
        }

        return $this->view(null, Response::HTTP_NOT_FOUND);
    }

    /**
     * @FOS\View(serializerGroups={"Default", "Attribute"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postImportAction(Request $request): View
    {
        $locale = $this->getParameter('locale');
        $translations = $request->get('translations');

        $attributeTranslationRepository = $this->getDoctrine()->getRepository(AttributeTranslation::class);
        $attributeTranslation = $attributeTranslationRepository->findOneBy(
            [
                'name' => $translations[$locale]['name'],
                'locale' => $locale,
            ]
        );
        if (null !== $attributeTranslation) {
            $attribute = $attributeTranslation->getTranslatable();

            return $this->view($attribute);
        }

        $item = new Attribute();
        $form = $this->createForm(AttributeType::class, $item);
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
}
