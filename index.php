<?php
// Démarrage de la session
session_start();

// L'user est-il connecté ?
if(!empty($_SESSION['auth'])){
	echo 'Bonjour ' . $_SESSION['auth']['firstname'] . ' ' . $_SESSION['auth']['lastname'] . ' !';
?>
<hr>
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
?>