<?php

declare(strict_types=1);

namespace UmberFirm\Bundle\PublicBundle\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTAuthenticatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\ExpiredTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\InvalidPayloadException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\InvalidTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\MissingTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\UserNotFoundException;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserProvider;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorInterface;
use Symfony\Component\Security\Guard\Token\GuardTokenInterface;
use UmberFirm\Bundle\ShopBundle\Entity\Shop;

/**
 * JWTTokenAuthenticator (Guard implementation).
 *
 * @see http://knpuniversity.com/screencast/symfony-rest4/jwt-guard-authenticator
 *
 * @author Nicolas Cabot <n.cabot@lexik.fr>
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class JwtAuthenticator implements GuardAuthenticatorInterface
{
    /**
     * @var JWTTokenManagerInterface
     */
    private $jwtManager;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var TokenExtractorInterface
     */
    private $tokenExtractor;

    /**
     * @var TokenStorageInterface
     */
    private $preAuthenticationTokenStorage;

    /**
     * @var PreAuthenticationTokenFactory
     */
    private $preAuthenticationTokenFactory;

    /**
     * JwtAuthenticator constructor.
     *
     * @param JWTTokenManagerInterface $jwtManager
     * @param EventDispatcherInterface $dispatcher
     * @param TokenExtractorInterface $tokenExtractor
     * @param PreAuthenticationTokenFactory $preAuthenticationTokenFactory
     */
    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        EventDispatcherInterface $dispatcher,
        TokenExtractorInterface $tokenExtractor,
        PreAuthenticationTokenFactory $preAuthenticationTokenFactory
    ) {
        $this->jwtManager                    = $jwtManager;
        $this->dispatcher                    = $dispatcher;
        $this->tokenExtractor                = $tokenExtractor;
        $this->preAuthenticationTokenStorage = new TokenStorage();
        $this->preAuthenticationTokenFactory = $preAuthenticationTokenFactory;
    }

    /**
     * Returns a decoded JWT token extracted from a request.
     *
     * {@inheritdoc}
     *
     * @return null|PreAuthenticationJWTUserToken
     *
     * @throws InvalidTokenException If an error occur while decoding the token
     * @throws ExpiredTokenException If the request token is expired
     */
    public function getCredentials(Request $request): ?PreAuthenticationJWTUserToken
    {
        $tokenExtractor = $this->getTokenExtractor();

        if (false === ($tokenExtractor instanceof TokenExtractorInterface)) {
            throw new \RuntimeException(sprintf('Method "%s::getTokenExtractor()" must return an instance of "%s".', __CLASS__, TokenExtractorInterface::class));
        }

        if (false === ($jsonWebToken = $tokenExtractor->extract($request))) {
            return null;
        }

        $preAuthToken = $this->preAuthenticationTokenFactory->createJWTUserToken($jsonWebToken);

        try {
            $payload = $this->jwtManager->decode($preAuthToken);
            if (false === $payload) {
                throw new InvalidTokenException('Invalid JWT Token');
            }

            $preAuthToken->setPayload($payload);
        } catch (JWTDecodeFailureException $e) {
            if (JWTDecodeFailureException::EXPIRED_TOKEN === $e->getReason()) {
                throw new ExpiredTokenException();
            }

            throw new InvalidTokenException('Invalid JWT Token', 0, $e);
        }

        return $preAuthToken;
    }

    /**
     * Returns an user object loaded from a JWT token.
     *
     * {@inheritdoc}
     *
     * @param PreAuthenticationJWTUserToken $preAuthToken Implementation of the (Security) TokenInterface
     * @param ShopCustomerProvider $userProvider
     *
     * @return null|Shop
     *
     * @throws \InvalidArgumentException If preAuthToken is not of the good type
     * @throws InvalidPayloadException   If the user identity field is not a key of the payload
     * @throws UserNotFoundException     If no user can be loaded from the given token
     */
    public function getUser($preAuthToken, UserProviderInterface $userProvider): ?Shop
    {
        if (false === ($preAuthToken instanceof PreAuthenticationJWTUserToken)) {
            throw new \InvalidArgumentException(
                sprintf('The first argument of the "%s()" method must be an instance of "%s".', __METHOD__, PreAuthenticationJWTUserToken::class)
            );
        }

        $payload       = $preAuthToken->getPayload();
        $identityField = $this->jwtManager->getUserIdentityField();

        if (false === isset($payload[$identityField])) {
            throw new InvalidPayloadException($identityField);
        }

        $identity = $payload[$identityField];

        try {
            if (true === isset($payload['shop'])) {
                /** @var Shop $shop */
                $shop = $userProvider->loadShopByToken($payload['shop']);
            } else {
                return null;
            }

            if (null !== $shop && true === isset($payload['customer'])) {
                $customer = $userProvider->loadCustomerByToken($payload['customer']);
                $shop->setCustomer($customer);
            }

        } catch (UsernameNotFoundException $e) {
            throw new UserNotFoundException($identityField, $identity);
        }

        $this->preAuthenticationTokenStorage->setToken($preAuthToken);

        return $shop;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $authException): Response
    {
        $response = new JWTAuthenticationFailureResponse($authException->getMessageKey());

        if ($authException instanceof ExpiredTokenException) {
            $event = new JWTExpiredEvent($authException, $response);
            $this->dispatcher->dispatch(Events::JWT_EXPIRED, $event);
        } else {
            $event = new JWTInvalidEvent($authException, $response);
            $this->dispatcher->dispatch(Events::JWT_INVALID, $event);
        }

        return $event->getResponse();
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): void
    {
        return;
    }

    /**
     * {@inheritdoc}
     *
     * @return JWTAuthenticationFailureResponse
     */
    public function start(Request $request, AuthenticationException $authException = null): JWTAuthenticationFailureResponse
    {
        $exception = new MissingTokenException('JWT Token not found', 0, $authException);
        $event     = new JWTNotFoundEvent($exception, new JWTAuthenticationFailureResponse($exception->getMessageKey()));

        $this->dispatcher->dispatch(Events::JWT_NOT_FOUND, $event);

        return $event->getResponse();
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $shop): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException If there is no pre-authenticated token previously stored
     */
    public function createAuthenticatedToken(UserInterface $user, $providerKey): GuardTokenInterface
    {
        $preAuthToken = $this->preAuthenticationTokenStorage->getToken();

        if (null === $preAuthToken) {
            throw new \RuntimeException('Unable to return an authenticated token since there is no pre authentication token.');
        }

        $authToken = new JWTUserToken($user->getRoles(), $user, $preAuthToken->getCredentials(), $providerKey);

        $this->dispatcher->dispatch(Events::JWT_AUTHENTICATED, new JWTAuthenticatedEvent($preAuthToken->getPayload(), $authToken));
        $this->preAuthenticationTokenStorage->setToken(null);

        return $authToken;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsRememberMe(): bool
    {
        return false;
    }

    /**
     * Gets the token extractor to be used for retrieving a JWT token in the
     * current request.
     *
     * Override this method for adding/removing extractors to the chain one or
     * returning a different {@link TokenExtractorInterface} implementation.
     *
     * @return TokenExtractorInterface
     */
    protected function getTokenExtractor(): TokenExtractorInterface
    {
        return $this->tokenExtractor;
    }

    /**
     * Loads the user to authenticate.
     *
     * @param UserProviderInterface $userProvider An user provider
     * @param array                 $payload      The token payload
     * @param string                $identity     The key from which to retrieve the user "username"
     *
     * @return UserInterface
     */
    protected function loadUser(UserProviderInterface $userProvider, array $payload, $identity): UserInterface
    {
        if (true === ($userProvider instanceof JWTUserProvider)) {
            return $userProvider->loadUserByUsername($identity, $payload);
        }

        return $userProvider->loadUserByUsername($identity);
    }
}
