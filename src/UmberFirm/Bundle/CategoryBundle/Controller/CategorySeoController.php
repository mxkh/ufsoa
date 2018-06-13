<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CategoryBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\View\View;
use UmberFirm\Bundle\CategoryBundle\Entity\Category;
use UmberFirm\Bundle\CategoryBundle\Entity\CategorySeo;
use UmberFirm\Bundle\CategoryBundle\Form\CategorySeoType;

/**
 * Class CategorySeoController
 *
 * @package UmberFirm\Bundle\CategoryBundle\Controller
 *
 * @FOS\RouteResource("seo")
 * @FOS\NamePrefix("umberfirm__category__")
 */
class CategorySeoController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of items
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "CategorySeo"})
     *
     * @param Category $category
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(Category $category): View
    {
        $repository = $this->getDoctrine()->getRepository(CategorySeo::class);
        $items = $repository->findBy(['category' => $category->getId()->toString()]);

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
     * @FOS\View(serializerGroups={"Default", "CategorySeo"})
     *
     * @param Category $category
     * @param CategorySeo $seo
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Category $category, CategorySeo $seo): View
    {
        if ($seo->getCategory()->getId()->toString() !== $category->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        return $this->view($seo);
    }

    /**
     * Get specified item translations
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "CategorySeoTranslation"})
     *
     * @param Category $category
     * @param CategorySeo $seo
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getTranslationsAction(Category $category, CategorySeo $seo): View
    {
        if ($seo->getCategory()->getId()->toString() !== $category->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        return $this->view($seo->getTranslations());
    }

    /**
     * Creating new item from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\CategoryBundle\Form\CategorySeoType",
     *   statusCodes = {
     *     201 = "Returned when created successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "CategorySeo"})
     *
     * @param Request $request the request object
     * @param Category $category
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request, Category $category): View
    {
        $seo = new CategorySeo();
        $seo->setCategory($category);

        $form = $this->createForm(CategorySeoType::class, $seo);
        $form->remove('category');

        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($seo);
            $em->flush();

            return $this->view($seo, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing item from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\CategoryBundle\Form\CategorySeoType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "CategorySeo"})
     *
     * @param Request $request the request object
     * @param Category $category
     * @param CategorySeo $seo
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Category $category, CategorySeo $seo): View
    {
        if ($seo->getCategory()->getId()->toString() !== $category->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(CategorySeoType::class, $seo);
        $form->remove('category');

        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($seo);
            $em->flush();

            return $this->view($seo);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }
}
