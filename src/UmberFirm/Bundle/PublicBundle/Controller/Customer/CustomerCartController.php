<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Customer;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use UmberFirm\Bundle\OrderBundle\Entity\ShoppingCart;
use UmberFirm\Bundle\PublicBundle\Controller\BaseAuthenticatedController;

/**
 * Class CustomerCartController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Customer
 *
 * @FOS\RouteResource("customer-cart")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class CustomerCartController extends BaseAuthenticatedController implements ClassResourceInterface
{
    /**
     * Get specified ShoppingCart by authorized customer
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource not found",
     *     403 = "When customer is unauthorized",
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicShoppingCart"})
     *
     * @throws NotFoundHttpException
     * @throws AccessDeniedException
     *
     * @return View
     */
    public function getAction()
    {
        $repository = $this->getDoctrine()->getRepository(ShoppingCart::class);
        $shoppingCart = $repository->findOneByCustomer($this->customer);
        if (null === $shoppingCart) {
            throw $this->createNotFoundException();
        }

        return $this->view($shoppingCart);
    }
}
