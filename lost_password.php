<?php
	// v1.0

$error="";

if ( $_POST ) {
	if ( !empty($_POST['action']['newpassword']) ) {
		
		if ( !empty($_POST['action']['id']) && !empty($_POST['user']['password']) && !empty($_POST['user']['password.confirm']) ) {
			if ( ($_POST['user']['password']) === ($_POST['user']['password.confirm']) ) {

				$pdo = include('conf/pdo.php');
				$userQuery = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?;');
				$userQuery->execute([password_hash($_POST['user']['password'], PASSWORD_DEFAULT)]);
				$users = $userQuery->fetchAll();

			} else $error = "Les mots de passe ne sont pas identiques.";

		} else $error = "Tous les champs doivent être remplis.";

	} else $error = "Le formulaire n'a pas été correctement validé.";

}

$title = "Mot de passe oublié";

include('includes/top.php'); ?>

<form action="newpassword.php" method="post">

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
			type="newpassword"
			placeholder="mot de passe"
			name="user[password]"
		/>
		<br/>
		<input
			type="newpassword"
			placeholder="mot de passe"
			name="user[password.confirm]"
		/>

	</fieldset>
	<fieldset>
		<input type="submit" name="action[newpassword]" value="Changer le mot de passe" />
	</fieldset>

	<?php if ( $password ) { ?>
		<h2><?=$password?></h2>
	<?php } ?>
</form>
<?php include('includes/bottom.php'); ?>