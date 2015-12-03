<!-- Formulaire d'inscription -->
<?php
    // v2.0

require_once 'inc/pdo.php';
require_once 'func/keyGenerator.php';

if ( $_POST ) {

    // Sauvegarde temporaire des valeurs saisies par l'utilisateur
    // foreach($_POST['register'] as $key => $value) {
    //     $_SESSION['form']['register'][$key] = $value;
    // }

    // Quand le bouton submit est cliqué
    if ( !empty($_POST['register']['submit']) ) {
        // On vérifie que les champs ne sont pas vides
        if ( !empty($_POST['register']['lastname']) && !empty($_POST['register']['firstname']) && !empty($_POST['register']['email']) && !empty($_POST['register']['password']) ) {
        	// Génération d'un salt
            $salt = keyGenerator();
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

                $_POST['auth'] = $user;
                die( header('Location: ./' . $_POST['register']['type']) );

            } else $error = "Une erreur de modulation de fréquence binaire est survenue.";
            
        } else $error = "Le formulaire n'a pas été correctement validé.";
    }
} else if ( $error ) { ?>
	<h2 style="color: red;"><?=$error?></h2>
<?php } ?>

<form method="post" action="register.php">
	<input type="text"     placeholder="Nom"          name="register[lastname]"         value="<?=!empty($_POST['register']['lastname'])  ? $_POST['register']['lastname']  : ''?>" required=""/>
	<input type="text"     placeholder="Prénom"       name="register[firstname]"        value="<?=!empty($_POST['register']['firstname']) ? $_POST['register']['firstname'] : ''?>" required=""/>
	<input type="email"    placeholder="Email"        name="register[email]"            value="<?=!empty($_POST['register']['email'])     ? $_POST['register']['email']     : ''?>" required=""/>
	<input type="text"     placeholder="Téléphone"    name="register[phone]"            value="<?=!empty($_POST['register']['phone'])     ? $_POST['register']['phone']     : ''?>" required=""/>
	<input type="password" placeholder="Mot de passe" name="register[password]"         value="<?=!empty($_POST['register']['password'])  ? $_POST['register']['password']  : ''?>" required=""/> 
	<input type="password" placeholder="Confirmation" name="register[password_confirm]" value="<?=!empty($_POST['register']['password_confirm']) ? $_POST['register']['password_confirm'] : ''?>" required=""/> 
	<input type="submit" name="register[submit]" value="CREER MON COMPTE"/>
</form>

<?php } ?>