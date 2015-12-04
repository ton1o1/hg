<?php
	// v1.1

$pdo = require_once('inc/pdo.php');

$error = '';
$id = '';

if ( $_POST ) {
	if ( !empty($_POST['action']['lost_password']) ) {
		if ( !empty($_POST['user']['email'])  ) {
			
			$userQuery = $pdo->prepare('SELECT * FROM user WHERE email = ?;');
			$userQuery->execute([$_POST['user']['email']]);
			$user = $userQuery->fetchAll();

			if ( $user ) {
				echo "<pre>";
				print_r($user);
				$password = $user[0]['id'];
			} else $error = "L'email n'est pas utilisé.";
		} else $error = "Tous les champs doivent être remplis.";
	} else $error = "Le formulaire n'a pas été correctement validé.";
}

$title = "Oubli de mot de passe";
include('view/header.php'); ?>
<form action="set_password.php" method="post">

	<?php if ( $error ) { ?>
		<h2 style="color: red;"><?=$error?></h2>
	<?php } ?>

	<fieldset>
		<input
			type="text"
			placeholder="Votre email"
			name="user[email]"
			value="<?=!empty($_POST['user']['email']) ? $_POST['user']['email'] : ''?>"
		/>
		<input type="submit" name="action[lost_password]" value="Valider" />
	</fieldset>

	<?php if ( $id ) { ?>
		<h2><?=$password?></h2>
	<?php } ?>
</form>
<?php include('view/footer.php'); ?>