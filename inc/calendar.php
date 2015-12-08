<?php

require_once 'pdo.php';
require_once '../func/datesArray.php';

$unavailable = array();

	$query = $pdo->prepare("SELECT * FROM booking WHERE lodging_id = :id");
	$query->execute([
		':id' => $_GET['id']
	]);
	// Si il y a des réservations
	if($query->rowCount() > 0){
		$bookings = $query->fetchAll();

		foreach($bookings as $booking) {
			$unavailable = array_merge(datesArray($booking['check_in'], $booking['check_out']), $unavailable);
		}
	}
echo json_encode($unavailable);
exit();