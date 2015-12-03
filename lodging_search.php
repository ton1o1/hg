<!-- Listing de tous les logements avec formulaire de recherche et système de pagination -->

<?php

//faire une fonction permettant de lister tous les appartements présents dans la BDD

function build_html_flats( $flats )
{
	$flats_html ='';
	foreach ($flats as $flat) 
	{
		$flats_html .= sprintf(
			'<section>
				<div>
					<a href="booking.php">
						<p>Appartement de %1$s</p>
						<p>%1$s</p>
						<p>%2$s</p>
						<p>%3$s</p>
					</a>
				</div>
				
				<div>
					<img src="pictures/%4$s/" alt="">
				</div>

			</section>',
			$flat['firstname'],
			$flat['lastname'],
			$flat['address'],
			$flat['id']
		);
	}
	return $flats_html;
}

include('../inc/pdo.php');

$statement = $pdo->prepare( 'SELECT user.firstname, user.lastname, lodging.address, lodging.id FROM lodging JOIN user ON lodging.user_id = user.id;' );
$statement->execute();

$statementFlat = $statement->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home | GUEST</title>
	<link href="css/style.css" rel="stylesheet">
</head>
	<body>

		<?= build_html_flats($statementFlat); ?>

	</body>
</html>