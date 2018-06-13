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
use UmberFirm\Bundle\ProductBundle\Entity\AttributeGroupEnum;
use UmberFirm\Bundle\ProductBundle\Form\AttributeGroupEnumType;

/**
 * Class AttributeGroupEnumController
 *
 * @package UmberFirm\Bundle\ProductBundle\Controller
 *
 * @FOS\RouteResource("attribute-group-enum")
 * @FOS\NamePrefix("umberfirm__product__")
 */
class AttributeGroupEnumController extends FOSRestController implements ClassResourceInterface
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
     * @FOS\View(serializerGroups={"Default", "AttributeGroupEnum"})
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
            ->searchByQuery(AttributeGroupEnum::class, $searchQuery)
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
     * @FOS\View(serializerGroups={"Default", "AttributeGroupEnum"})
     *
     * @param AttributeGroupEnum $attributeGroupEnum
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(AttributeGroupEnum $attributeGroupEnum): View
    {
        return $this->view($attributeGroupEnum);
    }

    /**
     * Export a new AttributeGroupEnum from the submitted data.
     * This is action used for import Attributes from old project to new
     *
     * @FOS\View(serializerGroups={"Default", "AttributeGroupEnum"})
     *
     * @param Request $request
     *
     * @return View
     */
    public function postImportAction(Request $request): View
    {
        $attributeGroupEnumRepository = $this->getDoctrine()->getRepository(AttributeGroupEnum::class);
        $attributeGroupEnum = $attributeGroupEnumRepository->findOneBy(['name'=> $request->get('name')]);

        if (null !== $attributeGroupEnum) {
            return $this->view($attributeGroupEnum);
        }

        $attributeGroupEnum = new AttributeGroupEnum();
        $form = $this->createForm(AttributeGroupEnumType::class, $attributeGroupEnum);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($attributeGroupEnum);
            $em->flush();

            return $this->view($attributeGroupEnum, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }
}
