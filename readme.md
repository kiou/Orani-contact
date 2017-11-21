## Administration
* Gestion des contacts
* Afficher un contact
* Supprimer un contact
* Ajouter un sujet
* Gestion des sujets
* Modifier un sujet
* Supprimer un sujet

## Client
* Formulaire de contact
* Notification mail
* Google map

## Dépendances
* GlobalBundle
* SweetAlert

## Installation

### Menu

### Fichier
* app/AppKernel.php
```php
new ContactBundle/ContactBundle(),
```
* app/config.yml
```yml
- { resource: "@ContactBundle/Resources/config/services.yml" }
```
* app/routing.yml
```yml
contact:
    resource: "@ContactBundle/Resources/config/routing.yml"
    prefix:   /
```
## Client
* Design disponible dans le dossier Install