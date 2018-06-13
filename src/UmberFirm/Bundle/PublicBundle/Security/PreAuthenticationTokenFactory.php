<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;

/**
 * Class PreAuthenticationTokenFactory
 *
 * @package UmberFirm\Bundle\PublicBundle\Security
 */
class PreAuthenticationTokenFactory implements PreAuthenticationTokenFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createJWTUserToken(string $jsonWebToken): PreAuthenticationJWTUserToken
    {
        return new PreAuthenticationJWTUserToken($jsonWebToken);
    }
}