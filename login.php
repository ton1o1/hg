<?php
// Formulaire de connexion

require_once 'inc/pdo.php';
require_once 'func/keyGenerator.php';

// Le formulaire est-il soumis ?
if ( !empty($_POST['login']['submit']) ) {

    // Si le tous les champs du formulaire sont remplis
    if ( !empty($_POST['login']['email']) && !empty($_POST['login']['password']) ) {

        $query = $pdo->prepare("SELECT * FROM user WHERE email = :email;");
        $query->execute([
            ':email' => $_POST['login']['email'],
        ]);
        $user = $query->fetch();
        
        // Si l'user existe
        if ( $user ) {

            // Création du hash à comparer avec le hash de la database
            $passwordHash = hash('sha512', $_POST['login']['password'] . $user['salt']);

            // Comparaison du hash (le mot de passe saisi est-il correct ?)
            if ( $passwordHash == $user['password'] ) {
                
                // Démarrage de la session
                session_start();

                // Connexion de l'user (création de la session)
                $_SESSION['auth'] = $user;

            } else $error = "Mauvais identifiants !";

        } else $error = "Mauvais identifiants !";

    } else $error = "Tous les champs doivent être remplis.";

} else $error = "Le formulaire n'a pas été correctement validé.";

?>
<!DOCTYPE html>
<html>
<head>
    <title>H&G | Connexion</title>
</head>
<body>
<h1>Connexion</h1>
<hr>
<?php
// Si on a une erreur à afficher
if ( !empty($error) ) {
    echo '<h2 style="color:red">'.$error.'</h2>';
}
?>
<form method="post">
    <input type="text" placeholder="Email" name="login['email']" value="<?=!empty($_POST['login']['email']) ? $_POST['login']['email'] : ''?>" required />
    <br /><input type="password" class="password" placeholder="Mot de passe" name="login['password']" value="<?=!empty($_POST['login']['password']) ? $_POST['login']['password'] : ''?>" required />
    <br /><input type="submit" name="login['submit']" value="Connexion" />
</form>
</body>
</html>