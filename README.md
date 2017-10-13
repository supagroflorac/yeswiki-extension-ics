# ICS

Extension pour YesWiki permettant d'afficher un calendrier ICS accessible via
une URL.

## Installation :
```sh
git clone https://github.com/supagroflorac/yeswiki-extension-ics.git [racine_du_wiki]/tools/ics
cd [racine_du_wiki]/tools/ics
composer install
```

Pour installer composer : https://getcomposer.org/download/

## Usage :
```
{{ics src='url_du_calendrier' template='nom_du_template'}}
```

Templates disponibles :
 - minical (affiche le mois en cours. Les jours avec des évènements sont mis en avant et les évènement sont affiché au survol)
