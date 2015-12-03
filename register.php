<!-- Formulaire d'inscription -->
<?php
    // v2.0

require_once 'inc/pdo.php';
require_once 'func/keyGenerator.php';

if ( $_POST ) {
    session_start();

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

                $_SESSION['auth'] = $user;
                die( header('Location: ./' . $_POST['register']['type']) );

            } else $_SESSION['error'] = "Une erreur de modulation de fréquence binaire est survenue.";
            
        } else $_SESSION['error'] = "Le formulaire n'a pas été correctement validé.";
        
        if( !empty($_SESSION['error']) ) {
        	die(header('Location: ./') ); 
    	}
    } else die(header('Location: ./'));
} else { ?>

<?php } ?>