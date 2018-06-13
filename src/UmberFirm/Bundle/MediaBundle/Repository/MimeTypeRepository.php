<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\MediaBundle\Repository;

use Doctrine\ORM\EntityRepository;
use UmberFirm\Bundle\MediaBundle\Entity\MimeType;

/**
 * Class MimeTypeRepository
 *
 * @package UmberFirm\Bundle\MediaBundle\Repository
 */
class MimeTypeRepository extends EntityRepository
{
    /**
     * Find one mime type by template of content type (like image/png, etc.)
     * NOTE: There's possibility to not create this method and use some magic. but I prefer to create real method.
     *
     * @param string $template
     * @param array|null $orderBy
     *
     * @return null|object|MimeType
     */
    public function findOneByTemplate(string $template, array $orderBy = null): ?MimeType
    {
        return parent::findOneBy(['template' => $template], $orderBy);
    }
}
