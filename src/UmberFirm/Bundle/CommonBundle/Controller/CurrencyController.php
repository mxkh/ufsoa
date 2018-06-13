<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\CommonBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\CommonBundle\Entity\Currency;
use UmberFirm\Bundle\CommonBundle\Form\CurrencyType;

/**
 * Class CurrencyController
 *
 * @package UmberFirm\Bundle\CommonBundle\Controller
 *
 * @FOS\RouteResource("currency")
 * @FOS\NamePrefix("umberfirm__common__")
 */
class CurrencyController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of currencies
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
     * @FOS\View(serializerGroups={"Default", "Currency"})
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
        $representation = $pagenator
            ->searchByQuery(Currency::class, $searchQuery)
            ->getRepresentation($limit, $currentPage);

        return $this->view($representation);
    }

    /**
     * Get specified currency
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Currency"})
     *
     * @param Currency $currency
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function getAction(Currency $currency): View
    {
        return $this->view($currency);
    }

    /**
     * Creates a new Currency from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\CommonBundle\Form\CurrencyType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Currency"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $currency = new Currency();
        $form = $this->createForm(CurrencyType::class, $currency);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($currency);
            $em->flush();

            return $this->view($currency, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update existing currency from the submitted data
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\CommonBundle\Form\CurrencyType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"Default", "Currency"})
     *
     * @param Request $request the request object
     * @param Currency $currency
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function putAction(Request $request, Currency $currency): View
    {
        $form = $this->createForm(CurrencyType::class, $currency);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($currency);
            $em->flush();

            return $this->view($currency);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Removes a Currency.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @param Currency $currency
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function deleteAction(Currency $currency): View
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($currency);
        $em->flush();

        return $this->routeRedirectView('umberfirm__common__get_currencies', [], Response::HTTP_NO_CONTENT);
    }
}
