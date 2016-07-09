<?php

// src/AppBundle/Security/Authentication/Provider/WsseProvider.php

namespace AppBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use AppBundle\Security\Authentication\Token\WsseUserToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class WsseProvider implements AuthenticationProviderInterface {

    private $userProvider;
    private $cacheDir;
    private $encoder;

    public function __construct(UserProviderInterface $userProvider, $cacheDir, UserPasswordEncoderInterface $encoder) {
        $this->userProvider = $userProvider;
        $this->cacheDir = $cacheDir;
        $this->encoder = $encoder;
    }

    public function authenticate(TokenInterface $token) {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());
        if ($user && $this->encoder->isPasswordValid($user, $token->digest)) {
            $authenticatedToken = new WsseUserToken($user->getRoles());
            $authenticatedToken->setUser($user);
            return $authenticatedToken;
        }

        throw new AuthenticationException('The WSSE authentication failed.');
    }

    /**
     * This function is specific to Wsse authentication and is only used to help this example
     *
     * For more information specific to the logic here, see
     * https://github.com/symfony/symfony-docs/pull/3134#issuecomment-27699129
     */
    protected function validateDigest($digest, $nonce, $salt, $secret) {
        $passwordValid = $this->encoder->isPasswordValid($user, $token->getCredentials());
        echo $this->encodePassword($digest, $salt) . "------------------------" . $secret;
        $expected = base64_encode(sha1(base64_decode($nonce) . $secret, true));
        return hash_equals($expected, $digest);
    }

    public function supports(TokenInterface $token) {
        return $token instanceof WsseUserToken;
    }

    public function encodePassword($raw, $salt) {

        $salted = $this->mergePasswordAndSalt($raw, $salt);
        $digest = hash('sha512', $salted, true);

        // "stretch" hash
        for ($i = 1; $i < 5000; ++$i) {
            $digest = hash('sha512', $digest . $salted, true);
        }

        return base64_encode($digest);
    }

    protected function mergePasswordAndSalt($password, $salt) {
        if (empty($salt)) {
            return $password;
        }

        if (false !== strrpos($salt, '{') || false !== strrpos($salt, '}')) {
            throw new \InvalidArgumentException('Cannot use { or } in salt.');
        }

        return $password . '{' . $salt . '}';
    }

}
