<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CategoryBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Gedmo\Exception\UnexpectedValueException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\CategoryBundle\Entity\Category;
use UmberFirm\Bundle\CategoryBundle\Form\CategoryType;
use UmberFirm\Bundle\CategoryBundle\Repository\CategoryRepository;

/**
 * Class CategoryController
 *
 * @package UmberFirm\Bundle\CategoryBundle\Controller
 *
 * @FOS\RouteResource("category")
 * @FOS\NamePrefix("umberfirm__category__")
 */
class CategoryController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of categories.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Categories"})
     *
     * @return View
     */
    public function cgetAction(): View
    {
        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $categoryRepository->getRootNodes();

        return $this->view($categories);
    }

    /**
     * Get specified category.
     *
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *       200 = "Returned when successful",
     *       404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Category"})
     *
     * @param Category $category
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Category $category): View
    {
        return $this->view($category);
    }

    /**
     * Get Translations of Category
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "CategoryTranslation"})
     *
     * @param Category $category
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getTranslationsAction(Category $category): View
    {
        return $this->view($category->getTranslations());
    }

    /**
     * Creates a new Category from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\CategoryBundle\Form\CategoryType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Category"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->view($category, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing category from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\CategoryBundle\Form\CategoryType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Category"})
     *
     * @param Request $request the request object
     * @param Category $category
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Category $category): View
    {
        $form = $this->createForm(CategoryType::class, $category);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($category);
                $em->flush();
            } catch (UnexpectedValueException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }

            return $this->view($category);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes a Category.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param Category $category
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Category $category): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return $this->routeRedirectView('umberfirm__category__get_categories', [], Response::HTTP_NO_CONTENT);
    }
}
