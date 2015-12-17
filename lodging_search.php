<!-- Listing de tous les logements avec formulaire de recherche et système de pagination -->

<!DOCTYPE html>
<html>
<head>
	<title>Home | Flats</title>
	<link href="css/style.css" rel="stylesheet">
</head>
	<body>
		<h2>Les villes où nous sommes présents : </h2>

		<?php 
			$results = include('lodging_search_app.php');
			echo build_allCities($results['cities']); 
		?>

		<h2>Cherchez votre logement</h2>

		<form action="#" method="GET" id="flats-form">
			<input type="text" name="flatsForm" placeholder="Votre appartement">
		</form>
			
		<div id="results">

			<?php
				echo build_allFlats($results['flats']);
			?>

		</div>

	 	<script src="js/jquery.min.js"></script>
		<script src="js/main.js"></script>

	</body>
</html>