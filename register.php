<?php
	// register.php
    // v2.6
	// Changelog: 10:32 04/12/2015 : Ajout meta charset
	// Changelog: 10:09 04/12/2015 : Ajout de la variable $error

require_once 'inc/pdo.php';
require_once 'func/keyGenerator.php';

$error = "";

if ( $_POST ) {

    // Quand le bouton submit est cliqué
    if ( !empty($_POST['register']['submit']) ) {
        // On vérifie que les champs ne sont pas vides
        if ( !empty($_POST['register']['lastname']) && !empty($_POST['register']['firstname']) && !empty($_POST['register']['email']) && !empty($_POST['register']['password']) ) {
        	// Génération d'un salt
            $salt = keyGenerator();
            // Mise à jour des données dans la base
            try{
	            $statement = $pdo->prepare("INSERT INTO user VALUES ('', :password, :salt, :firstname, :lastname, :email, :phone);");
	            $statement->execute([
	            	// Hashage du mot de passe
	                ':password'  => hash('sha512', $_POST['register']['password'].$salt),
	                ':salt'      => $salt,
	                ':firstname' => $_POST['register']['firstname'],
	                ':lastname'  => $_POST['register']['lastname'],
	                ':email'     => $_POST['register']['email'],
	                ':phone'     => $_POST['register']['phone'],
	                ]);
            }
            catch (PDOException $e) {
		        echo 'Erreur : ' . $e->getMessage();
		    }

            $success = "Votre compte à bien été crée.";
            
            $query = $pdo->prepare("SELECT * FROM user WHERE email = :email;");
            $query->execute([
                ':email' => $_POST['register']['email'],
                ]);
            $user = $query->fetch();

            if ( $user ) {
    			session_start();

                $_SESSION['auth'] = $user;
                die( header('Location: ./') );

            } else $error = "Une erreur de modulation de fréquence binaire est survenue.";
        } else $error = "Veuillez renseigner tous les champs !";
    } else $error = "Le formulaire n'a pas été correctement validé.";
} 

if ( $error ) { ?>
	<h2 style="color: red;"><?=$error?></h2>
<?php } ?>

<!DOCTYPE html>
<html>
<head>
    <title>H&G | Inscrition</title>
    <meta charset="utf-8">
</head>
<body>
<h1>Inscrition</h1>
<hr>
<form method="post" action="register.php">
	<input type="text"     placeholder="Nom"          name="register[lastname]"         value="<?=!empty($_POST['register']['lastname'])  ? $_POST['register']['lastname']  : ''?>" required=""/><br />
	<input type="text"     placeholder="Prénom"       name="register[firstname]"        value="<?=!empty($_POST['register']['firstname']) ? $_POST['register']['firstname'] : ''?>" required=""/><br />
	<input type="email"    placeholder="Email"        name="register[email]"            value="<?=!empty($_POST['register']['email'])     ? $_POST['register']['email']     : ''?>" required=""/><br />
	<input type="text"     placeholder="Téléphone"    name="register[phone]"            value="<?=!empty($_POST['register']['phone'])     ? $_POST['register']['phone']     : ''?>" required=""/><br />
	<input type="password" placeholder="Mot de passe" name="register[password]"         value="<?=!empty($_POST['register']['password'])  ? $_POST['register']['password']  : ''?>" required=""/> <br />
	<input type="password" placeholder="Confirmation" name="register[password_confirm]" value="<?=!empty($_POST['register']['password_confirm']) ? $_POST['register']['password_confirm'] : ''?>" required=""/> <br />
	<input type="submit" name="register[submit]" value="Créer un compte"/>
</form>
