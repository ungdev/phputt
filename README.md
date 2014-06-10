# PHP UTT

PHP UTT est un set de composants écrits en PHP (PHP 5.3+) facilitant l'utilisation de ressources mises à disposition
des étudiants par l'UTT pour le développement de sites internet.

## Installation

L'installation se fait via Composer, un gestionnaire de paquets pour PHP. Vous devez donc installer PHP (si vous ne l'avez pas), puis Composer.

### Debian & Ubuntu

Si vous n'avez pas PHP, lancez :

    ```
    sudo apt-get install php5 php5-cgi php5-cli php5-common php5-curl php5-gd php5-mcrypt php5-mysql php5-fpm php5-intl php5-json php-apc php5-ldap php5-xdebug php5-dev
    ```

Dans le dossier de votre projet, exécutez ensuite :

    ```
    curl -sS https://getcomposer.org/installer | php
    ```
    
Cela vous créer un fichier composer.phar, utilisable avec `php composer.phar`.

### Windows

Téléchargez l'installateur Windows sur https://getcomposer.org/download/. Utilisez Composer avec `composer`.

### Composer

Ensuite, dans un fichier `composer.json` :


``` json
{
    "require": {
        "ungdev/phputt": "dev-master"
    },
    "minimum-stability": "dev"
}
```


## Usage

### CAS - Connexion utilisateur

La connexion utilisateur permet d'utiliser l'interface CAS officielle de l'UTT facilement.

> **Remarque** : vous **devez** utiliser un nom de domain approuvé par le CRI pour utiliser le CAS. Tous les noms de
> domaines en *.utt.fr sont par défaut approuvés.

``` php
$security = new PhpUtt\Cas\SecurityLayer();

// Retourne le login utilisateur. Redirige vers l'interface CAS si l'utilisateur n'est pas connecté.
$userLogin = $security->login();

// Déconnecte l'utilisateur du CAS. Vous devez toujours supprimer la session courante de votre script PHP.
$security->logout();
```

### LDAP - Récupération d'informations utilisateurs

Un annuaire LDAP des étudiants est mis à disposition par l'UTT. Cette librairie permet d'utiliser le LDAP pour récupérer
les informations utilisateurs.

> **Remarque** : le LDAP n'est accessible que depuis l'intérieur du réseau de l'UTT (Wifi ou SIA).

``` php
$ldap = new PhpUtt\Ldap\LdapLayer();

// Retourne un objet décrivant l'utilisateur
$user = $ldap->getUser($login);

// Retourne un objet décrivant l'association
$orga = $ldap->getOrga($login);

// Retourne un tableau de tous les utilisateurs
$users = $ldap->getUsers();

// Retourne un tableau de tous les étudiants (personnel de l'UTT retiré)
$students = $ldap->getStudents();

// Retourne un tableau de toutes les associations
$users = $ldap->getOrgas($login);
```

## Credits

- [Titouan Galopin](https://github.com/tgalopin)
- [All Contributors](https://github.com/tgalopin/annotations/contributors)


## License

The MIT License (MIT). Please see [License File](https://github.com/tgalopin/annotations/blob/master/LICENSE) for more information.
