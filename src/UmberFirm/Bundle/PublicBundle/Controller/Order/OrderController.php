<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Order;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UmberFirm\Bundle\PublicBundle\Controller\BasePublicController;
use UmberFirm\Bundle\PublicBundle\DataObject\PublicOrder;
use UmberFirm\Bundle\PublicBundle\Form\PublicOrderType;

/**
 * Class OrderController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Order
 *
 * @FOS\RouteResource("order")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class OrderController extends BasePublicController implements ClassResourceInterface
{
    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\PublicBundle\Form\PublicOrderType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     404 = "Returned when the resource not found"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicOrder"})
     *
     * @param Request $request the request object
     *
     * @throws NotFoundHttpException
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $publicOrder = new PublicOrder();
        $publicOrder->setShop($this->shop);
        $publicOrder->setCustomer($this->customer);

        $form = $this->createForm(PublicOrderType::class, $publicOrder);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $orderManager = $this->get('umberfirm.public.component.order.manager.order_manager');
            $publicOrder = $orderManager->manage($publicOrder);

            return $this->view($publicOrder, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }
}
