# Répertoire app

Le répertoire app contient deux répertoires.

  - config
  - Resources

## Répertoire config

Ce répertoire contient tous les fichiers de configurations des bundles/modules/plugins utilisés dans notre application. Les fichiers contenus dans ce répertoire ne doivent pas être modifiés.

## Répertoire Resources

Ce répertoire contient tous les fichiers nécessaires à la gestion des vues et traductions de notre application.

  - FOSUserBundle
Ce répertoire contient les vues ( views ) et traductions du bundle qui gère les utilisateurs.
    - translations
Ici vous trouverez deux types de fichiers. Les fichiers validators.LANGUE.yml contient les traductions des messages d'erreurs qui s'affichent lorsqu'on complète un formulaire ( champ username non rempli, adresse email invalide etc etc ) utilisé pour la gestion des utilisateurs ( formulaire de connexion, formulaire de modification du profil, formulaire de renouvellement de mot de passe etc etc ). Les fichiers FOSUserBundle.LANGUE.yml contient toutes les autres traductions liés à la gestion utilisateurs ( label de formulaire, bouton de formulaire, titre de page de connexion, titre de fenetre de connexion etc etc ).
    - views
Ici vous trouverez toutes les vues ( fichiers HTML ) utilisés pour la gestion utilisateur. Notre formulaire de connexion ( dans le répertoire sécurity ), page de profile utilisateur, etc etc. Ne vous formalisez pas sur l'extension .yml. En ouvrant les fichiers vous reconnaitrez les balises HTML que vous connaissez avec quelques variables supplémentaires ( ce qui remplace les <?php echo "toto" ?> par exemple )
  - views
Ici vous trouverez tous les autres fichiers utilisés pour  générer les vues des autres pages. J'ai divisé cela par habitude en deux sous-repertoires ( front contient les vues des pages publics de l'application, back contient les vues des pages admin - dans notre cas particulier ce seront les pages  pour nos commanditaires )

# Répertoire src
Ce répertoire contient le coeur de l'application. Par convention, sous Symfony, il est recommandé de créer des sous-répertoires ( AppBundle étant créé par défault ). Pour les grosses applications, on créera un sous-répertoire par "fonctionnalité" de l'application ( ex: si notre application contenait une gestion d'article, blog, on créerait un répertoire BlogBundle, pour une partie ecommerce, on créerait un répertoire EcommerceBundle etc ). Dans notre cas, tout le coeur de l'application sera dans AppBundle ).
 - les détails des sous-répertoires de AppBundle seront rédigés  plus tard )

# Répertoire tests
Comme son nom  l'indique, ce répertoire est utilisé pour créer des tests. Personnellement, je ne l'ai jamais utilisé.Bouh, pas bien, bla bla bla.

# Répertoire web
Ce répertoire contient tous les fichiers publics de l'application. ( les fichiers images, les fichiers css, fichiers js, fichiers fonts etc etc.). Je rajouterai au fur et à mesure le détail du contenu. A ce stade, rien de compliqué, il suffit de lire. =)
