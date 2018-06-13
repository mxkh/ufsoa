<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Order;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UmberFirm\Bundle\OrderBundle\Entity\FastOrder;
use UmberFirm\Bundle\OrderBundle\Form\FastOrderType;
use UmberFirm\Bundle\PublicBundle\Controller\BasePublicController;
use UmberFirm\Bundle\PublicBundle\Event\Order\FastOrderEventInterface;

/**
 * Class FastOrderController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Order
 *
 * @FOS\RouteResource("fast-order")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class FastOrderController extends BasePublicController implements ClassResourceInterface
{
    /**
     * Creates a new item from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "UmberFirm\Bundle\OrderBundle\Form\FastOrderType",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicFastOrder"})
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postAction(Request $request): View
    {
        $fastOrder = new FastOrder();

        $form = $this->createForm(FastOrderType::class, $fastOrder);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (true === $form->isValid()) {
            $fastOrderEvent = $this->get('umberfirm.public.event.fast_order.factory')
                ->createFastOrderEvent($fastOrder, $this->authenticatedShop(), $this->authenticatedCustomer());
            $this->get('event_dispatcher')->dispatch(FastOrderEventInterface::PLACEMENT, $fastOrderEvent);

            return $this->view($fastOrder, Response::HTTP_CREATED);
        }

        return $this->view($form, Response::HTTP_BAD_REQUEST);
    }
}
