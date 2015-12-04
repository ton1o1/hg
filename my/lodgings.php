<?php
// Listing de mes logements

// Démarrage de la session
session_start();

// Si l'user n'est pas connecté on renvoie sur la home
if(empty($_SESSION['auth'])){  die( header('Location: ./') ); }

$title = 'Mes logements';
require_once './view/header.php';
?>
<ul>
<li><a href="./lodging_search.php">Rechercher un logement</a></li>
<li><a href="./my/bookings.php">Mes réservations</a></li>
<li><a href="./lodging_add.php">Ajouter un logement</a></li>
<li><a href="./my/lodgings.php">Gérer mes logements</a></li>
<li><a href="./logout.php">Déconnexion</a></li>
</ul>
<?php
}
else{
?>
<ul>
<li><a href="login.php">Connexion</a></li>
<li><a href="register.php">Inscription</a></li>
</ul>
<?php
}

require_once './view/footer.php';
?>