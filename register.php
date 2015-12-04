<?php
    // register.php
    // v3.1
    // Changelog: 16:47 04/12/2015 : Ajout d'une étape de vérification d'utilisateur déjà enregistré avec le même email
    // Changelog: 15:25 04/12/2015 : Ajout d'une étape de validation d'email
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

                // On vérifie que l'email saisi est valide
                if ( filter_var($_POST['register']['email'], FILTER_VALIDATE_EMAIL) ) {

                    // On confirme que les mots de passe correspondent
                    if ( $_POST['register']['password'] == $_POST['register']['password_confirm'] ) {

                        // On vérifie qu'il n'existe pas déjà un utilisateur avec le même email dans la base
                        $query = $pdo->prepare("SELECT COUNT(*) AS verif_email FROM user WHERE email = :email;");
                        $query->execute([
                            ':email' => $_POST['register']['email'],
                            ]);
                        $result = $query->fetch();

                        // $_SESSION['info'] = $result['verif_email'];
                        // die( header('Location: ./') );
                        if ( $result['verif_email'] == 0 ) {

                            // On rajoute l'utilisateur dans la base
                            // Génération d'un salt
                            $salt = keyGenerator();
                            try {
                                // Mise à jour des données dans la base
                                $statement = $pdo->prepare("INSERT INTO user VALUES ('', :password, :salt, :firstname, :lastname, :email, :phone);");
                                $statement->execute([

                                    // Hashage et ajout du hash du mot de passe dans la base
                                    ':password'  => hash('sha512', $_POST['register']['password'].$salt),

                                    // Ajout du salt et des autres infos de l'utilisateur
                                    ':salt'      => $salt,
                                    ':firstname' => $_POST['register']['firstname'],
                                    ':lastname'  => $_POST['register']['lastname'],
                                    ':email'     => $_POST['register']['email'],
                                    ':phone'     => $_POST['register']['phone'],
                                    ]);

                                // On ouvre une session
                                $query = $pdo->prepare("SELECT * FROM user WHERE email = :email;");
                                $query->execute([
                                    ':email' => $_POST['register']['email'],
                                    ]);

                                // On charge toutes les infos de l'utilisateur dans la variable $_SESSSION depuis la base
                                $user = $query->fetch();
                                if ( $user ) {
                                    session_start();
                                    $_SESSION['auth'] = $user;
                                    $_SESSION['info'] .= "Votre compte à bien été crée.";
                                    // On redirige l'utilisateur vers la home
                                    die( header('Location: ./') );
                                }
                            }
                            catch (PDOException $e) {
                                // Il s'est produit une erreur PDO
                                $error = 'Erreur : ' . $e->getMessage();
                            }
                        } else $error = "Un utilisateur est déjà enregistré avec cet email.";
                    } else $error = "Les mots de passe ne correspondent pas";
                } else $error = "Vous devez entrer un email valide.";
            } else $error = "Veuillez renseigner tous les champs !";
        } else $error = "Le formulaire n'a pas été correctement validé.";
    }

    // inclusion du header
    $title = 'Création de compte';
    require_once './view/header.php';

    // Si on a une erreur à afficher
    if ( !empty($error) ) {
        echo '<h2 style="color:red">'.$error.'</h2>';
    }
?>

<!-- formulaire -->
<form method="post">
    <input type="text"     placeholder="Nom"          name="register[lastname]"         value="<?=!empty($_POST['register']['lastname'])  ? $_POST['register']['lastname']  : ''?>" required=""/><br />
    <input type="text"     placeholder="Prénom"       name="register[firstname]"        value="<?=!empty($_POST['register']['firstname']) ? $_POST['register']['firstname'] : ''?>" required=""/><br />
    <input type="email"    placeholder="Email"        name="register[email]"            value="<?=!empty($_POST['register']['email'])     ? $_POST['register']['email']     : ''?>" required=""/><br />
    <input type="text"     placeholder="Téléphone"    name="register[phone]"            value="<?=!empty($_POST['register']['phone'])     ? $_POST['register']['phone']     : ''?>" required=""/><br />
    <input type="password" placeholder="Mot de passe" name="register[password]"         value="<?=!empty($_POST['register']['password'])  ? $_POST['register']['password']  : ''?>" required=""/> <br />
    <input type="password" placeholder="Confirmation" name="register[password_confirm]" value="<?=!empty($_POST['register']['password_confirm']) ? $_POST['register']['password_confirm'] : ''?>" required=""/> <br />
    <input type="submit" name="register[submit]" value="Créer un compte"/>
</form>

<!-- inclusion du footer -->
<?php require_once './view/footer.php'; ?>
