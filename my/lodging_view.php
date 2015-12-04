<?php
// Gestion d'un logement

// Démarrage de la session
session_start();

// Si l'user n'est pas connecté ou qu'il n'y a pas de logement renseigné, on renvoie sur la home
if(empty($_SESSION['auth']) || empty($_GET['id'])){  die( header('Location: /hg') ); }

require_once '../inc/pdo.php';

// Vérification existence du logement
$query = $pdo->prepare("SELECT * FROM lodging WHERE user_id = :userId AND id = :lodgingId");
$query->execute([
	':userId' => $_SESSION['auth']['id'],
	':lodgingId' => (int) $_GET['id'],
]);

// Si le logement existe et qu'il appartient bien à l'user connecté
if($query->rowCount() == 1){
	
	$title = 'Mon logement';
	require_once '../view/header.php';

	// On fetch les données
	$lodging = $query->fetch();

	echo 'Gestion du logement à l\'adresse : '.$lodging['address'].' '.$lodging['zipcode'].' '.$lodging['city'];
	echo '
		<ul>
			<li><a href="lodging_delete.php?id='.$lodging['id'].'">Supprimer</a></li>
		</ul>';

	require_once '../view/footer.php';
}
else die( header('Location: /hg') );
?>