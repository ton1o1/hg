<?php
	// token.request.php
	// v2.5
	// H&G

	$error = '';
	$success = '';
	$id = '';
	$token = '';
	require_once('inc/pdo.php');

	// Si la variable $_POST n'est pas vide (il y a eu validation d'un formulaire)
	if ( $_POST ) {

		// Vérifie que le bouton submit à été cliqué
		if ( !empty($_POST['action']['password']) ) {

			// Vérifie que le champ email n'est pas vide
			if ( !empty($_POST['user']['email'])  ) {

				// Recherche de l'utilisateur correspondant à l'email de l'utilisateur
				$statement = $pdo->prepare('SELECT * FROM user WHERE email = ?;');
				$statement->execute([$_POST['user']['email']]);
				$user = $statement->fetch();

				// Si l'utilisateur existe dans la base
				if ( $user ) {

					// On récupère l'id de l'utilisateur
					$id = $user['id'];

					// On génere un token
					$token = password_hash(uniqid(), PASSWORD_DEFAULT);

					// Par sécurité utilise l'id de l'utilisateur et non son email qui serait visible dans la barre d'adresse (pas besoin de prepare, aucune donnée ne proviennent de l'utilisateur)
					$pdo->exec(sprintf(
						'UPDATE user SET token = "%1$s" WHERE id = %2$u;',
						$token,
						$id
					));

					// Envoie le lien de récupération de mot de passe à l'utilisateur par email
					require_once('inc/PHPMailerAutoload.php');
					$mail = new PHPMailer;
					$mail->isSMTP();
					$mail->Host     = 'smtp.gmail.com';
					$mail->SMTPAuth = true;
					$mail->Username = 'admin@email.com';
					$mail->Password = 'admin';
					$mail->Port     = 587;
					$mail->setFrom('admin@email.com', 'H&G');
					$mail->addAddress($user['email'], $user['email']);
					$mail->isHTML(true);
					$mail->CharSet  = 'UTF-8';
					$mail->Subject  = "Récupération de mot de passe";
					$mail->Body     = "Votre lien de récupération de mot de passe : <br/><a href='http://localhost/hg/reset.password.php?id=$id&token=$token'>http://localhost/hg/reset.password.php?id=$id&token$token</a>";
					$mail->AltBody  = "Votre lien de récupération de mot de passe : \nhttp://localhost/hg/reset.password.php?id=$id&token=$token";

					if ( !$mail->send() ) {
					    $error = "Le mail de récupération de mot de passe n'a pas pu être envoyé.<br/>";
					    $error .= "Erreur: " . $mail->ErrorInfo;
					} else $success = "Votre lien de récupération de mot de passe à bien été envoyé, vérifiez votre boite mail.";
				} else $error = "Cet utilisateur n'existe pas.";
			} else $error = "Tous les champs doivent être remplis.";
		} else $error = "Le formulaire n'a pas été correctement validé.";
	}

	$title = "Récupération de mot de passe";
	include('view/header.php');
?>

<form action="token.request.php" method="post">

	<!-- Affichage des erreurs -->
	<?php if ($error) {?>
			<h2 style="color: red;"><?=$error?></h2>
	<?php }?>

	<fieldset>
		<!-- Formulaire de demande de token -->
		<input
			type="text"
			placeholder="email"
			name="user[email]"
			value="<?=!empty($_POST['user']['email']) ? $_POST['user']['email'] : ''?>"
		/>
		<input type="submit" name="action[password]" value="Récupérer" />
	</fieldset>
</form>

<!-- Le mail de récupération de mot de passe à bien été envoyé  -->
<?php if ( $success ) { ?>
	Votre lien de récupération de mot de passe à bien été envoyé, vérifiez votre boite mail.<br/>
<?php } ?>

<?php include('view/footer.php'); ?>
