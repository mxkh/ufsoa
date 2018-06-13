<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\ManufacturerBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\ManufacturerBundle\Entity\Manufacturer;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as FOS;
use UmberFirm\Bundle\ManufacturerBundle\Form\ManufacturerType;
use FOS\RestBundle\View\View;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * @FOS\RouteResource("manufacturer")
 * @FOS\NamePrefix("umberfirm__manufacturer__")
 */
class ManufacturerController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of manufacturers.
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
     * @FOS\View(serializerGroups={"Default", "Manufacturer"})
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
            ->searchByQuery(Manufacturer::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified Manufacturer.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Manufacturer"})
     *
     * @param Manufacturer $manufacturer
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Manufacturer $manufacturer): View
    {
        return $this->view($manufacturer);
    }

    /**
     * Get Translations of Manufacturer
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "ManufacturerTranslation"})
     *
     * @param Manufacturer $manufacturer
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getTranslationsAction(Manufacturer $manufacturer): View
    {
        return $this->view($manufacturer->getTranslations());
    }

    /**
     * Creates a new Manufacturer from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ManufacturerBundle\Form\ManufacturerType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Manufacturer"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $manufacturer = new Manufacturer();
        $form = $this->createForm(ManufacturerType::class, $manufacturer);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($manufacturer);
            $em->flush();

            return $this->view($manufacturer, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing manufacturer from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ManufacturerBundle\Form\ManufacturerType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Manufacturer"})
     *
     * @param Request $request the request object
     * @param Manufacturer $manufacturer
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Manufacturer $manufacturer): View
    {
        $form = $this->createForm(ManufacturerType::class, $manufacturer);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($manufacturer);
            $em->flush();

            return $this->view($manufacturer);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes a Manufacturer.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param Manufacturer $manufacturer
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Manufacturer $manufacturer): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($manufacturer);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__manufacturer__get_manufacturers',
            [],
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @FOS\View(serializerGroups={"Default", "Manufacturer"})
     *
     * @param string $reference
     *
     * @return View
     */
    public function getImportAction(string $reference): View
    {
        $manufacturerRepository = $this->getDoctrine()->getRepository(Manufacturer::class);
        $manufacturer = $manufacturerRepository->findOneBy(['reference' => $reference]);
        if (null === $manufacturer) {
            return $this->view(null, Response::HTTP_NOT_FOUND);
        }

        return $this->view($manufacturer);
    }

    /**
     * @FOS\View(serializerGroups={"Default", "Manufacturer"})
     *
     * @param Shop $shop
     * @param Request $request the request object
     *
     * @return View
     */
    public function postImportAction(Request $request, Shop $shop): View
    {
        $reference = $request->get('reference');

        $manufacturerRepository = $this->getDoctrine()->getRepository(Manufacturer::class);
        $manufacturer = $manufacturerRepository->findOneBy(['reference' => $reference]);

        if (null !== $manufacturer) {
            $manufacturer->addShop($shop);
            $em = $this->getDoctrine()->getManager();
            $em->persist($manufacturer);
            $em->flush();

            return $this->view($manufacturer);
        }

        $manufacturer = new Manufacturer();
        $manufacturer->addShop($shop);
        $form = $this->createForm(ManufacturerType::class, $manufacturer);
        $form->remove('shops');
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($manufacturer);
            $em->flush();

            return $this->view($manufacturer, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }
}
