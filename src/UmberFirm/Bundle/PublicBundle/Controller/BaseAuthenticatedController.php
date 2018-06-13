<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BaseAuthenticatedController
 *
 * @package UmberFirm\Bundle\PublicBundle\Controller
 */
abstract class BaseAuthenticatedController extends BasePublicController
{
    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        if (null === $this->customer) {
            throw $this->createAccessDeniedException();
        }
    }
}
