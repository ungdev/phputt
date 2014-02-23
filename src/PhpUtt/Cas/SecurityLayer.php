<?php

/*
 * This file is part of the PhpUTT library.
 *
 * Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpUtt\Cas;

/**
 * The security layer is the absreaction class to log in and log out users using the UTT CAS.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class SecurityLayer
{
    /**
     * Constructor
     *
     * @param string $version
     * @param string $host
     * @param int $port
     * @param string $path
     * @param bool $regenerateSession
     */
    public function __construct($version = '1.0', $host = 'cas.utt.fr', $port = 443, $path = '/cas/', $regenerateSession = false)
    {
        \phpCAS::client($version, $host, $port, $path, $regenerateSession);
        \phpCAS::setNoCasServerValidation();
    }

    /**
     * Log in the user. Ask for the login/password using the CAS interface if the user user is not already connected.
     *
     * @return string
     */
    public function login()
    {
        \phpCAS::forceAuthentication();
        return \phpCAS::getUser();
    }

    /**
     * Log out the user from CAS, and redirect to given URL.
     *
     * Note that the PHP session for the current script will be completely cleared:
     * any other sesison won't be available anymore.
     *
     * @param string|bool $redirect
     * @throws \RuntimeException
     */
    public function logout($redirect = false)
    {
        // Destroy current session
        $_SESSION = array();
        @session_destroy();

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }

        // Log out from CAS
        if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off') {
            $method = 'http';
        } else {
            $method = 'https';
        }

        if (! $redirect) {
            $redirect = $this->getDomainName();

            if (! $redirect) {
                throw new \RuntimeException(
                    'Redirection URL automatic detection failed. You have to provide the reciredtion URL in '.__CLASS__.'::'.__METHOD__
                );
            }
        }

        return \phpCAS::logoutWithRedirectService($method.'://'.$redirect);
    }

    /**
     * Get the domain name, or automatic domain detection and proper automatic redirection.
     *
     * @return string
     */
    protected function getDomainName()
    {
        if (! empty($_SERVER['HTTP_X_FORWARDED_HOST']) && $host = $_SERVER['HTTP_X_FORWARDED_HOST']) {
            $host = trim(end(explode(',', $host)));
        } else {
            if (empty($_SERVER['HTTP_HOST']) || ! $host = $_SERVER['HTTP_HOST']) {
                if (empty($_SERVER['SERVER_NAME']) || ! $host = $_SERVER['SERVER_NAME']) {
                    $host = ! empty($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : false;
                }
            }
        }

        return $host;
    }
}
