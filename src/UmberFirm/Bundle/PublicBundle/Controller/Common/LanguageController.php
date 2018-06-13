<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Common;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;
use UmberFirm\Bundle\ShopBundle\Entity\ShopLanguage;

/**
 * Class LanguageController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Common
 *
 * @FOS\RouteResource("language")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class LanguageController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of languages.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicLanguage"})
     *
     * @return View
     */
    public function cgetAction(): View
    {
        /** @var Shop $shop */
        $shop = $this->getUser();

        $repository = $this->getDoctrine()->getRepository(ShopLanguage::class);
        $collection = $repository->findBy(['shop' => $shop]);

        return $this->view($collection);
    }
}
