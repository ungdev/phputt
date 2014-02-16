<?php

/*
 * This file is part of the PhpUTT library.
 *
 * Titouan Galopin <galopintitouan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpUtt\Ldap;

/**
 * The LDAP layer is a class to retrieve users informations from UTT official LDAP.
 *
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
class LdapLayer
{
    protected $resource;

    public function __construct($host = 'ldap.utt.fr', $port = 389)
    {
        $this->resource = @ldap_connect($host, $port);

        if (! $this->resource) {
            throw new \RuntimeException(sprintf(
                'LDAP connection failed to %s:%s', $host, $port
            ));
        }
    }

    public function getUsers()
    {
        return $this->mapArray(ldap_get_entries($this->resource, ldap_list($this->resource, 'ou=people,dc=utt,dc=fr', 'uid=*')));
    }

    public function getUser($login)
    {
        return $this->mapOne(ldap_get_entries($this->resource, ldap_list($this->resource, 'ou=people,dc=utt,dc=fr', 'uid='.$login)));
    }

    protected function mapArray(array $datas)
    {
        $users = array();

        foreach ($datas as $data) {
            $users[] = $this->mapOne($data);
        }

        return $users;
    }

    protected function mapOne(array $data)
    {
        $user = new LdapUser();

        foreach ($data as $key => $value) {
            $user->$key = $value;
        }

        return $user;
    }
}
