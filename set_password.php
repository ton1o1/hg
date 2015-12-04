<?php
	// set_password
	// v1.3

$pdo = require_once('inc/pdo.php');
$error="";

// On verifie que la variable $_POST n'est pas vide
if ( $_POST ) {
	// On verifie que le boutton submit a ete clique
	if ( !empty($_POST['action']['newpassword']) ) {
		// On verifie qu'on a bien les valeur indispensables pour la requete
		if ( !empty($_POST['action']['id']) && !empty($_POST['user']['password']) && !empty($_POST['user']['password_confirm']) ) {
			if ( ($_POST['user']['password']) === ($_POST['user']['password_confirm']) ) {
	        	// Génération d'un salt
	            $salt = keyGenerator();
	            // Mise à jour des données dans la base
            	try {
		            $statement = $pdo->prepare("UPDATE user SET password=:password, salt=:salt WHERE user_id=:user_id");
		            $statement->execute([
		                ':password'  => hash('sha512', $_POST['user']['password'].$salt),
		                ':salt'      => $salt,
					]);
					$users = $userQuery->fetchAll();
				}
	            catch (PDOException $e) {
			        echo 'Erreur : ' . $e->getMessage();
			    }
			} else $error = "Les mots de passe ne sont pas identiques.";
		} else $error = "Tous les champs doivent être remplis.";
	} else $error = "Le formulaire n'a pas été correctement validé.";
}

$title = "Oubli de mot de passe";
include('view/footer.php'); ?>

<form method="post">

	<?php if ( $error ) { ?>
		<h2 style="color: red;"><?=$error?></h2>
	<?php } ?>

	<fieldset>
		<input
			type="hidden"
			name="action[id]"
			value="<?=!empty($_GET['id']) ? $_GET['id'] : ''?>"
		/>
		<input
			type="password"
			placeholder="Nouveau de passe"
			name="user[password]"
		/>
		<br/>
		<input
			type="password"
			placeholder="Confirmation"
			name="user[password_confirm]"
		/>

	</fieldset>
	<fieldset>
		<input type="submit" name="action[newpassword]" value="Changer le mot de passe" />
	</fieldset>

	<?php if ( $password ) { ?>
		<h2><?=$password?></h2>
	<?php } ?>
</form>
<?php include('view/footer.php'); ?>
