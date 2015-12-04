<?php
// Listing de mes logements

// Démarrage de la session
session_start();

// Si l'user n'est pas connecté on renvoie sur la home
if(empty($_SESSION['auth'])){  die( header('Location: ./') ); }

$title = 'Mes logements';
require_once '../view/header.php';

require_once '../inc/pdo.php';

$query = $pdo->prepare("SELECT * FROM lodging, user WHERE lodging.user_id = user.id AND user.id = :id");
$query->execute([
	':id' => $_SESSION['auth']['id']
]);
$lodgings = $query->fetchAll();

echo '<ul>'

foreach($lodgings as $lodging){

echo '<li><a href="lodging_view.php?id='.$lodging['id'].'">'.$lodging['address'].' '.$lodging['zipcode'].' '.$lodging['city'].'</a></li>';

}

echo '</ul>';

require_once '../view/footer.php';
?>