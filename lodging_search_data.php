<?php

include('inc/pdo.php');

$flatsForm = $_GET['flatsForm'];

if ( strlen($flatsForm) < 3 )
{
	$statement = $pdo->prepare('SELECT user.firstname, user.lastname, lodging.address, lodging.id 
								FROM lodging JOIN user ON lodging.user_id = user.id ;' );
	$statement->execute(); 
} 
else
{
	$statement = $pdo->prepare('SELECT user.firstname, user.lastname, lodging.address, lodging.id 
								FROM lodging JOIN user ON lodging.user_id = user.id 
								WHERE city LIKE :research ;' );
	$statement->execute([ 
		':research' => '%' . $flatsForm . '%'
		]); 

}

$flats = $statement->fetchAll();

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
				<img src="pictures/%4$s/%4$s.jpg" alt="">
			</div>
		</section>',
		$flat['firstname'],
		$flat['lastname'],
		$flat['address'],
		$flat['id']
	);
}

echo $flats_html;