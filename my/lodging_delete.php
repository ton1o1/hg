<?php
// Suppression d'un logement

// Démarrage de la session
session_start();

// Si l'user n'est pas connecté ou qu'il n'y a pas de logement renseigné, on renvoie sur la home
if(empty($_SESSION['auth']) || empty($_GET['id'])){  die( header('Location: /hg') ); }

require_once '../inc/pdo.php';

$lodgingId = (int) $_GET['id'];

// Vérification existence du logement
$query = $pdo->prepare("SELECT COUNT(*) as lodgingFound FROM lodging WHERE user_id = :userId AND id = :lodgingId");
$query->execute([
	':userId' => $_SESSION['auth']['id'],
	':lodgingId' => $lodgingId,
]);
$result = $query->fetch();

// Si le logement existe et qu'il appartient bien à l'user connecté
if($result['lodgingFound'] == 1){

	// On supprime le logement
	$query = $pdo->prepare("DELETE FROM lodging WHERE id = :lodgingId");
	$success = $query->execute([
		':lodgingId' => $lodgingId,
	]);

	if($success){
		// Si la requête a bien été effectuée, on sauvegarde un message de succès dans la session
		$_SESSION['info'] = 'Votre logement a bien été supprimé.';
	}
	else{
		$_SESSION['info'] = 'Une erreur est survenue !';
	}

	// Retour sur la page de gestion des logements
	die( header('Location: lodgings.php') );
}
// Retour sur la home
else die( header('Location: /hg') );
?>