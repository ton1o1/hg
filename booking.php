<?php
// On arrive sur cette page après avoir cliqué sur un logement

// - Descriptif du logement
// - Calendrier des dispos
// - Formulaire de réservation

// Démarrage de la session
session_start();

// Si l'user n'est pas connecté ou qu'aucun logement n'est renseigné, on renvoie sur la home
if(empty($_SESSION['auth']) || empty($_GET['id'])){  die( header('Location: ./') ); }

require_once 'inc/pdo.php';

$query = $pdo->prepare("SELECT * FROM lodging WHERE id = :id");
$query->execute([
    ':id' => $_GET['id']
]);

// Si on a un logement à afficher
if($query->rowCount() > 0){

    $lodging = $query->fetch();

    if($_POST){

        // Le formulaire est-il soumis ?
        if ( !empty($_POST['booking']['submit']) ) {

            // Si le tous les champs du formulaire sont remplis
            if ( !empty($_POST['booking']['checkin']) && !empty($_POST['booking']['checkout']) && !empty($_POST['booking']['guests']) ) {

                // Vérification des dates fournies
                $dateCurrent = new DateTime();
                $dateCurrent->format('Y-m-d');
                $dateCheckin = new DateTime($_POST['booking']['checkin']);
                $dateCheckout = new DateTime($_POST['booking']['checkout']);

                if($dateCheckin >= $dateCurrent && $dateCheckout >= $dateCheckin){

                    // Vérification de la capacité d'hébergement du logement
                    if($_POST['booking']['guests'] > 0 && $_POST['booking']['guests'] <= $lodging['capacity']){

                        // Enregistrement de la réservation dans la database
                        $query = $pdo->prepare("INSERT INTO booking VALUES('', :lodgingId, :userId, :checkin, :checkout, :ngGuests);");
                        $success = $query->execute([
                            ':lodgingId' => $_GET['id'],
                            ':userId' => $_SESSION['auth']['id'],
                            ':checkin' => $_POST['booking']['checkin'],
                            ':checkout' => $_POST['booking']['checkout'],
                            ':ngGuests' => $_POST['booking']['guests'],
                        ]);

                        if($success){
                            // Si la requête a bien été effectuée

                            // On sauvegarde un message de succès dans la session
                            $_SESSION['info'] = 'Votre réservation a bien été ajoutée.';

                            // Retour sur la page de gestion des réservations
                            die( header('Location: my/bookings.php') );

                        } else $error = 'Une erreur est survenue, veuillez rééssayer !';

                    } else $error = "Le nombre de guests n'est pas valide, veuillez vérifier la capacité d'hébergement du logement !";

                } else $error = "Les dates saisies ne sont pas valides !";

            } else $error = "Tous les champs doivent être remplis.";

        } else $error = "Le formulaire n'a pas été correctement validé.";

    }

    $title = 'Réserver ce logement';
    require_once 'view/header.php';

    // Si on a une erreur à afficher
    if ( !empty($error) ) {
        echo '<h2 style="color:red">'.$error.'</h2><hr>';
    }

?>

<div id="calendar" data-toggle="calendar" ></div>

<hr>

<form method="post">
Réserver du <input type="text" name="booking[checkin]" id="checkin" value="<?=!empty($_POST['booking']['checkin']) ? $_POST['booking']['checkin'] : ''?>" /> au
<input type="text" name="booking[checkout]" id="checkout" value="<?=!empty($_POST['booking']['checkout']) ? $_POST['booking']['checkout'] : ''?>" />
<br />Nombre de guests : <input type="text" name="booking[guests]" value="<?=!empty($_POST['booking']['guests']) ? $_POST['booking']['guests'] : ''?>" /> <input type="submit" name="booking[submit]" value="Go !" />
</form>
<?php
    require_once 'view/footer.php';
}
else die( header('Location: ./') );
?>