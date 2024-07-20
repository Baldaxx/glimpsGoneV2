# Glimps Gone V2

Glimps Gone V2 est une galerie d'art en ligne innovante où l'art invisible prend vie grâce à l'imagination des visiteurs. Ce projet utilise une architecture MVC et intègre des technologies modernes pour offrir une expérience utilisateur exceptionnelle.

---

## Table des matières

- [Installation et Configuration](#installation-et-configuration)
- [Base de Données](#base-de-données)
- [Exécution de l'Application](#exécution-de-lapplication)
- [Contributeurs](#contributeurs)

---

## Installation et Configuration

### Prérequis

- PHP 7.4 ou supérieur
- Composer
- WampServer

### Configuration de l'environnement

1. Cloner le dépôt :

```sh
git clone <https://github.com/Baldaxx/glimpsgonev2.git>
cd glimpsgonev2
```

2. Installer les dépendances PHP :

```sh
composer install
```

### Configurer les variables d'environnement

Copier le fichier .env.example et modifier les valeurs selon votre configuration et l'enregistrer en .env

```
DB_HOST=localhost:3306
DB_NAME=glimpsgone
DB_USER=root
DB_PASS= Votre mot de passe
```

Copier le fichier .configExemple et modifier les valeurs selon votre configuration et l'enregistrer en .config.php

```php
<?php

const DB = [
    "name" => "root",
    "user" => "root",
    "password" => "root",
    "host" => "localhost:3306",
];

const APP_NAME = "root";
```

---

### Base de Données

Création de la base de données :

```sh
php database/resetDb.php
```

---

### Exécution de l'Application

Télécharger et installer WampServer.

Placer le projet dans le répertoire www de WampServer par exemple :    
`C:\wamp64\www\glimpsgonev2`

Démarrer WampServer et s'assurer que les services Apache et MySQL sont en cours d'exécution.

Ouvrir votre navigateur et accéder à l'application à l'adresse suivante :    
http://localhost/glimpsgonev2

---

### Contributeurs

Virginie Baldacchino
