<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller\Common;

use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\DependencyInjection\ContainerInterface;
use UmberFirm\Bundle\CommonBundle\Entity\Subject;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * Class FeedbackController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller\Common
 *
 * @FOS\RouteResource("subject")
 * @FOS\NamePrefix("umberfirm__public__")
 */
class SubjectController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @var Shop
     */
    private $shop;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->shop = $this->getUser();
    }

    /**
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when customer successful",
     *     401 = "Returned when unauthorized"
     *   }
     * )
     *
     * @FOS\View(serializerGroups={"PublicService", "PublicSubject"})
     *
     * @return View
     */
    public function cgetAction(): View
    {
        $repository = $this->getDoctrine()->getRepository(Subject::class);
        $collection = $repository->findBy(['shop' => $this->shop, 'isActive' => true]);

        return $this->view($collection);
    }
}
