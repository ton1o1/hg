<?php
	// reset.password.php
	// v2.4
	// H&G

	$error = '';
	require_once('inc/pdo.php');
	require_once 'func/keyGenerator.php';

	// Si la variable $_POST n'est pas vide
	if ( $_POST ) {

		// Vérifie que le bouton submit à été cliqué
		if ( !empty($_POST['action']['new.password']) ) {

			// Vérifie que les données du fomulaire ne sont pas vides
			if ( !empty($_POST['user']['new.password']) && !empty($_POST['user']['password.confirm']) ) {

				// Vérifie que les mots de passe correspondent
				if ( ($_POST['user']['new.password']) === ($_POST['user']['password.confirm']) ) {

					// Recherche de l'utilisateur correspondant à l'id et au token
					$statement = $pdo->prepare('SELECT * FROM user WHERE id = ? AND token = ?;');
					$statement->execute([
						$_POST['action']['id'],
						$_POST['action']['token'],
					]);
					$user = $statement->fetch();

					// Vérifie que le token et l'id de l'utilisateur sont présents dans la base
					if ( $user ) {

						// Génération d'un salt
						$salt = keyGenerator();

						// Mise à jour du mot de passe dans la base
						$statement = $pdo->prepare('UPDATE user SET password = ?, token = "" WHERE id = ? AND token = ?;');
						$statement->execute([
							hash('sha512', $_POST['register']['new.password'].$salt),
							// password_hash($_POST['user']['new.password'], PASSWORD_DEFAULT),
							$_POST['action']['id'],
							$_POST['action']['token']
						]);

						// On démarre directement une session avec le compte nouvellement modifié
						$statement = $pdo->prepare('SELECT * FROM user WHERE id = ?;');
						$statement->execute([$_POST['action']['id']]);
						$user = $statement->fetch();
						session_start();

						// Stockage des infos de l'utilisateur dans la variable $_SESSION
						$_SESSION['auth'] = $user;
						// Redirige l'utilisateur
						die ( header('Location: ./' . (!empty($_POST['action']['next']) ? $_POST['action']['next'] : '')) );
					} else $error = "Impossible de renouveler votre mot de passe, veuillez réessayer.";
				} else $error = "Les mots de passe ne sont pas identiques.";
			} else $error = "Tous les champs doivent être remplis.";
		} else $error = "Le formulaire n'a pas été correctement validé.";
	}

	$title = "Réinitialisation de mot de passe";
	include('view/header.php');
?>
<form action="reset.password.php" method="post">

	<!-- Affichage des erreurs -->
	<?php if ($error) {?>
			<h2 style="color: red;"><?=$error?></h2>
	<?php }?>

	<!-- Formulaire de nouveau mot de passe -->
	<fieldset>
		<input
			type="password"
			placeholder="Nouveau mot de passe"
			name="user[new.password]"
		/><br/>
		<input
			type="password"
			placeholder="Confirmation du nouveau mot de passe"
			name="user[password.confirm]"
		/><br/>

		<!-- On récupère l'id de la variable $_GET et on la passe dans le formulaire dans un champ caché en POST -->
		<input
			type="hidden"
			name="action[id]"
			value="<?=!empty($_GET['id']) ? $_GET['id'] : ( !empty($_POST['action']['id']) ? $_POST['action']['id'] : '' )?>"
		/>

		<!-- On récupère le token de la variable $_GET et on la passe dans le formulaire dans un champ caché en POST -->
		<input
			type="hidden"
			name="action[token]"
			value="<?=!empty($_GET['token']) ? $_GET['token'] : ( !empty($_POST['action']['token']) ? $_POST['action']['token'] : '' )?>"
		/>
		<input type="submit" name="action[new.password]" value="Changer le mot de passe" />
	</fieldset>
</form>

<?php include('view/footer.php'); ?>
