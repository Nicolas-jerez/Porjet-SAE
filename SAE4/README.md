
# Tutoriel d'introduction à Symfony 6 #
Symfony version 6.2, 7 janvier 2023

Cette partie a été préparée en suivant la documentation Symfony (chapitre *Getting started*) :
[http://symfony.com/doc/current/index.html](http://symfony.com/doc/current/index.html).

## Installation ##

Ce projet contient une version installée de Symfony, il est donc inutile de le réinstaller. Pour les
plus pressés, aller directement à la section "Pour utiliser ce projet".

Symfony 6 propose deux versions à installer : soit une version très légère, convenant à la création
d'API de type REST, soit une version plus complète pour la création de sites Web. C'est cette
deuxième version que nous allons utiliser.

On peut installer Symfony soit avec l'utilitaire `symfony` (installé sur les Linux de l'IUT) soit
avec l'utilitaire de gestion de paquets PHP `composer` (également installé à l'IUT).

Avec `symfony` : 

```bash
symfony new Symfony6-PriseEnMain --webapp
```

Avec `composer` :

```bash
composer create-project symfony/skeleton Symfony6-PriseEnMain
cd Symfony6-PriseEnMain
composer require webapp
```

La différence est que `symfony` configure le projet pour `git` (avec un `git init`) et ajoute le
serveur web de développement (qu'on peut lancer avec `symfony server:start` et arrêter avec `symfony
server:stop`).  On préférera donc en général utiliser l'application `symfony` pour créer de nouveaux
projets.

La configuration de cette application Web classique contient :
- **Vues :** `Twig` (gestionnaire de templates), `asset` (fichiers css, js, img), `form`
  (formulaires) ;
- **Contrôleurs :** `security-bundle` (pour les utilisateurs et les droits d'accès), `form` ;
- **Modèles :** `doctrine/orm` (ORM : *Object Repository Manager*, pour l'accès à la base de
  données), `maker-bundle` (outils d'aide à la création de code) ;
- **Outils bien pratiques :** `web-profiler-bundle` (outils de débogage), un serveur web intégré,
  pour le développement, `flex` (gestion simplifiée des paquets composer).

Nous avons ajouté à l'installation de base le paquet `twig/intl-extra` :
```bash
composer require twig/intl-extra
```
Ce paquet permet d'afficher les dates en français dans les vues Twig.

Nous avons enlevé le *bundle* `sensio/framework-extra-bundle` annoncé comme obsolète par `composer`
mais quand même installé par `symfony` :
```bash
composer remove sensio/framework-extra-bundle
```

Enfin nous avons créé un fichier `.php-version` contenant `8.1` pour forcer Symfony à utiliser la
version 8.1 de PHP. Notez que l'intranet utilise la version 7.4 mais le serveur web de `gigondas`
est en 8.1.

## Quelques ressources pour les vues ##

Pour démarrer la création du design de votre application, vous trouverez dans `public` :

- `js` : répertoire contenant le code JavaScript pour Bootstrap-5.2 (la version `bundle` contient
  `popper`) ;
- `css` : feuilles de style de Bootstrap, Bootstrap Icons ainsi que `main.css` pour les adaptations
  locales ;
- `img` : le bandeau de `gigondas`, le logo de l'IUT et l'icône de l'UGA.

Ces ressources sont importées dans le fichier `templates/base.html.twig`.

## Pour utiliser ce projet ##

Clonez-le dans votre compte (notez qu'il n'est pas indispensable de le mettre dans votre dossier
`public_html` mais cela laisse la possibilité de le tester avec le serveur Web de `gigondas`.

```bash
cd public_html
git clone git@gitlab.iut-valence.fr:symfony/Symfony6-PriseEnMain.git
```

ou encore :

```bash
cd public_html
git clone https://gitlab.iut-valence.fr/symfony/Symfony6-PriseEnMain.git
```

Ceci fait vous devez installer les modules Symfony (qui ne sont pas intégrés au projet Git) avec :

```bash
cd Symfony6-PriseEnMain
composer install
```

C'est un peu long lors de la première installation mais on ne le répétera pas.

**Vous êtes prêts à travailler avec Symfony 6 !**

## Lancement du serveur de développement ##

Symfony permet d'utiliser le serveur intégré Web intégré à PHP (depuis la version 5) comme serveur
de développement.  Cela vous évite d'avoir à installer un serveur Apache ou NGinx et même si vous en
avez déjà un, c'est souvent plus rapide/pratique d'utiliser le serveur Symfony.

Pour le lancer, ouvrez un nouveau terminal (`Ctrl-Shift-N`) puis :

```bash
cd Symfony6-PriseEnMain   # le cas échéant
symfony server:start
```

Pour l'arrêter :

```bash
symfony server:stop
```

Vous devez pouvoir visualiser votre application à l'URL :
[http://localhost:8000](http://localhost:8000)

Vous obtenez une page d'accueil de Symfony (page affichée si aucune route n'a été créée dans
l'application, ce qui est le cas pour nous).

**Astuce** créez un alias pour lancer le serveur et pour l'arrêter, par exemple :
```bash
alias start='xterm -e symfony server:start &'
alias stop='symfony server:stop'
```

L'alias `start` lance un terminal `xterm` dans lequel il exécute le serveur Symfony.

## La commande `console` ##

Le programme `console` fournit un lot d'utilitaires destinés à afficher des informations sur l'état
de l'application, à générer des bouts de code, à interagir avec la base de données,... Pour avoir la
liste des possibilités :

```bash
bin/console
```

Nous en utiliserons quelques-unes au fur et à mesure des besoins. Notez que vous pouvez obtenir de
l'aide sur une commande avec par exemple :

```bash
bin/console help make:controller
```

## La première page ##

Une page Web est accédée par une URL qui déclenche l'interprétation d'un programme PHP produisant le
code HTML à afficher.

En Symfony on parle de *route* : association d'une URL et d'un *nom*. L'intérêt de cette séparation
est que le nom de la route, utilisé dans le code de l'application, ne change jamais, alors que l'URL
peut changer au gré des évolutions du site ou en fonction de la langue préférée de l'utilisateur. 

Une route est associée à une *action* (méthode) dans un *contrôleur* (classe héritant de
`AbstractController`). Pour afficher une page, le contrôleur va *rendre* (calculer) une *vue*
(*template* Twig pour l'instant).

**Pour créer une page, il faut donc créer :**

- un contrôleur contenant au moins une méthode (action) ;
- une vue qui sera affichée par l'action ;
- associer l'action du contrôleur à une route (URL, nom).

On peut tout faire avec un bon vieil éditeur de texte mais on peut aussi utiliser le *maker* de
Symfony :

```bash
bin/console make:controller Bonjour
```

Ouvrez le fichier `src/Controller/BonjourController.php` créé par cette commande, vous pouvez voir
l'*attribut* PHP ajouté pour définir la route : `#[Route('/bonjour', name: 'bonjour')]`.

Vous pouvez lister toutes les routes définies par votre application avec :
    
```bash
bin/console debug:router
bin/console debug:router --env=prod
```

Vous pouvez voir la page `bonjour` avec l'URL :
[http://localhost:8000/bonjour](http://localhost:8000/bonjour).  Elle affiche une vue par défaut
créée par le *maker*.

Les vues sont rangées dans le répertoire `templates` et on ajoute en général un répertoire par
contrôleur. La vue que vous voyez est donc dans `templates/bonjour/index.html.twig`.

Ouvrez le fichier `templates/bonjour/index.html.twig` et remplacez son contenu par :

```twig
{% extends 'base.html.twig' %}

{% block title %}Bienvenue{% endblock %}

{% block body %}
<div class="row justify-content-center">
    <h1 class="col-6">Bonjour le monde !</h1>
</div>
{% endblock %}
```

Rechargez la page `bonjour` et appréciez la différence... Pour comprendre, ouvrez aussi
`templates/base.html.twig`.

Ce *layout* est préparé pour utiliser *Bootstrap*. On remarque que les CSS et les librairies
JavaScript pour Bootstrap sont toujours importés dans la page, tandis que la feuille de style
`main.css` est intégrée au bloc *stylesheets*. En héritant de ce layout, on peut redéfinir les blocs
et donc ajouter des feuilles de styles (bloc *stylesheets*) et du code JavaScript (bloc
*javascripts* à la fin de la page).

## Ajoutons un paramètre à la route ##
Pour jouer, on peut ensuite ajouter un paramètre à la page `bonjour` :

- modifiez la route du contrôleur : `#[Route('/bonjour/{nom}', name: 'bonjour')]`
- puis les paramètres de index : `index(string $nom = "joyeux contribuable")`
- passez le paramètre à la vue : `render('bonjour/index.html.twig', ['nom' => $nom]);`

Enfin, modifiez la vue pour intégrer le paramètre : `<h1>Bonjour {{nom}} !</h1>`

Essayez : [http://localhost:8000/bonjour](http://localhost:8000/bonjour)
puis [http://localhost:8000/bonjour/Georges](http://localhost:8000/bonjour/Georges).
C'est beau non ?

Notez que si on ne définit pas de valeur par défaut pour le paramètre `nom` de `index`, la route
devra obligatoirement définir ce paramètre : on pourra utiliser `/bonjour/Georges` mais pas
`/bonjour`. Faites l'essai pour être convaincu.

Pour utiliser une route dans une vue, on utilise la fonction Twig `path`. On peut par exemple
ajouter à notre vue un lien vers la page `bonjour` sans paramètre (mise en forme Bootstrap) :

```twig
<a class="btn btn-primary" href="{{ path('bonjour') }}" role="button">Félicitez le contribuable</a>
```

L'argument de `path` est le nom de la route, pas une URL. On peut ajouter des paramètres à la route :

```twig
<a class="btn btn-success" href="{{ path('bonjour', {'nom': "le monde"}) }}" role="button">Saluez le monde</a>
```

Essayez ces deux liens/boutons dans votre page `bonjour`.

**That's all, folks !**
