<?php
// Listing de toutes mes réservations

// Démarrage de la session
session_start();

// Si l'user n'est pas connecté on renvoie sur la home
if(empty($_SESSION['auth'])){  die( header('Location: /hg') ); }


require_once '../inc/pdo.php';

$title = 'Mes réservations';
require_once '../view/header.php';

if(!empty($_SESSION['info'])){ ?>

	<script>alert("<?=$_SESSION['info']?>");</script>
	<?php unset($_SESSION['info']);
	
}

$query = $pdo->prepare("SELECT * FROM booking WHERE user_id = :id");
$query->execute([
	':id' => $_SESSION['auth']['id']
]);

// Si on a des réservations à afficher
if($query->rowCount() > 0){

	$bookings = $query->fetchAll();

	echo '<ul>';

	foreach($bookings as $booking){

	echo '<li>N°'.$booking['id'].' - Du '.$booking['check_in'].' au '.$booking['check_out'].'</li>';

	}

	echo '</ul>';
}
else echo 'Vous n\'avez aucune réservation.';

require_once '../view/footer.php';
?>