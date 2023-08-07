Pour faire fonctionner l'API

1/ Le dump de la base de données se trouve dans ./dump_store.sql
2/ Créer un alias /store qui pointe vers public/StoreAPI.php
3/ L'utilisation des méthodes de l'API sont expliqué en commentaires dans StoreAPI.php


_______________________________

Voici les fonction développé pour l'API:

GET         /store/
            /store/{id}
            /store?find=[name]
POST        /store
PUT         /store/{id}
DELETE      /store/{id}