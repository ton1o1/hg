<?php
	// reset.password.php
	// v3.1
	// 23:41 11/12/2015
	// HG

	$error = '';
	$success = '';
	require_once('inc/pdo.php');
	require_once 'func/keyGenerator.php';

	// Vérifie que la variable "$_POST" n'est pas vide (qu'il y a eu validation d'un formulaire)
	if ( $_POST ) {

		// Vérifie que le bouton submit à été cliqué
		if ( !empty($_POST['action']['submit']) ) {

			// Vérifie que les données du fomulaire ne sont pas vides
			if ( !empty($_POST['user']['new.password']) && !empty($_POST['user']['password.confirm']) ) {

				// Vérifie que les mots de passe correspondent
				if ( ($_POST['user']['new.password']) === ($_POST['user']['password.confirm']) ) {

					// Recherche de l'utilisateur correspondant à l'id et au token
					$query = $pdo->prepare('SELECT * FROM user WHERE id = :id AND token = :token;');
					$query->execute([
						':id'    => $_POST['action']['id'],
						':token' => $_POST['action']['token'],
					]);
					$user = $query->fetch();

					// Vérifie que le token et l'id de l'utilisateur sont présents dans la base
					if ( $user ) {

						// Génération d'un salt
						$salt = keyGenerator();

						// Mise à jour du mot de passe et du salt dans la base et effacement du token
						$query = $pdo->prepare('UPDATE user SET password = :password, salt = :salt, token = "" WHERE id = :id AND token = :token;');
						$query->execute([

							// Hashage du mot de passe et ajout dans la base
							':password'  => hash('sha512', $_POST['user']['new.password'].$salt),
							':salt'  => $salt,
							
							// ':password' => password_hash($_POST['user']['new.password'], PASSWORD_DEFAULT),
							':id'       => $_POST['action']['id'],
							':token'    => $_POST['action']['token']
						]);
						
						// On démarre directement une session avec le compte nouvellement modifié
						$query = $pdo->prepare('SELECT * FROM user WHERE id = ?;');
						$query->execute([$_POST['action']['id']]);
						$users = $query->fetchAll();
						session_start();
						
						// Stockage des infos de l'utilisateur dans la variable $_SESSION
						$_SESSION['auth'] = $users[0];

						// Envoie le lien de confirmation de changement de mot de pasee à l'utilisateur par email
						require_once ('cfg/smtp.php');
						$mail->addAddress($user['email'], $user['email']);
						$mail->Subject  = 'Votre mot de passe à été changé sur HG';
						$mail->Body     = "<h1>Votre de mot de passe sur HG à été modifié</h1>Si vous n'êtes pâs à l'origine de ce changement, votre comte à peut être été piraté, contactez l'administrateur";
						$mail->AltBody  = "Votre de mot de passe sur HG à été modifié\nSi vous n'êtes pâs à l'origine de ce changement, votre comte à peut être été piraté, contactez l'administrateur";
						$mail->send();

						$success = "Votre mot de passe à bien été changé.";
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
		<section>
			<h2 style="color: red;"><?=$error?></h2>
		</section>
	<?php }?>

		<!-- Affichage du message -->
	<?php if ($success) {?>
		<section>
			<h2 style="color: green;"><?=$success?></h2>
		</section>
	<?php } else {?>

<?php } if ( $_GET ) { ?>

	<!-- Formulaire de nouveau mot de passe -->
	<fieldset>
		<input
			type="password"
			placeholder="Nouveau mot de passe"
			name="user[new.password]"
		/>
		<input
			type="password"
			placeholder="Confirmation du nouveau mot de passe"
			name="user[password.confirm]"
		/>

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
		<input type="submit" name="action[submit]" value="Changer le mot de passe" />
	</fieldset>
</form>
<?php }?>

<?php include('view/footer.php'); ?>
