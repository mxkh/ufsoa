<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ShopBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\CategoryBundle\Entity\Category;
use UmberFirm\Bundle\CategoryBundle\Form\CategoryType;
use UmberFirm\Bundle\CategoryBundle\Repository\CategoryNestedTreeRepositoryInterface;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class ShopCategoryController
 *
 * @package UmberFirm\Bundle\ShopBundle\Controller
 *
 * @FOS\RouteResource("category")
 * @FOS\NamePrefix("umberfirm__shop__")
 */
class ShopCategoryController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of categories of a specified shop.
     *
     * @ApiDoc(
     *      resource = true,
     *      statusCodes = {
     *          200 = "Returned when successful",
     *          404 = "Returned when object not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Categories"})
     *
     * @param Shop $shop
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(Shop $shop): View
    {
        /** @var CategoryNestedTreeRepositoryInterface $categoryRepository */
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $categoryRepository->getRootNodesByShop($shop);

        return $this->view($categories);
    }

    /**
     * Get category from specified shop.
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
     * @param Shop $shop
     * @param Category $category
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Shop $shop, Category $category): View
    {
        if ($shop->getId()->toString() !== $category->getShop()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        return $this->view($category);
    }

    /**
     * Creates a new Category from the submitted data for a specified shop.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirmCategoryBundle\Form\CategoryType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Category"})
     *
     * @param Request $request
     * @param Shop $shop
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request, Shop $shop): View
    {
        $category = new Category();
        $category->setShop($shop);
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
     * Update existing category from the submitted data of a specified shop.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirmCategoryBundle\Form\CategoryType",
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
     * @param Shop $shop
     * @param Category $category
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Shop $shop, Category $category): View
    {
        if ($shop->getId()->toString() !== $category->getShop()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $category->setShop($shop);
        $form = $this->createForm(CategoryType::class, $category);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->view($category);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes a Category in specified shop.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param Shop $shop
     * @param Category $category
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Shop $shop, Category $category): View
    {
        if ($shop->getId()->toString() !== $category->getShop()->getId()->toString()) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__shop__get_shop_categories',
            ['shop' => $shop->getId()->toString()],
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @FOS\View(serializerGroups={"Default", "Category"})
     *
     * @param Shop $shop
     * @param string $reference
     * @param string|null $parent
     *
     * @return View
     */
    public function getImportAction(Shop $shop, string $reference, string $parent = null): View
    {
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $category = $categoryRepository->findOneBy(['reference' => $reference, 'parent' => $parent, 'shop' => $shop,]);
        if (null === $category) {
            return $this->view(null, Response::HTTP_NOT_FOUND);
        }

        return $this->view($category);
    }

    /**
     * @FOS\View(serializerGroups={"Default", "Category"})
     *
     * @param Shop $shop
     * @param string $reference
     * @param string $root
     *
     * @return View
     */
    public function getImportRootAction(Shop $shop, string $reference, string $root): View
    {
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $category = $categoryRepository->findOneBy(['reference' => $reference, 'root' => $root, 'shop' => $shop,]);
        if (null === $category) {
            return $this->view(null, Response::HTTP_NOT_FOUND);
        }

        return $this->view($category);
    }

    /**
     * @FOS\View(serializerGroups={"Default", "Category"})
     *
     * @param Request $request the request object
     * @param Shop $shop
     *
     * @return View
     */
    public function postImportAction(Request $request, Shop $shop): View
    {
        $reference = $request->get('reference');
        $parent = $request->get('parent');

        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $category = $categoryRepository->findOneBy(['reference' => $reference, 'parent' => $parent, 'shop' => $shop,]);
        if (null !== $category) {
            return $this->view($category);
        }

        $category = new Category();
        $category->setShop($shop);
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
}
