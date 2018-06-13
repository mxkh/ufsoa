<?php

declare(strict_types=1);

namespace UmberFirm\Component\Request\ParamConverter;

use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DoctrineParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use UmberFirm\Component\Doctrine\Uuid\UuidEntityInterface;

/**
 * Class UuidDoctrineParamConverter
 *
 * @package UmberFirm\Component\Request\ParamConverter
 */
class UuidDoctrineParamConverter extends DoctrineParamConverter implements ParamConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $id = $this->getIdentifier($request, $this->getOptions($configuration), $configuration->getName());

        if (false === $id || false === Uuid::isValid($id)) {
            $request->attributes->set($configuration->getName(), Uuid::NIL);
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        $class = new \ReflectionClass($configuration->getClass());
        if (true === parent::supports($configuration) && true === $class->isSubclassOf(UuidEntityInterface::class)) {
            return true;
        }

        return false;
    }
}
