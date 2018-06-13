<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;

/**
 * Class PreAuthenticationTokenFactory
 *
 * @package UmberFirm\Bundle\PublicBundle\Security
 */
interface PreAuthenticationTokenFactoryInterface
{
    /**
     * @param string $jsonWebToken
     *
     * @return PreAuthenticationJWTUserToken
     */
    public function createJWTUserToken(string $jsonWebToken): PreAuthenticationJWTUserToken;
}