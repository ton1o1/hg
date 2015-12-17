<?php

include('inc/pdo.php');
include_once('func/lodging_search.php');

//***************************************** DATA : pour build_allCities() *****************************************

$statementCities = $pdo->prepare( 'SELECT city FROM lodging GROUP BY city ;' );
$statementCities->execute(); 

$cities = $statementCities->fetchAll();

//************************************** DATA : pour le Build_allFlats/AJAX *****************************************
$flatsForm ='';

if (!empty($_GET))
{
	$flatsForm = $_GET['flatsForm'];
}

if ( !empty($_GET['page']) ) 
{
	$page = $_GET['page'];
}
else
{
	$page = 1;
}

$pageSize = 2;
$offset = ( $page - 1 ) * $pageSize;


if ( strlen($flatsForm) < 3 )
{
	$statement = $pdo->prepare( sprintf (
								'SELECT user.firstname, user.lastname, lodging.address, lodging.id 
								FROM lodging JOIN user ON lodging.user_id = user.id
								ORDER BY id DESC LIMIT %1$u, %2$u ;',
								$offset,
								$pageSize
								));
	$statement->execute(); 
} 
else
{	
	$statement = $pdo->prepare( sprintf (
								'SELECT user.firstname, user.lastname, lodging.address, lodging.id 
								FROM lodging JOIN user ON lodging.user_id = user.id 
								WHERE city LIKE :research 
								ORDER BY id DESC LIMIT %1$u, %2$u ;',
								$offset,
								$pageSize
								));
	$statement->execute([
		':research' => '%' . $flatsForm . '%' 
		]);
}

$flats = $statement->fetchAll();

//************************************** DATA : pour le count *****************************************


$statementCount = $pdo->prepare(' SELECT COUNT(*) AS count FROM lodging; ');
$statementCount->execute();

$flatsCount = $statementCount->fetchAll();

// echo"<pre>";
// print_r($flatsCount);

//***********************************************************************************************************

if (!empty($_GET))
{
		echo build_allFlats($flats);
}
else
{
	return [
		"cities" => $cities,
		"flats" => $flats,
		"total" => $flatsCount
	];
}