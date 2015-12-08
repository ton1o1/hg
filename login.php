<?php
// login.php
// v2.5
// Formulaire de connexion

// Démarrage de la session
session_start();

// Si déjà connecté, redirige sur la home
if(!empty($_SESSION['auth'])){  die( header('Location: ./') ); }

require_once 'inc/pdo.php';

if($_POST){

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
                
                // Connexion de l'user (création de la session)
                $_SESSION['auth'] = $user;

                // Redirection sur la home
                die( header('Location: ./') );

            } else $error = "Mauvais identifiants !";

        } else $error = "Mauvais identifiants !";

    } else $error = "Tous les champs doivent être remplis.";

} else $error = "Le formulaire n'a pas été correctement validé.";

}

$title = 'Connexion';
require_once './view/header.php';
?>

<!-- Sous-menu -->
<a href="token.request.php">Mot de passe oublié ?</a>
<hr>

<?php
// Si on a une erreur à afficher
if ( !empty($error) ) {
    echo '<h2 style="color:red">'.$error.'</h2>';
}
?>
<form method="post">
    <input type="text" placeholder="Email" name="login[email]" value="<?=!empty($_POST['login']['email']) ? $_POST['login']['email'] : ''?>" required />
    <br /><input type="password" placeholder="Mot de passe" name="login[password]" value="<?=!empty($_POST['login']['password']) ? $_POST['login']['password'] : ''?>" required />
    <br /><input type="submit" name="login[submit]" value="Connexion" />
</form>
<?php require_once './view/footer.php'; ?>