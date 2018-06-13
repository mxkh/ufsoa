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
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroup;
use UmberFirm\Bundle\ProductBundle\Form\AttributeGroupType;

/**
 * Class AttributeGroupController
 *
 * @package UmberFirm\Bundle\ProductBundle\Controller
 *
 * @FOS\RouteResource("attribute-group")
 * @FOS\NamePrefix("umberfirm__product__")
 */
class AttributeGroupController extends FOSRestController implements ClassResourceInterface
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
     * @FOS\View(serializerGroups={"Default", "AttributeGroup"})
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
            ->searchByQuery(AttributeGroup::class, $searchQuery)
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
     * @FOS\View(serializerGroups={"Default", "AttributeGroup"})
     *
     * @param AttributeGroup $attributeGroup
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(AttributeGroup $attributeGroup): View
    {
        return $this->view($attributeGroup);
    }

    /**
     * Get all Attributes of AttributeGroup item
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "AttributeGroup"})
     *
     * @param AttributeGroup $attributeGroup
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAttributesAction(AttributeGroup $attributeGroup): View
    {
        return $this->view($attributeGroup->getAttributes());
    }

    /**
     * Get Translations of AttributeGroup
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "AttributeGroupTranslation"})
     *
     * @param AttributeGroup $attributeGroup
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getTranslationsAction(AttributeGroup $attributeGroup): View
    {
        return $this->view($attributeGroup->getTranslations());
    }

    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirmProductBundle\Form\AttributeGroupType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "AttributeGroup"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $item = new AttributeGroup();
        $form = $this->createForm(AttributeGroupType::class, $item);
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
     *   input = "UmberFirm\Bundle\ProductBundle\Form\AttributeGroupType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "AttributeGroup"})
     *
     * @param Request $request the request object
     * @param AttributeGroup $attributeGroup
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, AttributeGroup $attributeGroup): View
    {
        $form = $this->createForm(AttributeGroupType::class, $attributeGroup);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($attributeGroup);
            $em->flush();

            return $this->view($attributeGroup);
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
     * @param AttributeGroup $attributeGroup
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(AttributeGroup $attributeGroup): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($attributeGroup);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__product__get_attribute-groups',
            [],
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * Export a new AttributeGroupEnum from the submitted data.
     * This is action used for import Attributes from old project to new
     *
     * @FOS\View(serializerGroups={"Default", "AttributeGroup"})
     *
     * @param Request $request
     *
     * @return View
     */
    public function postImportAction(Request $request): View
    {
        $attributeGroupRepository = $this->getDoctrine()->getRepository(AttributeGroup::class);
        $attributeGroup = $attributeGroupRepository->findOneBy(['code'=> $request->get('code')]);

        if (null !== $attributeGroup) {
            return $this->view($attributeGroup);
        }

        $attributeGroup = new AttributeGroup();
        $form = $this->createForm(AttributeGroupType::class, $attributeGroup);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($attributeGroup);
            $em->flush();

            return $this->view($attributeGroup, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }
}
