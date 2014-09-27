# PHP UTT

PHP UTT est un set de composants écrits en PHP (PHP 5.3+) facilitant l'utilisation de ressources mises à disposition
des étudiants par l'UTT pour le développement de sites internet.

## Installation

L'installation se fait via Composer, un gestionnaire de paquets pour PHP. Vous devez donc installer PHP (si vous ne l'avez pas), puis Composer.

### Debian & Ubuntu

Si vous n'avez pas PHP, lancez :

    sudo apt-get install php5 php5-cgi php5-cli php5-common php5-curl php5-gd php5-mcrypt php5-mysql php5-fpm php5-intl php5-json php-apc php5-ldap php5-xdebug php5-dev

Dans le dossier de votre projet, exécutez ensuite :

    curl -sS https://getcomposer.org/installer | php
    
Cela vous créer un fichier composer.phar, utilisable avec `php composer.phar`.

### Windows

Téléchargez l'installateur Windows sur https://getcomposer.org/download/. Utilisez Composer avec `composer`.

### Composer

Une fois PHP et Composer installés, créez un fichier `composer.json` avec le contenu suivant :


``` json
{
    "require": {
        "ungdev/phputt": "dev-master"
    },
    "minimum-stability": "dev"
}
```

Cela indique à Composer que vous avez besoin de *phputt* pour votre projet. Lancez ensuite en ligne de commande :

    - `php composer.phar update` pour Debian / Ubuntu
    - `composer update` pour Windows
    
Composer installera alors dans un dossier `vendor` à la fois *phputt* et *phpCAS*, necéssaire à *phputt*.

L'installation terminée, vous pouvez utiliser la librairie en l'incluant avec un simple include de `vendor/autoload.php` :

``` php
<?php

require 'vendor/autoload.php';

$security = new PhpUtt\Cas\SecurityLayer();

// Retourne le login utilisateur. Redirige vers l'interface CAS si l'utilisateur n'est pas connecté.
$userLogin = $security->login();
```


## Usage

### CAS - Connexion utilisateur

La connexion utilisateur permet d'utiliser l'interface CAS officielle de l'UTT facilement.

> **Remarque** : vous **devez** utiliser un nom de domain approuvé par le CRI pour utiliser le CAS. Tous les noms de
> domaines en *.utt.fr sont par défaut approuvés.

> Si vous souhaitez tout de même pouvoir faire des essais en local, vous aurez remarqué que le CAS ne l'autorise pas, pour la raison évoquée ci-dessus. Une solution consiste à ajouter une entrée au fichier « hosts » (```C:\WINDOWS\system32\drivers\etc\hosts``` sous Windows, ```/etc/hosts``` sous Linux) pour faire le lien entre ```127.0.0.1```et ```test-local.utt.fr```.

#### Connexion

Pour connecter l'utilisateur grâce à CAS, il vous faut utiliser le `SecurityLayer`, une classe gérant l'appel à CAS.
Pour cela, lorsque vous souhaitez que l'utilisateur se connecte :

``` php
$security = new PhpUtt\Cas\SecurityLayer();

// Retourne le login utilisateur. Redirige vers l'interface CAS si l'utilisateur n'est pas connecté.
$userLogin = $security->login();
```

Il vous incombe tout de même de stocker ce login en session de votre coté, CAS ne le fera pas pour vous.

#### Déconnexion

De la même manière, lorsque vous souaitez déconnectez l'utilisateur de CAS, vous devez utiliser le `SecurityLayer` :

``` php
$security = new PhpUtt\Cas\SecurityLayer();

$security->logout();
```

> **Remarque** : déconnectez l'utilisateur de CAS ne supprime pas les sessions de votre coté, à vous de le faire.


### LDAP - Récupération d'informations utilisateurs

Un annuaire LDAP des étudiants est mis à disposition par l'UTT. Cette librairie permet d'utiliser le LDAP pour récupérer
les informations utilisateurs.

> **Remarque** : le LDAP n'est accessible que depuis l'intérieur du réseau de l'UTT (Wifi ou SIA).

Les méthodes disponibles sont :

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
