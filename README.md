# Rent Car
## Site de location

# Code source de mon cours sur Symfony disponible => [ici](https://www.udemy.com/draft/2045032/)

## Installation

Si vous avez déjà le projet en local ignorez l'étape "Clonez le projet"

##### Clonez le projet 

`$ git clone https://github.com/ismail1432/rent-a-car.git`

##### Allez dans le répertoire

`$ cd rent-a-car`

Installer les dépendances (Vendor)

`$ composer install`

##### Creation de la Base de données et des tables

Editez la ligne 18 du .env pour mettre vos informations

`$ php bin/console doctrine:database:create`

`$ php bin/console doctrine:migrations:migrate`

##### Chargez les fixtures Fixtures

`$ php bin/console hautelook:fixtures:load --no-interaction`

##### Lancer l'application application

`$ php bin/console server:run`

##### Lancer les tests Behat

`$ vendor/bin/behat`

##### Lancer les tests PHPUnit

`$ bin/phpunit`

-----

##### Pour se connectez aller sur l'url  `/login`

Utilisateur avec ROLE_USER

email : user@email.fr /
password : user


Utilisateur avec ROLE_ADMIN

email : admin@email.fr /
password : admin
