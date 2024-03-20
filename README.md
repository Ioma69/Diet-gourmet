# Dietégourmet

# ECF entrainement Dietégourmet 2023 STUDI Sariel RAQUIN

possibilité de consulter des recettes, noter des recettes (si connecté). également d'uploader, modifier, ou supprimer une recette si connecté en tant qu'admin

## Lien vers le trello : (https://trello.com/b/pGkhreB8/ecf-quai-antique-sariel-raquin](https://trello.com/invite/b/8flL1IsF/ATTI92e7724ebbaa22ef7ee5ded52ba70de777C252BF/ecf-entrainement-sandrine-coupart-sariel-raquin)

## Lien vers le site internet en ligne (https://dietegourmet.herokuapp.com/)


1. Clôner le dépôt : git clone <URL_DU_DÉPÔT>
2. Dans le répertoire, tapez **composer install**, pour installer les dépendances nécessaires au projet.
3. Ensuite **php bin/console doctrine:database:create**, pour créer la base de données.
4. Ouvrez le fichier **.env** et recherchez la ligne contenant **DATABASE_URL**. Modifiez cette ligne en fonction des informations de connexion à votre base de données locale. Assurez-vous de fournir le bon nom d'utilisateur, mot de passe, hôte, port et nom de la base de données dans l'URL de connexion.
5. Puis tapez **php bin/console doctrine:migrations:migrate**, pour insérer les tables nécessaires au projet.
6. Et enfin **php bin/console doctrine:fixtures:load**, afin d'avoir des entrées dans la base de données, à savoir l'identifiant admin
7. Lancer le serveur : Démarrez le serveur Symfony en utilisant la commande suivante : **php bin/console server:start**
8. Accéder à l'application : Ouvrez votre navigateur et accédez à l'URL http://localhost:8000 pour accéder à l'application Symfony.
