<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Common;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use UmberFirm\Bundle\CommonBundle\Entity\Gender;

/**
 * Class GenderController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Common
 *
 * @FOS\RouteResource("gender")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class GenderController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list of gender.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicGender"})
     *
     * @return View
     */
    public function cgetAction(): View
    {
        $repository = $this->getDoctrine()->getRepository(Gender::class);
        $collection = $repository->findAll();

        return $this->view($collection);
    }
}
