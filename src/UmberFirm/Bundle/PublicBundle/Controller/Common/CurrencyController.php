<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Common;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopCurrency;

/**
 * Class CurrencyController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Common
 *
 * @FOS\RouteResource("currency")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class CurrencyController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of currencies.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicCurrency"})
     *
     * @return View
     */
    public function cgetAction(): View
    {
        /** @var Shop $shop */
        $shop = $this->getUser();

        $repository = $this->getDoctrine()->getRepository(ShopCurrency::class);
        $collection = $repository->findBy(['shop' => $shop]);

        return $this->view($collection);
    }
}
