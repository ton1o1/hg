<?php
// On arrive sur cette page après avoir cliqué sur un logement

// - Descriptif du logement
// - Calendrier des dispos
// - Formulaire de réservation

// Démarrage de la session
session_start();

// Si l'user n'est pas connecté ou qu'aucun logement n'est renseigné, on renvoie sur la home
if(empty($_SESSION['auth']) || empty($_GET['id'])){  die( header('Location: ./') ); }

require_once 'inc/pdo.php';

$query = $pdo->prepare("SELECT * FROM lodging WHERE id = :id");
$query->execute([
	':id' => $_GET['id']
]);

// Si on a un logement à afficher
if($query->rowCount() > 0){

	$lodging = $query->fetch();

	$title = 'Réserver ce logement';
	require_once 'view/header.php';

	if(!empty($_SESSION['info'])){ ?>

		<script>alert("<?=$_SESSION['info']?>");</script>
		<?php unset($_SESSION['info']);
		
	}

?>

<div id="calendar" data-toggle="calendar" ></div>

<hr>

<form method="post">
Réserver du <input type="text" name="booking[checkin]" id="checkin" /> au <input type="text" name="booking[checkout]" id="checkout" /> <input type="submit" value="Go !" />
</form>
<?php
	require_once 'view/footer.php';
}
else die( header('Location: ./') );
?>