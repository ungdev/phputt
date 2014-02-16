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
    public function __construct($version = '1.0', $host = 'cas.utt.fr', $port = 443, $path = '/cas/', $regenerateSession = false)
    {
        \phpCAS::client($version, $host, $port, $path, $regenerateSession);
        \phpCAS::setNoCasServerValidation();
    }

    public function login()
    {
        \phpCAS::forceAuthentication();
        return \phpCAS::getUser();
    }

    public function logout($domain = false)
    {
        if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off') {
            $method = 'http';
        } else {
            $method = 'https';
        }

        if (! $domain) {
            $domain = $this->getDomainName();

            if (! $domain) {
                throw new \RuntimeException(
                    'Domain name automatic detection failed. You have to provide the domain name in '.__CLASS__.'::'.__METHOD__
                );
            }
        }

        return \phpCAS::logoutWithRedirectService($method.'://'.$domain);
    }

    /**
     * @return string
     */
    protected function getDomainName()
    {
        $host = false;

        if ($host = $_SERVER['HTTP_X_FORWARDED_HOST']) {
            $host = trim(end(explode(',', $host)));
        } else {
            if (! $host = $_SERVER['HTTP_HOST']) {
                if (! $host = $_SERVER['SERVER_NAME']) {
                    $host = ! empty($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : false;
                }
            }
        }

        return $host;
    }
}
