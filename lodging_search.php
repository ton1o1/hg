<!-- Listing de tous les logements avec formulaire de recherche et système de pagination -->

<?php

include('inc/pdo.php');

$statementCities = $pdo->prepare('SELECT city FROM lodging GROUP BY city ;' );
$statementCities->execute(); 

$allCities = $statementCities->fetchAll();

function build_allCities( $cities )
{
	$allCities_html ='';
	
	foreach ($cities as $city) 
	{
		$allCities_html .= sprintf(
			'<p>%1$s</p>',
			$city['city']
		);
	}
	return $allCities_html;
}

$statementFlats = $pdo->prepare('	SELECT user.firstname, user.lastname, lodging.address, lodging.id 
									FROM lodging JOIN user ON lodging.user_id = user.id ' );
$statementFlats->execute(); 

$allFlats = $statementFlats->fetchAll();

function build_allFlats( $flats )
{
	$allFlats_html ='';
	
	foreach ($flats as $flat) 
	{
		$allFlats_html .=sprintf(
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
					<img src="pictures/%4$s/%4$s.jpg" alt="">
				</div>
			</section>',
			$flat['firstname'],
			$flat['lastname'],
			$flat['address'],
			$flat['id']
		);
	}
	return $allFlats_html;
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Home | Flats</title>
	<link href="css/style.css" rel="stylesheet">
</head>
	<body>
		<h2>Les villes où nous sommes présents : </h2>
		
		<?= build_allCities($allCities); ?>

		<h2>Cherchez votre logement</h2>

		<form action="#" method="GET" id="flats-form">
			<input type="text" name="flatsForm" placeholder="Votre appartement">
		</form>
		

		<div id="results">
			
			<?= build_allFlats($allFlats); ?>

		</div>
		

	 	<script src="js/jquery.min.js"></script>
		<script src="js/main.js"></script>

	</body>
</html>