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
use UmberFirm\Bundle\ProductBundle\Entity\Feature;
use UmberFirm\Bundle\ProductBundle\Entity\FeatureValue;
use UmberFirm\Bundle\ProductBundle\Form\FeatureValueType;

/**
 * Class FeatureValueController
 *
 * @package UmberFirm\Bundle\ProductBundle\Controller
 *
 * @FOS\RouteResource("feature-value")
 * @FOS\NamePrefix("umberfirm__product__")
 */
class FeatureValueController extends FOSRestController implements ClassResourceInterface
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
     * @FOS\QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     nullable=true,
     *     description="Offset from which to start listing items."
     * )
     * @FOS\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="10",
     *     description="How many items to return."
     * )
     *
     * @FOS\View(serializerGroups={"Default", "FeatureValue"})
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param Feature $feature
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher, Feature $feature): View
    {
        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');

        $repository = $this->getDoctrine()->getRepository(FeatureValue::class);
        $items = $repository->findBy(['feature' => $feature], null, $limit, $offset);

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
     * @FOS\View(serializerGroups={"Default", "FeatureValue"})
     *
     * @param Feature $feature
     * @param FeatureValue $featureValue
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Feature $feature, FeatureValue $featureValue): View
    {
        if ($feature->getId() !== $featureValue->getFeature()->getId()) {
            throw $this->createNotFoundException();
        }

        return $this->view($featureValue);
    }

    /**
     * Get Translations of FeatureValue
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "FeatureValueTranslation"})
     *
     * @param Feature $feature
     * @param FeatureValue $featureValue
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getTranslationsAction(Feature $feature, FeatureValue $featureValue): View
    {
        if ($feature->getId() !== $featureValue->getFeature()->getId()) {
            throw $this->createNotFoundException();
        }

        return $this->view($featureValue->getTranslations());
    }

    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ProductBundle\Form\FeatureValueType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "FeatureValue"})
     *
     * @param Request $request the request object
     * @param Feature $feature
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request, Feature $feature): View
    {
        $featureValue = new FeatureValue();
        $featureValue->setFeature($feature);

        $form = $this->createForm(FeatureValueType::class, $featureValue);
        $form->remove('feature');
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($featureValue);
            $em->flush();

            return $this->view($featureValue, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing item from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\ProductBundle\Form\FeatureValueType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "FeatureValue"})
     *
     * @param Request $request the request object
     * @param Feature $feature
     * @param FeatureValue $featureValue
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Feature $feature, FeatureValue $featureValue): View
    {
        if ($feature->getId() !== $featureValue->getFeature()->getId()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(FeatureValueType::class, $featureValue);
        $form->remove('feature');
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($featureValue);
            $em->flush();

            return $this->view($featureValue);
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
     * @param Feature $feature
     * @param FeatureValue $featureValue
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Feature $feature, FeatureValue $featureValue): View
    {
        if ($feature->getId() !== $featureValue->getFeature()->getId()) {
            throw $this->createNotFoundException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($featureValue);
        $em->flush();

        return $this->routeRedirectView(
            'umberfirm__product__get_feature_feature-values',
            [
                'feature' => $feature->getId()->toString(),
            ],
            Response::HTTP_NO_CONTENT
        );
    }
}
