<?php
// Listing de mes logements

// Démarrage de la session
session_start();

// Si l'user n'est pas connecté on renvoie sur la home
if(empty($_SESSION['auth'])){  die( header('Location: /hg') ); }


require_once '../inc/pdo.php';

$title = 'Mes logements';
require_once '../view/header.php';

if(!empty($_SESSION['info'])){ ?>

	<script>alert("<?=$_SESSION['info']?>");</script>
	<?php unset($_SESSION['info']);
	
} ?>

<!-- Sous-menu -->
<a href="../lodging_add.php">Ajouter un logement</a>
<hr>

<?php
$query = $pdo->prepare("SELECT * FROM lodging WHERE user_id = :id");
$query->execute([
	':id' => $_SESSION['auth']['id']
]);

// Si on a des logements à afficher
if($query->rowCount() > 0){

	$lodgings = $query->fetchAll();

	echo '<ul>';

	foreach($lodgings as $lodging){

	echo '<li><a href="lodging_view.php?id='.$lodging['id'].'">'.$lodging['address'].' '.$lodging['zipcode'].' '.$lodging['city'].'</a></li>';

	}

	echo '</ul>';
}
else echo 'Vous n\'avez pas ajouté de logement à louer.';

require_once '../view/footer.php';
?>