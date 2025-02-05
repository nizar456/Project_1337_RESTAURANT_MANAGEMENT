<h1>Présentation générale du projet</h1>
<p>Le projet 1337 RESTaurant est une initiative innovante visant à moderniser et à simplifier le processus de commande et de livraison des repas pour les étudiants. L'idée principale de ce projet est de permettre aux étudiants de commander leurs repas directement depuis une application web. Une fois la commande passée, le restaurant prépare le repas et le livre directement à l'étudiant. Cette approche élimine le besoin pour les étudiants de se déplacer et d'attendre leur repas dans la file d'attente, leur offrant ainsi une expérience de restauration plus pratique et efficace.</p>
<h1>Résumé du Projet</h1>
<p><b>Le système de gestion des repas à 1337 Coding School permet aux étudiants et aux administrateurs de gérer efficacement les repas quotidiens grâce à une série de pages web interconnectées. Voici un résumé des différentes pages et de leurs fonctionnalités principales :</b></p>

<p><b>1-Page de Connexion</b> Cette page permet aux utilisateurs de se connecter à leurs comptes, en fonction de leur rôle d'étudiant ou d'administrateur, grâce à leur nom d'utilisateur et mot de passe.</p>

<p><b>2-Dashboard Admin</b> Cette page fournit aux administrateurs une vue d'ensemble des statistiques importantes, telles que le nombre total d'étudiants, le nombre d'étudiants ayant mangé aujourd'hui, ainsi que les pourcentages de choix des repas principaux.</p>

<p><b>3-Today Menu (Admin)</b> Sur cette page, les administrateurs sélectionnent les repas disponibles pour la journée, incluant les repas principaux, secondaires, ainsi que les fruits et yaourts.</p>

<p><b>4-Commandes</b> Cette page permet aux administrateurs de visualiser et de confirmer les commandes reçues par les étudiants.</p>

<p><b>5-Add Products</b> Les administrateurs peuvent ajouter de nouveaux produits en renseignant le nom, l'image, et la catégorie des produits.</p>

<p><b>6-Today Menu (Étudiants)</b> Cette page permet aux étudiants de visualiser le menu du jour. Pour passer une commande, les étudiants doivent utiliser une autre page et commander entre 12h et 15h.</p>

<p><b>7-Command Food</b> Sur cette page, les étudiants sélectionnent les repas qu'ils souhaitent, en choisissant des quantités pour les fruits et les yaourts, et confirment leur commande.</p>

<h1>Technologies Utilisées</h1>
<p><ul>
    <li><strong>PHP</strong> : Utilisé pour la gestion des requêtes côté serveur et la manipulation des bases de données.</li>
    <li><strong>CSS</strong> : Employé pour styliser et améliorer l'apparence visuelle de l'application.</li>
    <li><strong>HTML</strong> : La structure de base de l'application web, permettant d'organiser le contenu de manière cohérente.</li>
    <li><strong>Bootstrap</strong> : Un framework CSS qui facilite la création de sites web réactifs et attrayants, garantissant une expérience utilisateur optimale sur différents appareils.</li>
    <li><strong>JavaScript</strong> : Utilisé pour ajouter des fonctionnalités dynamiques et interactives à l'application, améliorant l'expérience utilisateur.</li>
    <li><strong>MySQL</strong> : Utilisé pour gérer et stocker les données relatives aux commandes, aux utilisateurs, et aux menus de manière efficace et sécurisée.</li>
   </ul>
</p>

<h1>Explication des Pages de l'Application</h1>
<h2>Page de Connexion</h2>
<p>La page de connexion joue un rôle crucial dans la gestion des accès au système. Elle permet de diriger l'utilisateur vers les interfaces appropriées en fonction de leurs identifiants de connexion.
Lorsqu'un utilisateur arrive sur la page de connexion, il est invité à entrer son nom d'utilisateur et son mot de passe. Ces informations sont ensuite vérifiées par le système pour déterminer leur validité et le rôle de l'utilisateur.
Si les identifiants sont corrects, le système identifie le rôle de l'utilisateur. Deux rôles principaux sont gérés par notre système : les étudiants et les administrateurs.
<ul><li><strong>Connexion Étudiant:</strong><br>
Lorsqu'un étudiant se connecte, il est redirigé vers une interface qui lui est dédiée. Cette interface permet à l'étudiant d'accéder à diverses fonctionnalités comme consulter les menus, passer des commandes, et voir l'historique de ses commandes.
<li><strong>Connexion Administrateur:</strong><br>
Si un administrateur se connecte, il est redirigé vers une interface d'administration. Cette interface offre des fonctionnalités avancées telles que la gestion des menus, la gestion des utilisateurs, et l'accès aux rapports de commandes</ul></p>
<img src="images\connex.png" width="400" height="400" >

<h2>Dashboard</h2>
<p>Explication de la Page Dashboard de l'Administrateur
La page Dashboard de l'administrateur est un outil essentiel pour la gestion et le suivi des activités liées aux repas des étudiants. Elle fournit une vue d'ensemble claire et concise des statistiques importantes, aidant ainsi les administrateurs à prendre des décisions informées.<br>
Les principales fonctionnalités et statistiques disponibles sur cette page sont les suivantes :

<ul><li><strong>Nombre Total d'Étudiants :</strong><br>Cette section affiche le nombre total d'étudiants enregistrés dans le système. Elle permet à l'administrateur de connaître la taille de la population étudiante qui utilise le service de restauration.

<li><strong>Nombre d'Étudiants qui Mangent Aujourd'hui :</strong><br>
Cette statistique montre combien d'étudiants ont choisi de manger aujourd'hui. Cela aide à évaluer la participation quotidienne et à planifier les besoins en ressources alimentaires.
<li><strong>Statistiques sur les Choix de Repas :</strong><br>
Étant donné que les étudiants ont le choix entre deux principales options de repas, cette section du Dashboard affiche des statistiques sur leurs préférences. Elle indique le pourcentage d'étudiants qui ont choisi le repas principal numéro 1 par rapport au repas principal numéro 2 dans tous les menus disponibles.
<li><strong>Analyse des Préférences Alimentaires :</strong><br>
Cette section permet d'analyser les tendances alimentaires des étudiants. Elle peut aider à adapter les menus futurs en fonction des préférences des étudiants, optimisant ainsi la satisfaction et réduisant le gaspillage alimentaire.</ul></p>
<img src="images\dashbord.png">


