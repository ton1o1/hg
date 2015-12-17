<?php
	// token.request.php
	// v2.5
	// 23:38 08/12/2015
	// HG

	$error = '';
	$success = '';
	$id = '';
	$token = '';

	require_once ('inc/pdo.php');

	// Vérifie que la variable "$_POST" n'est pas vide (qu'il y a eu validation d'un formulaire)
	if ( $_POST ) {

		// Vérifie que le bouton submit à été cliqué
		if ( !empty($_POST['action']['submit']) ) {

			// Vérifie que le champ email n'est pas vide
			if ( !empty($_POST['user']['email'])  ) {

				// Recherche de l'utilisateur correspondant à l'email de l'utilisateur
				$query = $pdo->prepare('SELECT * FROM user WHERE email = ?;');
				$query->execute([ $_POST['user']['email'] ]);
				$user = $query->fetch();

				// Si l'utilisateur existe dans la base
				if ( $user ) {

					// On récupère l'id de l'utilisateur
					$id = $user['id'];

					// On génere un token
					$token = password_hash(uniqid(), PASSWORD_DEFAULT);

					// Ici on utilise l'id de l'utilisateur plutôt que son email pour une raison de sécurité (pas besoin de prepare, aucune données ne proviennent de l'utilisateur)
					$pdo->exec(sprintf(
						'UPDATE user SET token = "%1$s" WHERE id = %2$u;',
						$token,
						$id
					));

					// Envoie le lien de récupération de mot de passe à l'utilisateur par email
					require_once ('cfg/smtp.php');
					$mail->addAddress($user['email'], $user['email']);
					$mail->Subject  = "Récupération de mot de passe";
					$mail->Body     = "Votre lien de récupération de mot de passe : <br /><a href='http://localhost/hg/reset.password.php?id=$id&token=$token'>http://localhost/hg/reset.password.php?id=$id&token$token</a>";
					$mail->AltBody  = "Votre lien de récupération de mot de passe : \nhttp://localhost/hg/reset.password.php?id=$id&token=$token";

					if ( !$mail->send() ) {
					    $error = "Le mail de récupération de mot de passe n'a pas pu être envoyé.<br />";
					    $error .= "Erreur: " . $mail->ErrorInfo;
					} else $success = "Votre lien de récupération de mot de passe à bien été envoyé, vérifiez votre boite mail.";
				} else $error = "Cet utilisateur n'existe pas.";
			} else $error = "Tous les champs doivent être remplis.";
		} else $error = "Le formulaire n'a pas été correctement validé.";
	}

	$title = "Récupération de mot de passe";
	include('view/header.php');
?>

<!-- Affichage des erreurs -->
<?php if ($error) {?>
	<section>
		<h2 style="color: red;"><?=$error?></h2>
	</section>
<?php }?>

<form action="token.request.php" method="post">


	<fieldset>
		<!-- Formulaire de demande de token -->
		<input
			type="text"
			placeholder="email"
			name="user[email]"
			value="<?=!empty($_POST['user']['email']) ? $_POST['user']['email'] : ''?>"
		/>
		<input type="submit" name="action[submit]" value="Récupérer" />
	</fieldset>
</form>

<!-- Le mail contenant le lien de récupération de mot de passe à bien été envoyé  -->
<?php if ( $success ) { ?>
	<section>
		<h2 style="color: green;">Votre lien de récupération de mot de passe à bien été envoyé, vérifiez votre boite mail.</h2>
	</section>
<?php } ?>

<?php include('view/footer.php'); ?>
