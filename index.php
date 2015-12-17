<?php
	// index.php 
	// v1.1
	// 10:12 17/12/2015
	// hg

$title = 'Accueil';
require_once './view/header.php';

if(!empty($_SESSION['info'])){ ?>

	<script>alert("<?=$_SESSION['info']?>");</script>
	<?php unset($_SESSION['info']);
	
}

// L'user est-il connecté ?
if(!empty($_SESSION['auth'])){
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