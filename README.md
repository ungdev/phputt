# PHP UTT

PHP UTT est un set de composants écrits en PHP (PHP 5.3+) facilitant l'utilisation de ressources mises à disposition
des étudiants par l'UTT pour le développement de sites internet.

## Install

Via Composer

``` json
{
    "require": {
        "ungdev/phputt": "dev-master"
    }
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
