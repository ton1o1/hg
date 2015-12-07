<?php

include('inc/pdo.php');
include_once('func/lodging_search.php');

//***************************************** DATA : pour build_allCities() *****************************************

$statementCities = $pdo->prepare( 'SELECT city FROM lodging GROUP BY city ;' );
$statementCities->execute(); 

$cities = $statementCities->fetchAll();

//********************************************* DATA : pour le AJAX *****************************************
$flatsForm ='';

if (!empty($_GET))
{
	$flatsForm = $_GET['flatsForm'];
}

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
	
if (!empty($_GET))
{
		echo build_allFlats($flats);
}
else
{
	return [
		"cities" => $cities,
		"flats" => $flats
	];
}

