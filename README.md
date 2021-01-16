### Environment

- Composer > 2.0 (composer self-update)
- PHP 8.0.1
- yarn
- Mysql 5.7

### Installation

- Clone du projet
- composer install
- yarn encore (dev/prod) (--watch) ; (Facultative, seulement pour le FRONT symfony pour les assets)
- doctrine:database:create = pour créer la db
- doctrine:migrations:migrate = pour créer les tables (execute le fichier de migration par défaut).
Possible à l'avenir de nouvelle exécution si changement ou MAJ des entités.
- MERCURE : https://github.com/dunglas/mercure/releases/tag/v0.10.4 (dernier version stable)
- symfony check:requirements (pour check si l'environnement php et good)

### .env

- Faire une copie du .env (par défaut) et configurer le .env.local pour un dev en local. Attention au .env et les données sensibles.

### API / GRAPQL

- Défaut via /api
- Graphql via /api/graphql

### Démarrage

- Symfony (API/Site) : symfony server:start = pour démarrer le serveur
- Mercure HUB : ./mercure --jwt-key='!ChangeMe!' --addr='localhost:3000' --allow-anonymous --cors-allowed-origins='*'