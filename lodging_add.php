<?php
// Formulaire d'ajout de logement

// Démarrage de la session
session_start();

// Si l'user n'est pas connecté, on redirige sur la home
if(empty($_SESSION['auth'])){  die( header('Location: ./') ); }

require_once 'inc/pdo.php';

if($_POST){

// Le formulaire est-il soumis ?
if ( !empty($_POST['lodging_add']['submit']) ) {

    // Si le tous les champs du formulaire sont remplis
    if ( !empty($_POST['lodging_add']['address']) && !empty($_POST['lodging_add']['zipcode']) && !empty($_POST['lodging_add']['city']) && !empty($_POST['lodging_add']['capacity']) ) {

    	// Enregistrement du logement dans la database
        $query = $pdo->prepare("INSERT INTO lodging VALUES('', :userId, :address, :zipcode, :city, :capacity);");
        $success = $query->execute([
            ':userId' => $_SESSION['auth']['id'],
            ':address' => $_POST['address'],
            ':zipcode' => $_POST['zipcode'],
            ':city' => $_POST['city'],
            ':capacity' => $_POST['capacity'],
        ]);

        if($success){
			// Si la requête a bien été effectuée, on sauvegarde un message de succès dans la session
			$_SESSION['info'] = 'Votre logement a bien été ajouté.';
		}
		else{
			$_SESSION['info'] = 'Une erreur est survenue !';
		}

        // Sauvegarde des photos
        // Liste des formats de fichier autorisés
        $allowed =  array('gif', 'png', 'jpg');
		// Numéro initial de la photo
        $i = 1;
        // Boucle sur toutes les photos uploadées
        foreach ($_FILES["picture"] as $picture) {
    		$filename = $picture['name'];
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			// S'il s'agit bien d'une image
			if( in_array($ext, $allowed) ) {
				$tmp_name = $picture["tmp_name"];
				// On sauvegarde la photo dans le dossier du logement
        		move_uploaded_file($tmp_name, "pictures/$lodgingId/$i.$ext");
        		$i++;
    		}
		}

		// Retour sur la page de gestion des logements
		die( header('Location: my/lodgings.php') );

    } else $error = "Tous les champs doivent être remplis.";

} else $error = "Le formulaire n'a pas été correctement validé.";

}

$title = 'Ajout d\'un logement';
require_once './view/header.php';

// Si on a une erreur à afficher
if ( !empty($error) ) {
    echo '<h2 style="color:red">'.$error.'</h2>';
}
?>
<form enctype="multipart/form-data" method="post">
    <input type="text" placeholder="Adresse" name="lodging_add[address]" value="<?=!empty($_POST['lodging_add']['address']) ? $_POST['lodging_add']['address'] : ''?>" required />
    <br /><input type="text" placeholder="Code postal" name="lodging_add[zipcode]" value="<?=!empty($_POST['lodging_add']['zipcode']) ? $_POST['lodging_add']['zipcode'] : ''?>" required />
    <br /><input type="text" placeholder="Ville" name="lodging_add[city]" value="<?=!empty($_POST['lodging_add']['city']) ? $_POST['lodging_add']['city'] : ''?>" required />
    <br /><input type="text" placeholder="Capacité d'accueil" name="lodging_add[capacity]" value="<?=!empty($_POST['lodging_add']['capacity']) ? $_POST['lodging_add']['capacity'] : ''?>" required />
    <input type="hidden" name="pictureCount" id="pictureCount" value="1" />
    <br /><br />
    <div id="picturesUpload">
    	Ajoutez jusqu'à 3 photos :<br />
    	Photo principale : <input name="picture[]" type="file" />
    </div>
    <input type="button" id="addPicture" value="+" />
    <br /><br /><input type="submit" name="lodging_add[submit]" value="Sauvegarder le logement" />
</form>
<?php require_once './view/footer.php'; ?>