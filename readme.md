## Administration
* Gestion des contacts
* Afficher un contact
* Supprimer un contact
* Ajouter un objet
* Gestion des objets
* Modifier un objet
* Supprimer un objet

## Client
* Formulaire de contact
* Notification mail (multiple)
* Google map

## Dépendances
* GlobalBundle
* SweetAlert

## Installation

### Menu
```twig
{% set menuContact = ['admin_contact_manager','admin_contact_view','admin_contactobjet_manager', 'admin_contactobjet_ajouter', 'admin_contactobjet_modifier'] %}

<a href="#" data-nav="contact-menu" class="menuNav {{ getCurrentMenu(menuContact) }}"> <i class="fa fa-paper-plane-o"></i> Contacts <i class="fa fa-angle-right"></i></a>
<ul class="contact-menu {{ getCurrentMenu(menuContact) }}">
    <li class="{{ getCurrentMenu(['admin_contact_manager']) }}"><a href="{{ path('admin_contact_manager')}}">Gestion des contacts</a></li>
    <li class="{{ getCurrentMenu(['admin_contactobjet_ajouter']) }}"><a href="{{ path('admin_contactobjet_ajouter')}}">Ajouter un objet</a></li>
    <li class="{{ getCurrentMenu(['admin_contactobjet_manager']) }}"><a href="{{ path('admin_contactobjet_manager')}}">Gestion des objets</a></li>
</ul>
```

### Fichier
* app/AppKernel.php
```php
new ContactBundle\ContactBundle(),
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